<?php

namespace App\Http\Controllers;

use App\Models\BancoHora;
use App\Models\BatidaPonto;
use App\Models\FechamentoPonto;
use App\Models\Funcionario;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EspelhoPontoController extends Controller
{
    public function index(Request $request): View
    {
        $empresaId = auth()->user()?->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'O usuário não está vinculado a uma empresa.'
        );

        $mes = $request->integer('mes', now()->month);
        $ano = $request->integer('ano', now()->year);
        $funcionarioId = $request->integer('funcionario_id') ?: null;

        $mes = $mes >= 1 && $mes <= 12
            ? $mes
            : now()->month;

        $ano = $ano >= 2020 && $ano <= 2100
            ? $ano
            : now()->year;

        $inicio = Carbon::create($ano, $mes, 1)->startOfMonth();
        $fim = $inicio->copy()->endOfMonth();

        $funcionarios = Funcionario::query()
            ->where('empresa_id', $empresaId)
            ->orderBy('nome')
            ->get([
                'id',
                'nome',
                'matricula',
            ]);

        $funcionario = null;
        $dias = collect();

        $totais = [
            'previstos' => 0,
            'trabalhados' => 0,
            'saldo' => 0,
            'creditos' => 0,
            'debitos' => 0,
        ];

        if ($funcionarioId) {
            $funcionario = Funcionario::query()
                ->with([
                    'horario',
                    'escala',
                ])
                ->where('empresa_id', $empresaId)
                ->findOrFail($funcionarioId);

            $batidas = BatidaPonto::query()
                ->where('empresa_id', $empresaId)
                ->where('funcionario_id', $funcionario->id)
                ->whereBetween('data', [
                    $inicio->toDateString(),
                    $fim->toDateString(),
                ])
                ->orderBy('data_hora')
                ->get()
                ->groupBy(
                    fn (BatidaPonto $batida): string =>
                        Carbon::parse($batida->data)->format('Y-m-d')
                );

            $bancoHoras = BancoHora::query()
                ->where('empresa_id', $empresaId)
                ->where('funcionario_id', $funcionario->id)
                ->whereBetween('data', [
                    $inicio->toDateString(),
                    $fim->toDateString(),
                ])
                ->get()
                ->keyBy(
                    fn (BancoHora $item): string =>
                        Carbon::parse($item->data)->format('Y-m-d')
                );

            for (
                $data = $inicio->copy();
                $data->lte($fim);
                $data->addDay()
            ) {
                $chave = $data->toDateString();

                $batidasDoDia = $batidas->get($chave, collect());
                $registroBanco = $bancoHoras->get($chave);

                $previstos = $this->calcularMinutosPrevistos(
                    $funcionario,
                    $data
                );

                $trabalhados = $this->calcularMinutosTrabalhados(
                    $batidasDoDia
                );

                $saldo = $this->calcularSaldo(
                    $data,
                    $registroBanco?->minutos,
                    $trabalhados,
                    $previstos
                );

                $totais['previstos'] += $previstos;
                $totais['trabalhados'] += $trabalhados;
                $totais['saldo'] += $saldo;

                if ($saldo > 0) {
                    $totais['creditos'] += $saldo;
                }

                if ($saldo < 0) {
                    $totais['debitos'] += abs($saldo);
                }

                $dias->push([
                    'data' => $data->copy(),
                    'batidas' => $batidasDoDia,
                    'previstos' => $previstos,
                    'trabalhados' => $trabalhados,
                    'saldo' => $saldo,
                    'motivo' => $registroBanco?->motivo,
                ]);
            }
        }

        $fechado = FechamentoPonto::query()
            ->where('empresa_id', $empresaId)
            ->where('ano', $ano)
            ->where('mes', $mes)
            ->exists();

        return view('espelho-ponto.index', compact(
            'funcionarios',
            'funcionario',
            'funcionarioId',
            'mes',
            'ano',
            'inicio',
            'fim',
            'dias',
            'totais',
            'fechado'
        ));
    }

    private function calcularMinutosPrevistos(
        Funcionario $funcionario,
        Carbon $data
    ): int {
        $horario = $funcionario->horario;

        if (! $horario || ! $this->ehDiaDeTrabalho($funcionario, $data)) {
            return 0;
        }

        $entrada = Carbon::parse(
            $data->toDateString().' '.$horario->entrada
        );

        $saida = Carbon::parse(
            $data->toDateString().' '.$horario->saida
        );

        if ($saida->lessThanOrEqualTo($entrada)) {
            $saida->addDay();
        }

        $minutosJornada = $entrada->diffInMinutes($saida);
        $minutosIntervalo = 0;

        if ($horario->inicio_intervalo && $horario->fim_intervalo) {
            $inicioIntervalo = Carbon::parse(
                $data->toDateString().' '.$horario->inicio_intervalo
            );

            $fimIntervalo = Carbon::parse(
                $data->toDateString().' '.$horario->fim_intervalo
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

    private function ehDiaDeTrabalho(
        Funcionario $funcionario,
        Carbon $data
    ): bool {
        $escala = $funcionario->escala;

        if (! $escala) {
            return ! $data->isWeekend();
        }

        $campo = match ($data->dayOfWeek) {
            Carbon::SUNDAY => 'domingo',
            Carbon::MONDAY => 'segunda',
            Carbon::TUESDAY => 'terca',
            Carbon::WEDNESDAY => 'quarta',
            Carbon::THURSDAY => 'quinta',
            Carbon::FRIDAY => 'sexta',
            Carbon::SATURDAY => 'sabado',
        };

        return (bool) $escala->{$campo};
    }

    private function calcularMinutosTrabalhados(
        Collection $batidas
    ): int {
        if ($batidas->count() < 2) {
            return 0;
        }

        $horarios = $batidas
            ->sortBy('data_hora')
            ->pluck('data_hora')
            ->map(
                fn ($dataHora): Carbon => Carbon::parse($dataHora)
            )
            ->values();

        $total = 0;

        for (
            $indice = 0;
            $indice + 1 < $horarios->count();
            $indice += 2
        ) {
            $entrada = $horarios[$indice];
            $saida = $horarios[$indice + 1];

            if ($saida->greaterThan($entrada)) {
                $total += $entrada->diffInMinutes($saida);
            }
        }

        return $total;
    }

    private function calcularSaldo(
        Carbon $data,
        ?int $saldoRegistrado,
        int $trabalhados,
        int $previstos
    ): int {
        if ($saldoRegistrado !== null) {
            return $saldoRegistrado;
        }

        /*
         * Não gera débito automático para hoje ou para datas futuras,
         * pois a jornada ainda pode estar em andamento.
         */
        if ($data->isToday() || $data->isFuture()) {
            return 0;
        }

        return $trabalhados - $previstos;
    }
}
