<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Funcionario;
use App\Models\Empresa;
use App\Models\Departamento;
use App\Models\Cargo;

class FuncionarioSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::first();
        $departamento = Departamento::first();
        $cargo = Cargo::first();

        Funcionario::insert([
            [
                'empresa_id' => $empresa->id,
                'departamento_id' => $departamento->id,
                'cargo_id' => $cargo->id,
                'nome' => 'João da Silva',
                'cpf' => '111.111.111-11',
                'rg' => '1234567',
                'pis' => '12345678901',
                'matricula' => 'FUNC001',
                'nascimento' => '1990-01-01',
                'admissao' => now(),
                'email' => 'joao@empresa.com',
                'telefone' => '(71) 99999-9999',
                'status' => 'ATIVO'
            ],
            [
                'empresa_id' => $empresa->id,
                'departamento_id' => $departamento->id,
                'cargo_id' => $cargo->id,
                'nome' => 'Maria Souza',
                'cpf' => '222.222.222-22',
                'rg' => '7654321',
                'pis' => '10987654321',
                'matricula' => 'FUNC002',
                'nascimento' => '1992-05-10',
                'admissao' => now(),
                'email' => 'maria@empresa.com',
                'telefone' => '(71) 98888-8888',
                'status' => 'ATIVO'
            ]
        ]);
    }
}
