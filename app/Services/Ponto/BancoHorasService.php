<?php

namespace App\Services\Ponto;

use App\Models\BancoHora;

class BancoHorasService
{
    public function registrar(
        int $funcionarioId,
        string $data,
        array $resultado,
        ?string $motivo = null
    ): void {

        BancoHora::updateOrCreate(

            [
                'funcionario_id' => $funcionarioId,
                'data' => $data,
            ],

            [
                'minutos' => $resultado['saldo'],
                'tipo' => $resultado['saldo'] >= 0
                    ? 'credito'
                    : 'debito',

                'motivo' => $motivo,

                'origem' => 'calculo_jornada',

                'referencia_tipo' => 'batidas',

                'referencia_id' => null,
            ]

        );
    }
}