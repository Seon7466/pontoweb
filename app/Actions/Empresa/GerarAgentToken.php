<?php

namespace App\Actions\Empresa;

use Illuminate\Support\Str;

class GerarAgentToken
{
    public function execute(): array
    {
        $token = 'pwa_' . Str::random(64);

        return [
            'token'  => $token,
            'prefix' => substr($token, 0, 16),
            'hash'   => hash('sha256', $token),
        ];
    }
}
