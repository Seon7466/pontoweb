public function handle($request, Closure $next)
{
    $user = auth()->user();

    if (! $user) {
        abort(403);
    }

    if (! $user->isMaster()) {
        abort(403, 'Acesso restrito ao Master.');
    }

    return $next($request);
}
