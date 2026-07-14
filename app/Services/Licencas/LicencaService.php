<?php

namespace App\Services\Licencas;

use App\Models\Empresa;
use App\Models\Licenca;

class LicencaService
{
    public function permiteAcesso(Empresa $empresa): bool
    {
        return (bool) $empresa->licenca?->valida;
    }

    public function dentroDoLimiteFuncionarios(Empresa $empresa): bool
    {
        $limite = $empresa->licenca?->limite_funcionarios_efetivo;

        return $limite === null || $empresa->funcionarios()->count() < $limite;
    }

    public function dentroDoLimiteRelogios(Empresa $empresa): bool
    {
        $limite = $empresa->licenca?->limite_relogios_efetivo;

        return $limite === null || $empresa->equipamentos()->count() < $limite;
    }
}
