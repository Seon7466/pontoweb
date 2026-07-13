<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Empresa;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::first();

        Departamento::insert([
            [
                'empresa_id' => $empresa->id,
                'nome' => 'Recursos Humanos',
                'responsavel' => 'RH Geral',
                'ativo' => true
            ],
            [
                'empresa_id' => $empresa->id,
                'nome' => 'Financeiro',
                'responsavel' => 'Gestor Financeiro',
                'ativo' => true
            ],
            [
                'empresa_id' => $empresa->id,
                'nome' => 'Operacional',
                'responsavel' => 'Supervisor Operações',
                'ativo' => true
            ]
        ]);
    }
}
