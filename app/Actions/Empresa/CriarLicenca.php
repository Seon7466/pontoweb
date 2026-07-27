<?php

namespace App\Actions\Empresa;

use App\Models\Empresa;
use App\Models\Licenca;

class CriarLicenca
{
    public function execute(
        Empresa $empresa,
        int $planoId,
        array $agentToken
    ): Licenca {
        return Licenca::create([
            'empresa_id' => $empresa->id,
            'plano_id' => $planoId,

            'codigo' => 'LIC-' . strtoupper(substr(md5(uniqid()), 0, 20)),

            'status' => Licenca::STATUS_TESTE,

            'ciclo_cobranca' => 'mensal',

            'inicia_em' => now(),

            'periodo_teste_ate' => now()->addDays(14),

            'renovacao_automatica' => false,

            'agent_token_prefix' => $agentToken['prefix'],

            'agent_token_hash' => $agentToken['hash'],

            'agent_token_rotacionado_em' => now(),
        ]);
    }
}
