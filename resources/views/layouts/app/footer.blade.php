<footer class="border-t border-slate-200/80 bg-white/70 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-screen-2xl flex-col items-center justify-between gap-2 text-xs text-slate-500 sm:flex-row">
        <p>
            © {{ now()->year }} PontoWeb. Todos os direitos reservados.
        </p>

        <p class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                Plataforma operacional
            </span>
            <span class="text-slate-300">•</span>
            <span>v{{ config('app.version', '0.8.0') }}</span>
        </p>
    </div>
</footer>
