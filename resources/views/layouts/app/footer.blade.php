<footer class="border-t border-slate-200 bg-white px-4 py-4 sm:px-6 lg:px-8">
    <div class="mx-auto flex max-w-screen-2xl flex-col items-center justify-between gap-2 text-xs text-slate-500 sm:flex-row">
        <p>
            © {{ now()->year }} PontoWeb. Todos os direitos reservados.
        </p>

        <p class="flex items-center gap-2">
            <span>Plataforma de Gestão da Jornada</span>
            <span class="text-slate-300">•</span>
            <span>v{{ config('app.version', '0.8.0') }}</span>
        </p>
    </div>
</footer>
