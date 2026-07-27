@php
    $navLink = 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition duration-150';
    $navActive = 'bg-blue-600 text-white shadow-sm shadow-blue-950/20';
    $navIdle = 'text-slate-300 hover:bg-slate-900 hover:text-white';
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-slate-800 bg-slate-950 text-white shadow-2xl transition-all duration-200 lg:translate-x-0"
    :class="{
        'translate-x-0': sidebarOpen,
        'lg:w-20': sidebarCollapsed,
        'lg:w-64': !sidebarCollapsed
    }"
    aria-label="Menu principal">
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800 px-4">
        <a href="{{ route('dashboard') }}"
            class="flex min-w-0 items-center gap-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            <span
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold shadow-lg shadow-blue-950/30">
                PW
            </span>

            <span x-show="! sidebarCollapsed" x-transition.opacity class="min-w-0">
                <span class="block truncate text-base font-bold tracking-tight">PontoWeb</span>
                <span class="block truncate text-[11px] text-slate-400">Gestão da jornada</span>
            </span>
        </a>

        <button type="button"
            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-900 hover:text-white lg:hidden"
            @click="sidebarOpen = false" aria-label="Fechar menu">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                aria-hidden="true">
                <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">
        <div class="space-y-1">
            <a href="{{ route('dashboard') }}"
                class="{{ $navLink }} {{ request()->routeIs('dashboard') ? $navActive : $navIdle }}">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 10.5 12 3l9 7.5V21a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1V10.5Z" />
                </svg>
                <span x-show="! sidebarCollapsed" x-transition.opacity>Dashboard</span>
            </a>
        </div>

        <div>
            <p x-show="! sidebarCollapsed" x-transition.opacity
                class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                Cadastros
            </p>

            <div class="space-y-1">
                @foreach ([['empresas.*', 'empresas.index', 'Empresa', 'building'], ['departamentos.*', 'departamentos.index', 'Departamentos', 'grid'], ['cargos.*', 'cargos.index', 'Cargos', 'briefcase'], ['funcionarios.*', 'funcionarios.index', 'Funcionários', 'users'], ['horarios.*', 'horarios.index', 'Horários', 'clock'], ['escalas.*', 'escalas.index', 'Escalas', 'calendar'], ['equipamentos.*', 'equipamentos.index', 'Equipamentos', 'device']] as [$pattern, $route, $label, $icon])
                    <a href="{{ route($route) }}"
                        class="{{ $navLink }} {{ request()->routeIs($pattern) ? $navActive : $navIdle }}"
                        title="{{ $label }}">
                        @switch($icon)
                            @case('building')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 21h16M6 21V5a1 1 0 0 1 1-1h7v17M14 8h4a1 1 0 0 1 1 1v12M9 8h2m-2 4h2m-2 4h2m7-4h-2m2 4h-2" />
                                </svg>
                            @break

                            @case('grid')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="3" y="3" width="7" height="7" rx="1" />
                                    <rect x="14" y="3" width="7" height="7" rx="1" />
                                    <rect x="3" y="14" width="7" height="7" rx="1" />
                                    <rect x="14" y="14" width="7" height="7" rx="1" />
                                </svg>
                            @break

                            @case('briefcase')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 6V4h6v2m-11 4h16v10H4V10Zm0 0V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2m-9 3h2" />
                                </svg>
                            @break

                            @case('users')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            @break

                            @case('clock')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" d="M12 7v5l3 2" />
                                </svg>
                            @break

                            @case('calendar')
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="3" y="5" width="18" height="16" rx="2" />
                                    <path stroke-linecap="round" d="M8 3v4m8-4v4M3 10h18" />
                                </svg>
                            @break

                            @default
                                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <rect x="5" y="3" width="14" height="18" rx="2" />
                                    <path stroke-linecap="round" d="M9 7h6m-6 4h6m-6 4h3" />
                                </svg>
                        @endswitch
                        <span x-show="! sidebarCollapsed" x-transition.opacity>{{ $label }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <p x-show="! sidebarCollapsed" x-transition.opacity
                class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                Jornada
            </p>

            <div class="space-y-1">
                <a href="{{ route('ponto.index') }}"
                    class="{{ $navLink }} {{ request()->routeIs('ponto.*') ? $navActive : $navIdle }}">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 7v5l4 2" />
                    </svg>
                    <span x-show="! sidebarCollapsed" x-transition.opacity>Batidas</span>
                </a>

                <span
                    class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600"
                    title="Em desenvolvimento">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 18h16M6 15l4-4 3 3 5-6" />
                    </svg>
                    <span x-show="! sidebarCollapsed" x-transition.opacity>Banco de Horas</span>
                </span>

                <span
                    class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600"
                    title="Em desenvolvimento">
                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5 3h11l3 3v15H5V3Zm11 0v4h4M8 12h8m-8 4h8M8 8h3" />
                    </svg>
                    <span x-show="! sidebarCollapsed" x-transition.opacity>Relatórios</span>
                </span>
            </div>
        </div>

        @if (auth()->user()?->is_master)
            <div>
                <p x-show="! sidebarCollapsed" x-transition.opacity
                    class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                    Plataforma
                </p>

                <div class="space-y-1">
                    <a href="{{ route('master.dashboard') }}"
                        class="{{ $navLink }} {{ request()->routeIs('master.dashboard') ? $navActive : $navIdle }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m12 3 2.4 4.86 5.36.78-3.88 3.78.92 5.34L12 15.24 7.2 17.76l.92-5.34L4.24 8.64l5.36-.78L12 3Z" />
                        </svg>
                        <span x-show="! sidebarCollapsed" x-transition.opacity>Painel Master</span>
                    </a>

                    <a href="{{ route('master.planos.index') }}"
                        class="{{ $navLink }} {{ request()->routeIs('master.planos.*') ? $navActive : $navIdle }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7.5h16M4 12h16M4 16.5h10" />
                            <rect x="3" y="4" width="18" height="16" rx="2" />
                        </svg>
                        <span x-show="! sidebarCollapsed" x-transition.opacity>Planos</span>
                    </a>

                    <a href="{{ route('master.licencas.index') }}"
                        class="{{ $navLink }} {{ request()->routeIs('master.licencas.*') ? $navActive : $navIdle }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 4h10a2 2 0 0 1 2 2v14l-7-3-7 3V6a2 2 0 0 1 2-2Z" />
                        </svg>
                        <span x-show="! sidebarCollapsed" x-transition.opacity>Licenças</span>
                    </a>

                    <a href="{{ route('master.agentes.index') }}"
                        class="{{ $navLink }} {{ request()->routeIs('master.agentes.*') ? $navActive : $navIdle }}">
                        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="3" y="5" width="18" height="14" rx="2" />
                            <path stroke-linecap="round" d="M7 9h.01M7 13h.01M11 9h6M11 13h6" />
                        </svg>
                        <span x-show="! sidebarCollapsed" x-transition.opacity>Agentes</span>
                    </a>
                </div>
            </div>
        @endif
    </nav>

    <div class="shrink-0 border-t border-slate-800 p-3">
        <a href="{{ route('profile.edit') }}"
            class="{{ $navLink }} {{ request()->routeIs('profile.*') ? $navActive : $navIdle }}">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="1.8">
                <circle cx="12" cy="8" r="4" />
                <path stroke-linecap="round" d="M4 21a8 8 0 0 1 16 0" />
            </svg>
            <span x-show="! sidebarCollapsed" x-transition.opacity>Perfil</span>
        </a>

        <button type="button"
            class="mt-1 hidden w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 transition hover:bg-slate-900 hover:text-white lg:flex"
            @click="toggleSidebar()">
            <svg class="h-5 w-5 shrink-0 transition-transform" :class="sidebarCollapsed ? 'rotate-180' : ''"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6" />
            </svg>
            <span x-show="! sidebarCollapsed" x-transition.opacity>Recolher menu</span>
        </button>
    </div>
</aside>
