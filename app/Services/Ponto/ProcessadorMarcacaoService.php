<?php

namespace App\Services\Ponto;

use App\Models\BatidaPonto;
use App\Models\Funcionario;
use App\Models\MarcacaoRelogio;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessadorMarcacaoService
{

    public function __construct(
        private readonly CalculoJornadaService $calculoJornadaService,
        private readonly BancoHorasService $bancoHorasService,
        private readonly CargaHorariaService $cargaHorariaService,
    ) {}

    public function processar(MarcacaoRelogio $marcacao): MarcacaoRelogio
    {
        return DB::transaction(function () use ($marcacao) {
            $funcionario = Funcionario::query()
                ->with('horario')
                ->where('empresa_id', $marcacao->empresa_id)
                ->where(function ($query) use ($marcacao) {
                    $query
                        ->where('codigo_relogio', $marcacao->codigo_funcionario)
                        ->orWhere('matricula', $marcacao->codigo_funcionario)
                        ->orWhere('pis', $marcacao->codigo_funcionario);
                })
                ->first();

            if (! $funcionario) {
                $marcacao->update([
                    'status' => 'erro',
                    'erro' => 'Funcionário não localizado pelo código, matrícula ou PIS.',
                ]);

                return $marcacao->refresh();
            }

            $hash = hash('sha256', implode('|', [
                $marcacao->empresa_id,
                $marcacao->equipamento_id,
                $marcacao->codigo_funcionario,
                $marcacao->data_hora->format('Y-m-d H:i:s'),
                $marcacao->nsr,
            ]));

            $data = $marcacao->data_hora->toDateString();

            $quantidadeNoDia = BatidaPonto::query()
                ->where('empresa_id', $marcacao->empresa_id)
                ->where('funcionario_id', $funcionario->id)
                ->whereDate('data', $data)
                ->count();

            $tipos = [
                'entrada',
                'saida_intervalo',
                'retorno_intervalo',
                'saida',
            ];

            $tipo = $tipos[min($quantidadeNoDia, 3)];

            $batida = BatidaPonto::firstOrCreate(
                [
                    'hash_importacao' => $hash,
                ],
                [
                    'empresa_id' => $marcacao->empresa_id,
                    'funcionario_id' => $funcionario->id,
                    'equipamento_id' => $marcacao->equipamento_id,
                    'data' => $data,
                    'data_hora' => $marcacao->data_hora,
                    'tipo' => $tipo,
                    'origem' => 'relogio_biometrico',
                    'identificador_externo' => $marcacao->nsr,
                    'observacao' => 'Importada automaticamente do equipamento.',
                    'manual' => false,
                ]
            );

            $marcacao->update([
                'status' => 'processada',
                'erro' => null,
                'batida_ponto_id' => $batida->id,
            ]);

            $this->recalcularBancoHoras($funcionario, $data);

            return $marcacao->refresh();
        });
    }

    private function recalcularBancoHoras(
        Funcionario $funcionario,
        string $data
    ): void {
        $horario = $funcionario->horario;

        if (! $horario) {
            return;
        }

        $batidas = BatidaPonto::query()
            ->where('empresa_id', $funcionario->empresa_id)
            ->where('funcionario_id', $funcionario->id)
            ->whereDate('data', $data)
            ->orderBy('data_hora')
            ->get()
            ->map(function (BatidaPonto $batida) {
                return Carbon::parse($batida->data_hora)
                    ->format('Y-m-d H:i:s');
            })
            ->all();

        $cargaHorariaMinutos = $this->cargaHorariaService->calcular(
            data: $data,
            entrada: $horario->entrada,
            saida: $horario->saida,
            inicioIntervalo: $horario->inicio_intervalo,
            fimIntervalo: $horario->fim_intervalo,
        );

        if ($cargaHorariaMinutos <= 0) {
            return;
        }

        $resultado = $this->calculoJornadaService->calcular(
            $batidas,
            $cargaHorariaMinutos
        );

        $this->bancoHorasService->registrar(
            funcionarioId: $funcionario->id,
            data: $data,
            resultado: $resultado,
            motivo: 'Cálculo automático baseado nas batidas do dia.'
        );
    }
}
