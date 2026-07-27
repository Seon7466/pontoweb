<?php

namespace App\Services\Ponto;

class CalculoJornadaService
{
    public function calcular(array $batidas, int $cargaHorariaMinutos): array
    {
        sort($batidas);

        if (count($batidas) < 2) {
            return [
                'trabalhado' => 0,
                'extra' => 0,
                'atraso' => $cargaHorariaMinutos,
                'saldo' => -$cargaHorariaMinutos,
            ];
        }

        $trabalhado = 0;

        for ($i = 0; $i < count($batidas) - 1; $i += 2) {
            $entrada = strtotime($batidas[$i]);
            $saida   = strtotime($batidas[$i + 1]);

            $trabalhado += intval(($saida - $entrada) / 60);
        }

        $saldo = $trabalhado - $cargaHorariaMinutos;

        return [
            'trabalhado' => $trabalhado,
            'extra'      => max(0, $saldo),
            'atraso'     => max(0, -$saldo),
            'saldo'      => $saldo,
        ];
    }
}