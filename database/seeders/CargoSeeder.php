<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\Departamento;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::first();
        $departamento = Departamento::first();

        Cargo::insert([
            [
                'empresa_id' => $empresa->id,
                'departamento_id' => $departamento->id,
                'nome' => 'Analista de RH',
                'cbo' => '2524-05',
                'descricao' => 'Responsável pelo setor de RH',
                'ativo' => true
            ],
            [
                'empresa_id' => $empresa->id,
                'departamento_id' => $departamento->id,
                'nome' => 'Auxiliar Administrativo',
                'cbo' => '4110-05',
                'descricao' => 'Apoio administrativo geral',
                'ativo' => true
            ],
            [
                'empresa_id' => $empresa->id,
                'departamento_id' => $departamento->id,
                'nome' => 'Supervisor Operacional',
                'cbo' => '4101-05',
                'descricao' => 'Supervisão de operações',
                'ativo' => true
            ]
        ]);
    }
}
