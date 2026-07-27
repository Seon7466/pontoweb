<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="PontoWeb — controle inteligente de jornada, funcionários, escalas, banco de horas e relógios Control iD.">
    <title>PontoWeb | Controle inteligente de ponto</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        :root {
            --primary: #155eef;
            --primary-dark: #0b3b91;
            --accent: #16b8c4;
            --dark: #101828;
            --text: #344054;
            --muted: #667085;
            --line: #e4e7ec;
            --surface: #ffffff;
            --background: #f7f9fc;
            --success: #12b76a;
            --warning: #f79009;
            --shadow-sm: 0 4px 16px rgba(16, 24, 40, .06);
            --shadow-lg: 0 28px 70px rgba(21, 94, 239, .16);
            --radius: 22px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            color: var(--dark);
            background: var(--surface);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        body.menu-open { overflow: hidden; }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        img, svg { display: block; max-width: 100%; }
        .container { width: min(1180px, calc(100% - 40px)); margin-inline: auto; }
        .section { padding: 104px 0; }
        .section-soft { background: var(--background); }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 12px; border-radius: 999px;
            color: var(--primary-dark); background: #eef4ff;
            font-size: .78rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase;
        }
        .section-heading { max-width: 720px; margin: 0 auto 54px; text-align: center; }
        .section-heading h2 { margin-top: 16px; font-size: clamp(2rem, 4vw, 3rem); line-height: 1.12; letter-spacing: -.04em; }
        .section-heading p { margin-top: 18px; color: var(--muted); font-size: 1.05rem; }

        .site-header {
            position: fixed; inset: 0 0 auto; z-index: 50;
            border-bottom: 1px solid transparent;
            transition: background .25s ease, border-color .25s ease, box-shadow .25s ease;
        }
        .site-header.scrolled {
            background: rgba(255,255,255,.88); border-color: rgba(228,231,236,.85);
            backdrop-filter: blur(18px); box-shadow: 0 8px 30px rgba(16,24,40,.05);
        }
        .nav { min-height: 78px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
        .brand { display: inline-flex; align-items: center; gap: 11px; font-size: 1.18rem; font-weight: 800; letter-spacing: -.03em; }
        .brand-mark {
            width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center;
            color: white; background: linear-gradient(145deg, var(--primary), var(--accent));
            box-shadow: 0 10px 24px rgba(21,94,239,.22);
        }
        .nav-links { display: flex; align-items: center; gap: 30px; color: #475467; font-size: .94rem; font-weight: 600; }
        .nav-links a { transition: color .2s ease; }
        .nav-links a:hover { color: var(--primary); }
        .nav-actions { display: flex; align-items: center; gap: 12px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 9px;
            min-height: 48px; padding: 0 20px; border: 1px solid transparent; border-radius: 12px;
            font-weight: 700; cursor: pointer; transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { color: #fff; background: linear-gradient(135deg, var(--primary), #2672ff); box-shadow: 0 12px 26px rgba(21,94,239,.24); }
        .btn-primary:hover { box-shadow: 0 16px 30px rgba(21,94,239,.3); }
        .btn-secondary { color: var(--dark); background: rgba(255,255,255,.75); border-color: var(--line); }
        .menu-button { display: none; width: 46px; height: 46px; border: 1px solid var(--line); border-radius: 12px; background: white; cursor: pointer; }

        .hero {
            position: relative; overflow: hidden; padding: 158px 0 96px;
            background:
                radial-gradient(circle at 78% 18%, rgba(22,184,196,.16), transparent 28%),
                radial-gradient(circle at 22% 22%, rgba(21,94,239,.12), transparent 30%),
                linear-gradient(180deg, #f8fbff 0%, #ffffff 82%);
        }
        .hero::before {
            content: ""; position: absolute; inset: 0; opacity: .38; pointer-events: none;
            background-image: linear-gradient(rgba(21,94,239,.06) 1px, transparent 1px), linear-gradient(90deg, rgba(21,94,239,.06) 1px, transparent 1px);
            background-size: 42px 42px; mask-image: linear-gradient(to bottom, black, transparent 78%);
        }
        .hero-grid { position: relative; display: grid; grid-template-columns: .92fr 1.08fr; align-items: center; gap: 72px; }
        .hero-copy h1 { margin-top: 22px; font-size: clamp(3rem, 6vw, 5.25rem); line-height: .98; letter-spacing: -.065em; }
        .hero-copy h1 span { color: var(--primary); }
        .hero-copy > p { max-width: 620px; margin-top: 26px; color: var(--text); font-size: clamp(1.05rem, 2vw, 1.22rem); }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 34px; }
        .hero-trust { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 30px; color: var(--muted); font-size: .88rem; font-weight: 600; }
        .hero-trust span { display: inline-flex; align-items: center; gap: 7px; }
        .check { color: var(--success); }

        .dashboard-wrap { position: relative; perspective: 1200px; }
        .glow { position: absolute; inset: 12% 8% -8%; background: linear-gradient(135deg, rgba(21,94,239,.38), rgba(22,184,196,.28)); filter: blur(55px); border-radius: 50%; }
        .dashboard-mockup {
            position: relative; overflow: hidden; border: 1px solid rgba(208,213,221,.9); border-radius: 24px;
            background: white; box-shadow: var(--shadow-lg); transform: rotateY(-3deg) rotateX(1deg);
            animation: float 5.5s ease-in-out infinite;
        }
        @keyframes float { 0%,100% { transform: rotateY(-3deg) rotateX(1deg) translateY(0); } 50% { transform: rotateY(-3deg) rotateX(1deg) translateY(-10px); } }
        .browser-bar { height: 44px; display: flex; align-items: center; gap: 7px; padding: 0 16px; background: #f9fafb; border-bottom: 1px solid #eaecf0; }
        .browser-bar i { width: 9px; height: 9px; border-radius: 50%; background: #d0d5dd; }
        .browser-address { width: 46%; height: 20px; margin-left: 10px; border-radius: 6px; background: #fff; border: 1px solid #eaecf0; }
        .app-shell { min-height: 420px; display: grid; grid-template-columns: 150px 1fr; }
        .sidebar-preview { padding: 18px 13px; color: #d0d5dd; background: #101828; }
        .sidebar-logo { display: flex; align-items: center; gap: 8px; margin-bottom: 25px; color: #fff; font-size: .72rem; font-weight: 800; }
        .sidebar-logo span { width: 24px; height: 24px; border-radius: 7px; background: linear-gradient(145deg, var(--primary), var(--accent)); }
        .side-item { height: 29px; margin: 7px 0; border-radius: 7px; background: rgba(255,255,255,.06); }
        .side-item.active { background: rgba(21,94,239,.36); }
        .app-content { padding: 24px; background: #f8fafc; }
        .app-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .app-title { width: 122px; height: 12px; border-radius: 999px; background: #344054; }
        .avatar { width: 30px; height: 30px; border-radius: 50%; background: #dbe8ff; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .stat { min-height: 86px; padding: 14px; border: 1px solid #eaecf0; border-radius: 12px; background: white; box-shadow: var(--shadow-sm); }
        .stat-label { width: 54%; height: 7px; border-radius: 999px; background: #d0d5dd; }
        .stat-value { width: 38%; height: 18px; margin-top: 14px; border-radius: 5px; background: #155eef; }
        .chart-card { height: 170px; margin-top: 14px; padding: 18px; border: 1px solid #eaecf0; border-radius: 14px; background: white; }
        .chart-title { width: 110px; height: 8px; border-radius: 999px; background: #98a2b3; }
        .bars { height: 112px; display: flex; align-items: end; gap: 9px; padding-top: 18px; }
        .bars i { flex: 1; border-radius: 5px 5px 2px 2px; background: linear-gradient(180deg, #155eef, #9bc2ff); }
        .floating-badge { position: absolute; right: -18px; bottom: 32px; display: flex; align-items: center; gap: 10px; padding: 13px 16px; border: 1px solid #d1fadf; border-radius: 14px; background: rgba(255,255,255,.94); box-shadow: var(--shadow-sm); font-size: .8rem; font-weight: 700; }
        .status-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--success); box-shadow: 0 0 0 5px rgba(18,183,106,.12); }

        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
        .feature-card { padding: 30px; border: 1px solid var(--line); border-radius: var(--radius); background: var(--surface); box-shadow: var(--shadow-sm); transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease; }
        .feature-card:hover { transform: translateY(-6px); border-color: #b9cdfd; box-shadow: 0 20px 44px rgba(16,24,40,.09); }
        .icon-box { width: 52px; height: 52px; display: grid; place-items: center; border-radius: 15px; color: var(--primary); background: #eef4ff; }
        .feature-card:nth-child(2n) .icon-box { color: #087f8c; background: #e8fbfc; }
        .feature-card h3 { margin-top: 22px; font-size: 1.12rem; }
        .feature-card p { margin-top: 9px; color: var(--muted); font-size: .94rem; }

        .workflow { display: grid; grid-template-columns: repeat(7, auto); align-items: center; justify-content: center; gap: 22px; }
        .step { width: 190px; min-height: 174px; padding: 26px 20px; text-align: center; border: 1px solid var(--line); border-radius: 20px; background: #fff; box-shadow: var(--shadow-sm); }
        .step-number { width: 38px; height: 38px; display: grid; place-items: center; margin: 0 auto 17px; border-radius: 12px; color: white; background: linear-gradient(145deg, var(--primary), var(--accent)); font-weight: 800; }
        .step h3 { font-size: 1rem; }
        .step p { margin-top: 8px; color: var(--muted); font-size: .84rem; }
        .arrow { color: #98a2b3; font-size: 1.5rem; }

        .integrations { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
        .integration { min-height: 130px; display: grid; place-items: center; padding: 26px; text-align: center; border: 1px solid var(--line); border-radius: 18px; background: white; }
        .integration strong { display: block; margin-top: 11px; }
        .integration svg { color: var(--primary); }

        .cta-box { position: relative; overflow: hidden; padding: 62px; border-radius: 30px; color: white; background: linear-gradient(135deg, #0b3b91, #155eef 58%, #16b8c4); box-shadow: 0 30px 70px rgba(21,94,239,.25); }
        .cta-box::after { content: ""; position: absolute; width: 300px; height: 300px; right: -100px; top: -120px; border: 55px solid rgba(255,255,255,.08); border-radius: 50%; }
        .cta-content { position: relative; z-index: 1; max-width: 720px; }
        .cta-content h2 { font-size: clamp(2rem, 4vw, 3.25rem); line-height: 1.08; letter-spacing: -.045em; }
        .cta-content p { margin: 18px 0 30px; color: rgba(255,255,255,.82); font-size: 1.05rem; }
        .cta-box .btn-secondary { background: white; border-color: white; color: var(--primary-dark); }

        footer { padding: 72px 0 30px; color: #d0d5dd; background: #101828; }
        .footer-grid { display: grid; grid-template-columns: 1.6fr repeat(3, 1fr); gap: 48px; }
        .footer-brand p { max-width: 340px; margin-top: 18px; color: #98a2b3; }
        .footer-col h4 { margin-bottom: 18px; color: #fff; }
        .footer-col a { display: block; margin: 11px 0; color: #98a2b3; font-size: .92rem; }
        .footer-col a:hover { color: white; }
        .footer-bottom { display: flex; justify-content: space-between; gap: 20px; margin-top: 54px; padding-top: 24px; border-top: 1px solid #344054; color: #667085; font-size: .84rem; }

        .reveal { opacity: 0; transform: translateY(22px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.visible { opacity: 1; transform: none; }

        @media (max-width: 980px) {
            .nav-links { display: none; position: fixed; inset: 78px 20px auto; padding: 22px; flex-direction: column; align-items: stretch; gap: 8px; border: 1px solid var(--line); border-radius: 18px; background: white; box-shadow: 0 24px 60px rgba(16,24,40,.18); }
            .nav-links.open { display: flex; }
            .nav-links a { padding: 11px 8px; }
            .nav-actions .btn { display: none; }
            .menu-button { display: grid; place-items: center; }
            .hero-grid { grid-template-columns: 1fr; gap: 58px; }
            .hero-copy { text-align: center; }
            .hero-copy > p { margin-inline: auto; }
            .hero-actions, .hero-trust { justify-content: center; }
            .dashboard-wrap { width: min(760px, 100%); margin-inline: auto; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .workflow { grid-template-columns: 1fr; }
            .arrow { transform: rotate(90deg); justify-self: center; }
            .step { width: min(100%, 420px); }
            .integrations { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1.5fr 1fr 1fr; }
            .footer-brand { grid-column: 1 / -1; }
        }

        @media (max-width: 640px) {
            .container { width: min(100% - 28px, 1180px); }
            .section { padding: 78px 0; }
            .hero { padding: 128px 0 72px; }
            .hero-copy h1 { font-size: clamp(2.7rem, 14vw, 4rem); }
            .hero-actions .btn { width: 100%; }
            .hero-trust { flex-direction: column; align-items: center; gap: 10px; }
            .app-shell { grid-template-columns: 78px 1fr; min-height: 320px; }
            .sidebar-preview { padding: 14px 9px; }
            .sidebar-logo { font-size: 0; }
            .stats { grid-template-columns: 1fr; }
            .stat:nth-child(n+2) { display: none; }
            .app-content { padding: 15px; }
            .chart-card { height: 150px; }
            .floating-badge { right: 8px; bottom: -24px; }
            .features-grid, .integrations { grid-template-columns: 1fr; }
            .feature-card { padding: 25px; }
            .cta-box { padding: 40px 26px; border-radius: 24px; }
            .footer-grid { grid-template-columns: 1fr 1fr; }
            .footer-brand { grid-column: 1 / -1; }
            .footer-bottom { flex-direction: column; }
        }

        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
<header class="site-header" id="siteHeader">
    <div class="container nav">
        <a class="brand" href="#inicio" aria-label="PontoWeb - Início">
            <span class="brand-mark" aria-hidden="true">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M7 3v18M7 7h7.5a4.5 4.5 0 0 1 0 9H7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
            </span>
            PontoWeb
        </a>

        <nav class="nav-links" id="navLinks" aria-label="Navegação principal">
            <a href="#inicio">Início</a>
            <a href="#recursos">Recursos</a>
            <a href="#como-funciona">Como funciona</a>
            <a href="#integracoes">Integrações</a>
            <a href="#contato">Contato</a>
        </nav>

        <div class="nav-actions">
            @if (Route::has('login'))
                @auth
                    <a class="btn btn-primary" href="{{ url('/dashboard') }}">Abrir dashboard</a>
                @else
                    <a class="btn btn-primary" href="{{ route('login') }}">Entrar no sistema</a>
                @endauth
            @endif
            <button class="menu-button" id="menuButton" aria-label="Abrir menu" aria-expanded="false" aria-controls="navLinks">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            </button>
        </div>
    </div>
</header>

<main>
    <section class="hero" id="inicio">
        <div class="container hero-grid">
            <div class="hero-copy reveal">
                <span class="eyebrow">Controle de ponto inteligente</span>
                <h1>Gestão de jornada <span>simples, segura</span> e conectada.</h1>
                <p>Gerencie funcionários, horários, escalas, banco de horas, relatórios e relógios Control iD em uma única plataforma.</p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="#recursos">
                        Conhecer o sistema
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="m9 18 6-6-6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    @if (Route::has('login'))
                        @auth
                            <a class="btn btn-secondary" href="{{ url('/dashboard') }}">Ir para o dashboard</a>
                        @else
                            <a class="btn btn-secondary" href="{{ route('login') }}">Entrar</a>
                        @endauth
                    @endif
                </div>
                <div class="hero-trust">
                    <span><svg class="check" width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg> Dados protegidos</span>
                    <span><svg class="check" width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg> Acesso em nuvem</span>
                    <span><svg class="check" width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="m5 12 4 4L19 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg> Integração Control iD</span>
                </div>
            </div>

            <div class="dashboard-wrap reveal" aria-label="Prévia do dashboard PontoWeb">
                <div class="glow"></div>
                <div class="dashboard-mockup">
                    <div class="browser-bar"><i></i><i></i><i></i><span class="browser-address"></span></div>
                    <div class="app-shell">
                        <aside class="sidebar-preview">
                            <div class="sidebar-logo"><span></span>PontoWeb</div>
                            <div class="side-item active"></div><div class="side-item"></div><div class="side-item"></div><div class="side-item"></div><div class="side-item"></div><div class="side-item"></div>
                        </aside>
                        <div class="app-content">
                            <div class="app-top"><span class="app-title"></span><span class="avatar"></span></div>
                            <div class="stats">
                                <div class="stat"><div class="stat-label"></div><div class="stat-value"></div></div>
                                <div class="stat"><div class="stat-label"></div><div class="stat-value" style="background:#16b8c4"></div></div>
                                <div class="stat"><div class="stat-label"></div><div class="stat-value" style="background:#12b76a"></div></div>
                            </div>
                            <div class="chart-card">
                                <div class="chart-title"></div>
                                <div class="bars"><i style="height:43%"></i><i style="height:65%"></i><i style="height:52%"></i><i style="height:82%"></i><i style="height:70%"></i><i style="height:94%"></i><i style="height:78%"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="floating-badge"><span class="status-dot"></span> Relógio Control iD conectado</div>
            </div>
        </div>
    </section>

    <section class="section" id="recursos">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">Tudo em um só lugar</span>
                <h2>Recursos para uma gestão de ponto completa</h2>
                <p>Do cadastro do funcionário ao fechamento da jornada, o PontoWeb centraliza sua operação com clareza e segurança.</p>
            </div>
            <div class="features-grid">
                @php
                    $features = [
                        ['Funcionários', 'Cadastro completo, documentos, vínculos e informações organizadas.', 'users'],
                        ['Registro de ponto', 'Receba e acompanhe marcações com integração Control iD.', 'clock'],
                        ['Relatórios', 'Espelho de ponto, indicadores e informações para o fechamento.', 'chart'],
                        ['Multiempresa', 'Gerencie diferentes empresas e unidades em um único acesso.', 'building'],
                        ['Nuvem', 'Acesse o sistema com segurança de qualquer lugar e dispositivo.', 'cloud'],
                        ['Segurança', 'Controle de permissões e proteção dos dados da operação.', 'shield'],
                    ];
                @endphp
                @foreach ($features as [$title, $description, $icon])
                    <article class="feature-card reveal">
                        <div class="icon-box" aria-hidden="true">
                            @if ($icon === 'users')
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
                            @elseif ($icon === 'clock')
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.9"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
                            @elseif ($icon === 'chart')
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none"><path d="M4 19V9M10 19V5M16 19v-7M22 19H2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
                            @elseif ($icon === 'building')
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none"><path d="M4 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17M16 9h3a1 1 0 0 1 1 1v11M8 7h4M8 11h4M8 15h4M2 21h20" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
                            @elseif ($icon === 'cloud')
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none"><path d="M17.5 19H7a5 5 0 1 1 1-9.9A7 7 0 0 1 21 12a4 4 0 0 1-3.5 7Z" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
                            @else
                                <svg width="25" height="25" viewBox="0 0 24 24" fill="none"><path d="M12 3 4 6v5c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V6l-8-3Z" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/></svg>
                            @endif
                        </div>
                        <h3>{{ $title }}</h3>
                        <p>{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section section-soft" id="como-funciona">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">Como funciona</span>
                <h2>Da empresa ao relatório, sem complicação</h2>
                <p>Um fluxo simples para transformar marcações em informação confiável para sua equipe.</p>
            </div>
            <div class="workflow reveal">
                <article class="step"><span class="step-number">1</span><h3>Cadastre a empresa</h3><p>Configure unidades, regras e responsáveis.</p></article>
                <span class="arrow">→</span>
                <article class="step"><span class="step-number">2</span><h3>Adicione funcionários</h3><p>Centralize dados e vínculos da equipe.</p></article>
                <span class="arrow">→</span>
                <article class="step"><span class="step-number">3</span><h3>Integre o relógio</h3><p>Conecte equipamentos e receba marcações.</p></article>
                <span class="arrow">→</span>
                <article class="step"><span class="step-number">4</span><h3>Gere relatórios</h3><p>Acompanhe a jornada e faça o fechamento.</p></article>
            </div>
        </div>
    </section>

    <section class="section" id="integracoes">
        <div class="container">
            <div class="section-heading reveal">
                <span class="eyebrow">Integrações</span>
                <h2>Conectado à operação da sua empresa</h2>
                <p>Importe, sincronize e integre dados sem depender de processos manuais complexos.</p>
            </div>
            <div class="integrations reveal">
                <div class="integration"><div><svg width="34" height="34" viewBox="0 0 24 24" fill="none"><rect x="4" y="3" width="16" height="18" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 8h8M8 12h8M9 17h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><strong>Control iD</strong></div></div>
                <div class="integration"><div><svg width="34" height="34" viewBox="0 0 24 24" fill="none"><path d="M5 3h10l4 4v14H5V3Z" stroke="currentColor" stroke-width="1.8"/><path d="M15 3v5h5M8 13h8M8 17h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><strong>CSV</strong></div></div>
                <div class="integration"><div><svg width="34" height="34" viewBox="0 0 24 24" fill="none"><path d="m8 9-4 3 4 3M16 9l4 3-4 3M14 5l-4 14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg><strong>API</strong></div></div>
                <div class="integration"><div><svg width="34" height="34" viewBox="0 0 24 24" fill="none"><path d="M17.5 19H7a5 5 0 1 1 1-9.9A7 7 0 0 1 21 12a4 4 0 0 1-3.5 7Z" stroke="currentColor" stroke-width="1.8"/></svg><strong>Cloud</strong></div></div>
            </div>
        </div>
    </section>

    <section class="section" id="contato">
        <div class="container">
            <div class="cta-box reveal">
                <div class="cta-content">
                    <h2>Simplifique a gestão de jornada da sua empresa.</h2>
                    <p>Centralize funcionários, marcações, escalas, banco de horas e relatórios em um produto feito para crescer com sua operação.</p>
                    @if (Route::has('login'))
                        @auth
                            <a class="btn btn-secondary" href="{{ url('/dashboard') }}">Abrir o PontoWeb</a>
                        @else
                            <a class="btn btn-secondary" href="{{ route('login') }}">Entrar no sistema</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="#inicio"><span class="brand-mark"><svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M7 3v18M7 7h7.5a4.5 4.5 0 0 1 0 9H7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg></span>PontoWeb</a>
                <p>Controle inteligente de jornada de trabalho, com gestão centralizada e integração à sua operação.</p>
            </div>
            <div class="footer-col"><h4>Produto</h4><a href="#recursos">Recursos</a><a href="#como-funciona">Como funciona</a><a href="#integracoes">Integrações</a></div>
            <div class="footer-col"><h4>Empresa</h4><a href="#contato">Contato</a><a href="#contato">Suporte</a><a href="#inicio">PontoWeb</a></div>
            <div class="footer-col"><h4>Legal</h4><a href="#">Privacidade</a><a href="#">Termos de uso</a><a href="#">LGPD</a></div>
        </div>
        <div class="footer-bottom"><span>© {{ date('Y') }} PontoWeb. Todos os direitos reservados.</span><span>Controle de ponto com identidade própria.</span></div>
    </div>
</footer>

<script>
    const header = document.getElementById('siteHeader');
    const menuButton = document.getElementById('menuButton');
    const navLinks = document.getElementById('navLinks');

    const updateHeader = () => header.classList.toggle('scrolled', window.scrollY > 16);
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });

    menuButton?.addEventListener('click', () => {
        const open = navLinks.classList.toggle('open');
        document.body.classList.toggle('menu-open', open);
        menuButton.setAttribute('aria-expanded', String(open));
    });

    navLinks?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
        navLinks.classList.remove('open');
        document.body.classList.remove('menu-open');
        menuButton?.setAttribute('aria-expanded', 'false');
    }));

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(element => observer.observe(element));
</script>
</body>
</html>
