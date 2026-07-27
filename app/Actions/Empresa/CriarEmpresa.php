<?php

namespace App\Actions\Empresa;

use App\Models\Empresa;

class CriarEmpresa
{
    public function execute(array $dados): Empresa
    {
        return Empresa::create([
            'razao_social'  => $dados['razao_social'],
            'nome_fantasia' => $dados['nome_fantasia'],
            'cnpj'          => $dados['cnpj'],
            'email'         => $dados['email'] ?? null,
            'telefone'      => $dados['telefone'] ?? null,
            'endereco'      => $dados['endereco'] ?? null,
            'numero'        => $dados['numero'] ?? null,
            'bairro'        => $dados['bairro'] ?? null,
            'cidade'        => $dados['cidade'] ?? null,
            'estado'        => $dados['estado'] ?? null,
            'cep'           => $dados['cep'] ?? null,
            'ativo'         => $dados['ativo'] ?? true,
        ]);
    }
}
