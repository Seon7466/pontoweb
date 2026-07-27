<?php

namespace App\Services\Ponto;

use Carbon\Carbon;

class CargaHorariaService
{
    public function calcular(
        string $data,
        string $entrada,
        string $saida,
        ?string $inicioIntervalo = null,
        ?string $fimIntervalo = null,
    ): int {
        $inicioJornada = Carbon::parse("{$data} {$entrada}");
        $fimJornada = Carbon::parse("{$data} {$saida}");

        if ($fimJornada->lessThanOrEqualTo($inicioJornada)) {
            $fimJornada->addDay();
        }

        $minutosJornada = $inicioJornada->diffInMinutes($fimJornada);

        $minutosIntervalo = 0;

        if ($inicioIntervalo && $fimIntervalo) {
            $inicioPausa = Carbon::parse(
                "{$data} {$inicioIntervalo}"
            );

            $fimPausa = Carbon::parse(
                "{$data} {$fimIntervalo}"
            );

            if ($fimPausa->lessThan($inicioPausa)) {
                $fimPausa->addDay();
            }

            $minutosIntervalo = $inicioPausa->diffInMinutes(
                $fimPausa
            );
        }

        return max(
            0,
            $minutosJornada - $minutosIntervalo
        );
    }
}
