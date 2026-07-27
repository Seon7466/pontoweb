<header class="sticky top-0 z-30 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
    <div class="flex h-[4.5rem] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button
                type="button"
                class="pw-focus inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 lg:hidden"
                @click="sidebarOpen = true"
                aria-label="Abrir menu"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="min-w-0">
                @isset($header)
                    <div class="[&>h2]:truncate [&>h2]:text-lg [&>h2]:font-bold [&>h2]:tracking-tight [&>h2]:text-slate-950">
                        {{ $header }}
                    </div>
                @else
                    <div>
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-950">PontoWeb</h1>
                        <p class="hidden truncate text-xs text-slate-500 sm:block">Gestão da jornada de trabalho</p>
                    </div>
                @endisset
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <div class="hidden min-w-0 items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 px-3 py-2 md:flex">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-40"></span>
                    <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                </span>

                <div class="min-w-0 text-right">
                    <p class="truncate text-sm font-semibold text-slate-800">
                        {{ auth()->user()->name ?? 'Usuário' }}
                    </p>
                    <p class="max-w-56 truncate text-[11px] text-slate-500">
                        {{ auth()->user()->empresa?->nome_fantasia ?? (auth()->user()?->is_master ? 'Administração da plataforma' : 'Empresa não vinculada') }}
                    </p>
                </div>
            </div>

            <a
                href="{{ route('profile.edit') }}"
                class="pw-focus flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 text-sm font-bold text-white shadow-md shadow-blue-600/20 transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-blue-600/25"
                title="Perfil"
            >
                {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="pw-focus inline-flex h-10 items-center justify-center gap-2 rounded-xl px-3 text-sm font-semibold text-slate-500 transition hover:bg-red-50 hover:text-red-600"
                    title="Sair"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5M15 12H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7" />
                    </svg>
                    <span class="hidden sm:inline">Sair</span>
                </button>
            </form>
        </div>
    </div>
</header>
