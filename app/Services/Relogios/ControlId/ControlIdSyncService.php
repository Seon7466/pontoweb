<?php

namespace App\Services\Relogios\ControlId;

use App\Models\Equipamento;
use App\Models\MarcacaoRelogio;
use App\Services\Ponto\ProcessadorMarcacaoService;
use Illuminate\Support\Facades\DB;

class ControlIdSyncService
{
    public function __construct(
        private readonly ControlIdClassAdapter $adapter,
        private readonly ProcessadorMarcacaoService $processador,
    ) {
    }

    public function sincronizar(Equipamento $equipamento, bool $processar = true): array
    {
        $resultado = ['recebidas' => 0, 'novas' => 0, 'duplicadas' => 0, 'processadas' => 0, 'erros' => 0];
        $maiorNsr = $equipamento->ultimo_nsr;

        try {
            foreach ($this->adapter->buscarMarcacoes($equipamento) as $registro) {
                $resultado['recebidas']++;
                $hash = hash('sha256', implode('|', [
                    $equipamento->empresa_id,
                    $equipamento->id,
                    $registro['codigo'],
                    $registro['data_hora']->format('Y-m-d H:i:sP'),
                    $registro['nsr'] ?? '',
                ]));

                $marcacao = DB::transaction(function () use ($equipamento, $registro, $hash, &$resultado) {
                    $existente = MarcacaoRelogio::where('hash', $hash)->first();
                    if ($existente) {
                        $resultado['duplicadas']++;
                        return $existente;
                    }

                    $resultado['novas']++;
                    return MarcacaoRelogio::create([
                        'empresa_id' => $equipamento->empresa_id,
                        'equipamento_id' => $equipamento->id,
                        'codigo_funcionario' => $registro['codigo'],
                        'data_hora' => $registro['data_hora'],
                        'nsr' => $registro['nsr'] ?? null,
                        'hash' => $hash,
                        'dados_brutos' => $registro['dados_brutos'] ?? null,
                        'status' => 'pendente',
                    ]);
                });

                if ($processar && $marcacao->status === 'pendente') {
                    $marcacao = $this->processador->processar($marcacao);
                    $marcacao->status === 'processada' ? $resultado['processadas']++ : $resultado['erros']++;
                }

                if (is_numeric($registro['nsr'] ?? null)) {
                    $maiorNsr = max((int) ($maiorNsr ?? 0), (int) $registro['nsr']);
                }
            }

            $equipamento->forceFill([
                'ultimo_nsr' => $maiorNsr,
                'ultima_sincronizacao_em' => now(),
                'ultima_conexao_em' => now(),
                'ultima_mensagem' => sprintf(
                    'Sincronização concluída: %d recebidas, %d novas, %d duplicadas, %d processadas e %d erros.',
                    ...array_values($resultado)
                ),
            ])->save();

            return $resultado;
        } catch (\Throwable $e) {
            $equipamento->forceFill([
                'ultima_falha_em' => now(),
                'ultima_mensagem' => $e->getMessage(),
            ])->save();
            throw $e;
        }
    }
}
