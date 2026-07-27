<?php

namespace App\Services\Empresa;

use App\Actions\Empresa\CriarAdministrador;
use App\Actions\Empresa\CriarEmpresa;
use App\Actions\Empresa\CriarLicenca;
use App\Actions\Empresa\GerarAgentToken;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class EmpresaService
{
    public function criar(array $dados): array
    {
        return DB::transaction(function () use ($dados) {
            $empresa = app(CriarEmpresa::class)->execute($dados);

            $usuario = app(CriarAdministrador::class)->execute(
                $empresa,
                $dados
            );

            $token = app(GerarAgentToken::class)->execute();

            app(CriarLicenca::class)->execute(
                $empresa,
                (int) $dados['plano_id'],
                $token
            );

            return [
                'empresa' => $empresa,
                'usuario' => $usuario,
                'agent_token' => $token['token'],
            ];
        });
    }

    public function atualizar(Empresa $empresa, array $dados): Empresa
    {
        return DB::transaction(function () use ($empresa, $dados) {
            $empresa->update([
                'razao_social' => $dados['razao_social'],
                'nome_fantasia' => $dados['nome_fantasia'],
                'cnpj' => $dados['cnpj'],
                'email' => $dados['email'] ?? null,
                'telefone' => $dados['telefone'] ?? null,
                'cep' => $dados['cep'] ?? null,
                'endereco' => $dados['endereco'] ?? null,
                'numero' => $dados['numero'] ?? null,
                'bairro' => $dados['bairro'] ?? null,
                'cidade' => $dados['cidade'] ?? null,
                'estado' => $dados['estado'] ?? null,
                'ativo' => $dados['ativo'] ?? false,
            ]);

            if ($empresa->licenca) {
                $empresa->licenca->update([
                    'plano_id' => $dados['plano_id'],
                ]);
            }

            return $empresa->fresh();
        });
    }

    public function excluir(Empresa $empresa): void
    {
        DB::transaction(function () use ($empresa) {
            $empresa->delete();
        });
    }
}
