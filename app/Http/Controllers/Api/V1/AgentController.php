<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\HeartbeatRequest;
use App\Http\Requests\Api\V1\MarcacoesBatchRequest;
use App\Models\Agente;
use App\Models\Equipamento;
use App\Models\MarcacaoRelogio;
use App\Services\Ponto\ProcessadorMarcacaoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AgentController extends Controller
{
    public function heartbeat(HeartbeatRequest $request): JsonResponse
    {
        $licenca = $request->attributes->get('agent_license');
        $empresa = $request->attributes->get('agent_company');
        $data = $request->validated();

        $agente = Agente::query()->updateOrCreate(
            ['licenca_id' => $licenca->id, 'installation_id' => $data['installation_id']],
            [
                'empresa_id' => $empresa->id,
                'nome' => $data['name'] ?? null,
                'machine_name' => $data['machine_name'],
                'agent_version' => $data['agent_version'],
                'os_name' => $data['os_name'] ?? null,
                'os_version' => $data['os_version'] ?? null,
                'local_ip' => $data['local_ip'] ?? null,
                'public_ip' => $request->ip(),
                'status' => 'online',
                'last_seen_at' => now(),
                'metadata' => $data['metadata'] ?? null,
            ]
        );

        $agente->logs()->create([
            'empresa_id' => $empresa->id,
            'level' => 'info',
            'event' => 'heartbeat',
            'message' => 'Heartbeat recebido com sucesso.',
            'context' => ['agent_version' => $agente->agent_version, 'public_ip' => $agente->public_ip],
            'occurred_at' => now(),
        ]);

        return response()->json([
            'ok' => true,
            'server_time' => now()->toIso8601String(),
            'agent' => ['id' => $agente->id, 'status' => 'online'],
            'license' => [
                'code' => $licenca->codigo,
                'status' => $licenca->status_efetivo,
                'expires_at' => $licenca->termina_em?->toDateString(),
            ],
            'poll_interval_seconds' => 60,
        ]);
    }

    public function configuration(Request $request): JsonResponse
    {
        $licenca = $request->attributes->get('agent_license');
        $empresa = $request->attributes->get('agent_company');

        $equipamentos = Equipamento::query()
            ->where('empresa_id', $empresa->id)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get(['id', 'nome', 'fabricante', 'modelo', 'numero_serie', 'ip', 'porta', 'protocolo', 'tipo_integracao', 'modo_671', 'verificar_ssl', 'timeout_segundos', 'timezone', 'ultimo_nsr']);

        return response()->json([
            'company' => ['id' => $empresa->id, 'name' => $empresa->nome_fantasia ?: $empresa->razao_social],
            'license' => [
                'code' => $licenca->codigo,
                'plan' => $licenca->plano?->nome,
                'employee_limit' => $licenca->limite_funcionarios_efetivo,
                'device_limit' => $licenca->limite_relogios_efetivo,
                'expires_at' => $licenca->termina_em?->toDateString(),
            ],
            'equipment' => $equipamentos,
            'sync' => ['interval_seconds' => 60, 'batch_size' => 500],
        ]);
    }

    public function markings(MarcacoesBatchRequest $request, ProcessadorMarcacaoService $processor): JsonResponse
    {
        $licenca = $request->attributes->get('agent_license');
        $empresa = $request->attributes->get('agent_company');
        $data = $request->validated();

        $agente = Agente::query()
            ->where('licenca_id', $licenca->id)
            ->where('installation_id', $data['installation_id'])
            ->first();

        if (! $agente) {
            return response()->json(['message' => 'Execute o heartbeat antes de enviar marcações.', 'code' => 'heartbeat_required'], 409);
        }

        $equipamento = Equipamento::query()
            ->where('empresa_id', $empresa->id)
            ->find($data['equipment_id']);

        if (! $equipamento) {
            return response()->json(['message' => 'Equipamento não pertence à empresa da licença.', 'code' => 'invalid_equipment'], 422);
        }

        $result = ['received' => count($data['markings']), 'created' => 0, 'duplicates' => 0, 'processed' => 0, 'errors' => 0];

        foreach ($data['markings'] as $item) {
            try {
                DB::transaction(function () use ($item, $empresa, $equipamento, $processor, &$result) {
                    $raw = $item['raw'] ?? $item;
                    $hash = hash('sha256', implode('|', [
                        $empresa->id,
                        $equipamento->id,
                        $item['employee_code'],
                        (string) $item['occurred_at'],
                        $item['nsr'] ?? '',
                    ]));

                    $marcacao = MarcacaoRelogio::query()->firstOrCreate(
                        ['hash' => $hash],
                        [
                            'empresa_id' => $empresa->id,
                            'equipamento_id' => $equipamento->id,
                            'codigo_funcionario' => $item['employee_code'],
                            'data_hora' => $item['occurred_at'],
                            'nsr' => $item['nsr'] ?? null,
                            'dados_brutos' => is_array($raw) ? $raw : ['value' => $raw],
                            'status' => 'pendente',
                        ]
                    );

                    if (! $marcacao->wasRecentlyCreated) {
                        $result['duplicates']++;
                        return;
                    }

                    $result['created']++;
                    $processed = $processor->processar($marcacao);
                    $processed->status === 'processada' ? $result['processed']++ : $result['errors']++;
                });
            } catch (Throwable) {
                $result['errors']++;
            }
        }

        $agente->update(['last_seen_at' => now(), 'last_sync_at' => now(), 'status' => 'online']);
        $agente->logs()->create([
            'empresa_id' => $empresa->id,
            'level' => $result['errors'] > 0 ? 'warning' : 'info',
            'event' => 'markings_batch',
            'message' => 'Lote de marcações recebido.',
            'context' => $result,
            'occurred_at' => now(),
        ]);

        return response()->json(['ok' => true, 'result' => $result], 202);
    }
}
