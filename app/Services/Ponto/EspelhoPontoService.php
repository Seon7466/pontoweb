<?php

namespace App\Services\Ponto;

use App\Models\BatidaPonto;
use App\Models\Funcionario;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class EspelhoPontoService
{
    public function __construct(
        private readonly CalculoJornadaService $calculoJornadaService,
    ) {
    }

    public function gerar(
        Funcionario $funcionario,
        Carbon $inicio,
        Carbon $fim
    ): array {
        $funcionario->loadMissing([
            'empresa',
            'departamento',
            'cargo',
            'horario',
        ]);

        $batidasPorData = BatidaPonto::query()
            ->where('empresa_id', $funcionario->empresa_id)
            ->where('funcionario_id', $funcionario->id)
            ->whereBetween('data', [
                $inicio->toDateString(),
                $fim->toDateString(),
            ])
            ->orderBy('data_hora')
            ->get()
            ->groupBy(fn (BatidaPonto $batida) => $batida->data->format('Y-m-d'));

        $dias = [];

        $totais = [
            'previstos' => 0,
            'trabalhados' => 0,
            'creditos' => 0,
            'debitos' => 0,
            'saldo' => 0,
        ];

        foreach (CarbonPeriod::create($inicio, $fim) as $data) {
            $dataFormatada = $data->format('Y-m-d');

            $batidas = $batidasPorData->get($dataFormatada, collect());

            $previstos = $this->calcularCargaHorariaMinutos(
                $funcionario,
                $data
            );

            $resultado = $this->calculoJornadaService->calcular(
                $batidas
                    ->map(fn (BatidaPonto $batida) => $batida->data_hora->format('Y-m-d H:i:s'))
                    ->all(),
                $previstos
            );

            $dias[] = [
                'data' => $data->copy(),
                'batidas' => $batidas,
                'previstos' => $previstos,
                'trabalhados' => $resultado['trabalhado'],
                'saldo' => $resultado['saldo'],
            ];

            $totais['previstos'] += $previstos;
            $totais['trabalhados'] += $resultado['trabalhado'];
            $totais['creditos'] += $resultado['extra'];
            $totais['debitos'] += $resultado['atraso'];
            $totais['saldo'] += $resultado['saldo'];
        }

        return [
            'dias' => $dias,
            'totais' => $totais,
        ];
    }

    private function calcularCargaHorariaMinutos(
        Funcionario $funcionario,
        Carbon $data
    ): int {
        $horario = $funcionario->horario;

        if (! $horario) {
            return 0;
        }

        $inicioJornada = Carbon::parse(
            $data->format('Y-m-d').' '.$horario->entrada
        );

        $fimJornada = Carbon::parse(
            $data->format('Y-m-d').' '.$horario->saida
        );

        if ($fimJornada->lessThanOrEqualTo($inicioJornada)) {
            $fimJornada->addDay();
        }

        $minutosJornada = $inicioJornada->diffInMinutes($fimJornada);

        $minutosIntervalo = 0;

        if ($horario->inicio_intervalo && $horario->fim_intervalo) {
            $inicioIntervalo = Carbon::parse(
                $data->format('Y-m-d').' '.$horario->inicio_intervalo
            );

            $fimIntervalo = Carbon::parse(
                $data->format('Y-m-d').' '.$horario->fim_intervalo
            );

            if ($fimIntervalo->lessThan($inicioIntervalo)) {
                $fimIntervalo->addDay();
            }

            $minutosIntervalo = $inicioIntervalo->diffInMinutes(
                $fimIntervalo
            );
        }

        return max(0, $minutosJornada - $minutosIntervalo);
    }
}
