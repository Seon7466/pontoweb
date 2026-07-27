<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @hasSection('title')
            @yield('title') | {{ config('app.name', 'PontoWeb') }}
        @else
            {{ $title ?? config('app.name', 'PontoWeb') }}
        @endif
    </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --pw-primary: #155eef;
            --pw-primary-dark: #0b3b91;
            --pw-accent: #16b8c4;
            --pw-dark: #101828;
            --pw-muted: #667085;
            --pw-border: #e4e7ec;
            --pw-bg: #f6f8fc;
            --pw-surface: #ffffff;
            --pw-success: #12b76a;
            --pw-warning: #f79009;
            --pw-danger: #f04438;
            --sidebar-width: 17rem;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--pw-dark);
            background: var(--pw-bg);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select {
            font: inherit;
        }

        .app-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: var(--sidebar-width) minmax(0, 1fr);
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 50;
            width: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            padding: 1.25rem 1rem;
            color: #fff;
            background:
                radial-gradient(circle at 20% 0, rgba(22, 184, 196, .18), transparent 18rem),
                linear-gradient(180deg, #102a56 0%, #0b1f42 100%);
            border-right: 1px solid rgba(255, 255, 255, .08);
            transition: transform .22s ease;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .35rem .45rem 1.25rem;
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .brand-mark {
            width: 2.55rem;
            height: 2.55rem;
            display: grid;
            place-items: center;
            border-radius: .8rem;
            background: linear-gradient(135deg, var(--pw-primary), var(--pw-accent));
            box-shadow: 0 10px 24px rgba(21, 94, 239, .28);
        }

        .brand-mark svg {
            width: 1.45rem;
            height: 1.45rem;
        }

        .company-switcher {
            margin-bottom: 1rem;
            padding: .8rem;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: .85rem;
            background: rgba(255, 255, 255, .06);
        }

        .company-switcher small {
            display: block;
            margin-bottom: .25rem;
            color: rgba(255, 255, 255, .55);
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .company-switcher strong {
            display: block;
            overflow: hidden;
            font-size: .86rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .nav-title {
            margin: .9rem .65rem .45rem;
            color: rgba(255, 255, 255, .40);
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .nav {
            display: grid;
            gap: .3rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            min-height: 2.75rem;
            padding: .7rem .75rem;
            color: rgba(255, 255, 255, .72);
            border-radius: .75rem;
            font-size: .88rem;
            font-weight: 500;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .08);
            transform: translateX(2px);
        }

        .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, rgba(21, 94, 239, .98), rgba(22, 184, 196, .80));
            box-shadow: 0 10px 24px rgba(0, 0, 0, .16);
        }

        .nav-link svg {
            flex: 0 0 auto;
            width: 1.2rem;
            height: 1.2rem;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 1rem;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .75rem;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: .85rem;
            background: rgba(255, 255, 255, .06);
        }

        .avatar {
            flex: 0 0 auto;
            width: 2.25rem;
            height: 2.25rem;
            display: grid;
            place-items: center;
            color: #fff;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--pw-primary), var(--pw-accent));
            font-size: .78rem;
            font-weight: 800;
        }

        .user-meta {
            min-width: 0;
            flex: 1;
        }

        .user-meta strong,
        .user-meta span {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-meta strong {
            font-size: .78rem;
        }

        .user-meta span {
            color: rgba(255, 255, 255, .52);
            font-size: .68rem;
        }

        .main {
            grid-column: 2;
            min-width: 0;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            min-height: 4.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: .9rem clamp(1rem, 3vw, 2rem);
            background: rgba(246, 248, 252, .88);
            border-bottom: 1px solid rgba(228, 231, 236, .85);
            backdrop-filter: blur(16px);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: .8rem;
            min-width: 0;
        }

        .menu-toggle {
            display: none;
            width: 2.55rem;
            height: 2.55rem;
            place-items: center;
            color: var(--pw-dark);
            background: #fff;
            border: 1px solid var(--pw-border);
            border-radius: .75rem;
            cursor: pointer;
        }

        .page-heading {
            min-width: 0;
        }

        .page-heading h1 {
            margin: 0;
            font-size: clamp(1.25rem, 2vw, 1.65rem);
            letter-spacing: -.035em;
        }

        .page-heading p {
            margin: .18rem 0 0;
            color: var(--pw-muted);
            font-size: .78rem;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: .7rem;
        }

        .icon-button,
        .profile-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.55rem;
            color: #475467;
            background: #fff;
            border: 1px solid var(--pw-border);
            border-radius: .75rem;
        }

        .icon-button {
            position: relative;
            width: 2.55rem;
        }

        .icon-button svg {
            width: 1.15rem;
            height: 1.15rem;
        }

        .notification-dot {
            position: absolute;
            top: .55rem;
            right: .58rem;
            width: .42rem;
            height: .42rem;
            border: 2px solid #fff;
            border-radius: 999px;
            background: var(--pw-danger);
        }

        .profile-button {
            gap: .65rem;
            padding: .35rem .7rem .35rem .35rem;
        }

        .profile-button .avatar {
            width: 1.85rem;
            height: 1.85rem;
            font-size: .66rem;
        }

        .profile-button span {
            max-width: 9rem;
            overflow: hidden;
            font-size: .78rem;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .content {
            padding: clamp(1rem, 3vw, 2rem);
        }

        .overlay {
            position: fixed;
            inset: 0;
            z-index: 40;
            display: none;
            background: rgba(16, 24, 40, .55);
            backdrop-filter: blur(2px);
        }

        @media (max-width: 1080px) {
            .app-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                grid-column: 1;
            }

            .menu-toggle {
                display: grid;
            }

            .overlay.show {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .profile-button span {
                display: none;
            }

            .topbar {
                padding-inline: .8rem;
            }

            .content {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar" aria-label="Menu principal">
            <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="brand">
                <span class="brand-mark">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path
                            d="M5 12.5V7.8C5 6.806 5.806 6 6.8 6h10.4c.994 0 1.8.806 1.8 1.8v8.4c0 .994-.806 1.8-1.8 1.8H9.5"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M8.5 9.5v5M6 12h5" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M14.5 10h2M14.5 14h2" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </span>
                <span>PontoWeb</span>
            </a>

            <div class="company-switcher">
                <small>Empresa atual</small>
                <strong>{{ session('empresa_nome', 'Empresa demonstração') }}</strong>
            </div>

            <div class="nav-title">Gestão</div>
            <nav class="nav">
                <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M4 13h6V4H4v9Zm10 7h6v-9h-6v9ZM4 20h6v-3H4v3Zm10-13h6V4h-6v3Z" stroke="currentColor"
                            stroke-width="1.8" stroke-linejoin="round" />
                    </svg>
                    Dashboard
                </a>

                @if (Route::has('funcionarios.index'))
                    <a href="{{ route('funcionarios.index') }}"
                        class="nav-link {{ request()->routeIs('funcionarios.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M16 20v-1.6c0-2.2-1.8-4-4-4H7c-2.2 0-4 1.8-4 4V20M9.5 10.4A3.7 3.7 0 1 0 9.5 3a3.7 3.7 0 0 0 0 7.4ZM16 11c2 0 3.5-1.6 3.5-3.5S18 4 16 4M18 14.5c1.8.6 3 2.2 3 4.1V20"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        Funcionários
                    </a>
                @endif

                @if (Route::has('horarios.index'))
                    <a href="{{ route('horarios.index') }}"
                        class="nav-link {{ request()->routeIs('horarios.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                            <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        Horários
                    </a>
                @endif

                @if (Route::has('escalas.index'))
                    <a href="{{ route('escalas.index') }}"
                        class="nav-link {{ request()->routeIs('escalas.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="5" width="18" height="16" rx="2.5" stroke="currentColor"
                                stroke-width="1.8" />
                            <path d="M8 3v4M16 3v4M3 10h18M8 14h3M13 14h3M8 18h3" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                        Escalas
                    </a>
                @endif

                @if (Route::has('banco-horas.index'))
                    <a href="{{ route('banco-horas.index') }}"
                        class="nav-link {{ request()->routeIs('banco-horas.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 8h16M6 4h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"
                                stroke="currentColor" stroke-width="1.8" />
                            <path d="M8 13h3M8 16h5M16 13v3" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                        Banco de Horas
                    </a>
                @endif

                @if (Route::has('equipamentos.index'))
                    <a href="{{ route('equipamentos.index') }}"
                        class="nav-link {{ request()->routeIs('equipamentos.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <rect x="5" y="3" width="14" height="18" rx="3" stroke="currentColor"
                                stroke-width="1.8" />
                            <path d="M9 7h6M9 11h6M9 15h3M15 16.5h.01" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                        Equipamentos
                    </a>
                @endif
            </nav>

            <div class="nav-title">Análises</div>
            <nav class="nav">
                @if (Route::has('relatorios.index'))
                    <a href="{{ route('relatorios.index') }}"
                        class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M5 20V10M12 20V4M19 20v-7" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                        Relatórios
                    </a>
                @endif

                @if (Route::has('configuracoes.index'))
                    <a href="{{ route('configuracoes.index') }}"
                        class="nav-link {{ request()->routeIs('configuracoes.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.8" />
                            <path
                                d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1A1.7 1.7 0 0 0 4.6 15 1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1A1.7 1.7 0 0 0 9 4.6 1.7 1.7 0 0 0 10 3V2.8h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"
                                stroke="currentColor" stroke-width="1.4" stroke-linejoin="round" />
                        </svg>
                        Configurações
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                @if (Route::has('profile.edit'))
                    <a href="{{ route('profile.edit') }}" class="user-card">
                        <div class="avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="user-meta">
                            <strong>{{ auth()->user()->name ?? 'Usuário' }}</strong>
                            <span>{{ auth()->user()->email ?? 'usuario@pontoweb.com.br' }}</span>
                        </div>
                    </a>
                @else
                    <div class="user-card">
                        <div class="avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>
                        <div class="user-meta">
                            <strong>{{ auth()->user()->name ?? 'Usuário' }}</strong>
                            <span>{{ auth()->user()->email ?? 'usuario@pontoweb.com.br' }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        <div class="overlay" id="overlay"></div>

        <main class="main">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" id="menu-toggle" type="button" aria-label="Abrir menu">
                        <svg viewBox="0 0 24 24" fill="none" width="20" height="20">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>

                    <div class="page-heading">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1>@yield('page-title', 'PontoWeb')</h1>
                            <p>@yield('page-subtitle', 'Gestão da jornada')</p>
                        @endisset
                    </div>
                </div>

                <div class="topbar-actions">
                    {{-- seus controles do topo --}}
                </div>
            </header>

            <div class="content">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menu-toggle');

        function closeSidebar() {
            sidebar?.classList.remove('open');
            overlay?.classList.remove('show');
            document.body.style.overflow = '';
        }

        menuToggle?.addEventListener('click', () => {
            const isOpen = sidebar.classList.toggle('open');
            overlay.classList.toggle('show', isOpen);
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        overlay?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1080) {
                closeSidebar();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
