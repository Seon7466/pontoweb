<?php

namespace Database\Seeders;

use App\Models\Plano;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    public function run(): void
    {
        $planos = [
            [
                'nome' => 'Starter',
                'descricao' => 'Plano inicial para pequenas empresas com uma operação de ponto enxuta.',
                'max_funcionarios' => 20,
                'max_relogios' => 1,
                'valor_mensal' => 499.99,
                'armazenamento_gb' => 5,
                'suporte' => 'E-mail',
                'api_disponivel' => false,
                'app_mobile' => false,
                'bi_disponivel' => false,
                'geolocalizacao' => false,
                'integracoes' => false,
                'backup_automatico' => true,
                'marketplace' => false,
                'ordem' => 10,
                'ativo' => true,
            ],
            [
                'nome' => 'Basic',
                'descricao' => 'Plano para empresas em crescimento que operam com um único relógio.',
                'max_funcionarios' => 50,
                'max_relogios' => 1,
                'valor_mensal' => 799.99,
                'armazenamento_gb' => 10,
                'suporte' => 'E-mail + Chat',
                'api_disponivel' => false,
                'app_mobile' => false,
                'bi_disponivel' => false,
                'geolocalizacao' => false,
                'integracoes' => false,
                'backup_automatico' => true,
                'marketplace' => true,
                'ordem' => 20,
                'ativo' => true,
            ],
            [
                'nome' => 'Business',
                'descricao' => 'Plano avançado para operações com múltiplos relógios e integrações.',
                'max_funcionarios' => 100,
                'max_relogios' => 5,
                'valor_mensal' => 2499.90,
                'armazenamento_gb' => 50,
                'suporte' => 'Prioritário',
                'api_disponivel' => true,
                'app_mobile' => true,
                'bi_disponivel' => true,
                'geolocalizacao' => true,
                'integracoes' => true,
                'backup_automatico' => true,
                'marketplace' => true,
                'ordem' => 30,
                'ativo' => true,
            ],
            [
                'nome' => 'Enterprise',
                'descricao' => 'Plano personalizado para grandes operações, com limites e SLA definidos em contrato.',
                'max_funcionarios' => null,
                'max_relogios' => null,
                'valor_mensal' => null,
                'armazenamento_gb' => null,
                'suporte' => 'Dedicado / SLA',
                'api_disponivel' => true,
                'app_mobile' => true,
                'bi_disponivel' => true,
                'geolocalizacao' => true,
                'integracoes' => true,
                'backup_automatico' => true,
                'marketplace' => true,
                'ordem' => 40,
                'ativo' => true,
            ],
        ];

        foreach ($planos as $plano) {
            Plano::query()->updateOrCreate(
                ['nome' => $plano['nome']],
                $plano,
            );
        }
    }
}
