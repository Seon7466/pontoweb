<?php

namespace App\Services\Ponto;

use App\Models\FechamentoPonto;

class FechamentoService
{
    public function fechar(
        int $empresaId,
        int $ano,
        int $mes
    ): FechamentoPonto {
        return FechamentoPonto::firstOrCreate(
            [
                'empresa_id' => $empresaId,
                'ano' => $ano,
                'mes' => $mes,
            ],
            [
                'fechado_em' => now(),
                'user_id' => auth()->id(),
            ]
        );
    }

    public function fechado(
        int $empresaId,
        int $ano,
        int $mes
    ): bool {
        return FechamentoPonto::query()
            ->where('empresa_id', $empresaId)
            ->where('ano', $ano)
            ->where('mes', $mes)
            ->exists();
    }
}
