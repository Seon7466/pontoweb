<?php

namespace App\Http\Middleware;

use App\Models\Licenca;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAgentToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (! is_string($token) || ! str_starts_with($token, 'pwa_')) {
            return $this->unauthorized('Token do PontoWeb Agent ausente ou inválido.');
        }

        $licenca = Licenca::query()
            ->with(['empresa', 'plano'])
            ->where('agent_token_prefix', substr($token, 0, 12))
            ->first();

        if (! $licenca || ! filled($licenca->agent_token_hash)
            || ! hash_equals($licenca->agent_token_hash, hash('sha256', $token))) {
            return $this->unauthorized('Token do PontoWeb Agent não reconhecido.');
        }

        if (! $licenca->valida || ! $licenca->empresa?->ativo) {
            return response()->json([
                'message' => 'Licença ausente, vencida, suspensa ou empresa inativa.',
                'code' => 'license_invalid',
            ], 403);
        }

        $request->attributes->set('agent_license', $licenca);
        $request->attributes->set('agent_company', $licenca->empresa);

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json(['message' => $message, 'code' => 'unauthorized_agent'], 401);
    }
}
