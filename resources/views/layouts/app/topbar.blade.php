<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button
                type="button"
                class="rounded-xl p-2 text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Abrir menu"
            >
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="min-w-0">
                @isset($header)
                    {{ $header }}
                @else
                    <div>
                        <h1 class="truncate text-lg font-semibold text-slate-900">PontoWeb</h1>
                        <p class="hidden truncate text-xs text-slate-500 sm:block">Plataforma de gestão da jornada</p>
                    </div>
                @endisset
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <button
                type="button"
                class="relative hidden rounded-xl p-2.5 text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 sm:inline-flex"
                title="Notificações"
                aria-label="Notificações"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 8a6 6 0 1 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                </svg>
                <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
            </button>

            <div class="hidden min-w-0 text-right md:block">
                <p class="truncate text-sm font-semibold text-slate-800">
                    {{ auth()->user()->name ?? 'Usuário' }}
                </p>
                <p class="max-w-56 truncate text-xs text-slate-500">
                    {{ auth()->user()->empresa?->nome_fantasia ?? (auth()->user()?->is_master ? 'Administração da plataforma' : 'Empresa não vinculada') }}
                </p>
            </div>

            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-100 text-sm font-bold text-brand-700 md:hidden">
                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-pw.button type="submit" variant="ghost" size="sm">
                    Sair
                </x-pw.button>
            </form>
        </div>
    </div>
</header>
