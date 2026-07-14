<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmpresaHasValidLicense
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user?->is_master) {
            return $next($request);
        }

        $licenca = $user?->empresa?->licenca;

        if (! $licenca || ! $licenca->valida) {
            abort(403, 'A licença desta empresa está ausente, vencida ou suspensa. Entre em contato com o suporte.');
        }

        return $next($request);
    }
}
