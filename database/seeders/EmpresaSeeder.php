<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([

            'razao_social' => 'Empresa Exemplo LTDA',

            'nome_fantasia' => 'PontoWeb',

            'cnpj' => '00.000.000/0001-00',

            'inscricao_estadual' => '',

            'telefone' => '(11) 99999-9999',

            'email' => 'contato@pontoweb.com',

            'cep' => '00000-000',

            'endereco' => 'Rua Principal',

            'numero' => '100',

            'bairro' => 'Centro',

            'cidade' => 'São Paulo',

            'estado' => 'SP',

            'ativo' => true

        ]);
    }
}
