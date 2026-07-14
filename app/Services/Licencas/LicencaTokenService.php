<?php

namespace App\Services\Licencas;

use App\Models\Licenca;
use Illuminate\Support\Str;

class LicencaTokenService
{
    public function gerar(Licenca $licenca): string
    {
        $token = 'pwa_'.Str::lower(Str::random(56));

        $licenca->forceFill([
            'agent_token_prefix' => substr($token, 0, 12),
            'agent_token_hash' => hash('sha256', $token),
            'agent_token_rotacionado_em' => now(),
        ])->save();

        return $token;
    }

    public function validar(Licenca $licenca, string $token): bool
    {
        return filled($licenca->agent_token_hash)
            && hash_equals($licenca->agent_token_hash, hash('sha256', $token));
    }
}
