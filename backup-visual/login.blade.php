<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Entrar | {{ config('app.name', 'PontoWeb') }}</title>

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
            --pw-border: #d0d5dd;
            --pw-bg: #f5f7fb;
            --pw-surface: #ffffff;
            --pw-danger: #d92d20;
            --pw-success: #027a48;
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
            background:
                radial-gradient(circle at 0 0, rgba(21, 94, 239, .10), transparent 28rem),
                radial-gradient(circle at 100% 100%, rgba(22, 184, 196, .10), transparent 24rem),
                var(--pw-bg);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        .page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(28rem, .95fr);
        }

        .brand-panel {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem clamp(2rem, 5vw, 5rem);
            color: #fff;
            background:
                linear-gradient(145deg, rgba(11, 59, 145, .98), rgba(21, 94, 239, .94) 55%, rgba(22, 184, 196, .90));
        }

        .brand-panel::before,
        .brand-panel::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            pointer-events: none;
        }

        .brand-panel::before {
            width: 31rem;
            height: 31rem;
            right: -12rem;
            top: -10rem;
            border: 1px solid rgba(255, 255, 255, .20);
            box-shadow:
                0 0 0 4rem rgba(255, 255, 255, .035),
                0 0 0 9rem rgba(255, 255, 255, .025);
        }

        .brand-panel::after {
            width: 22rem;
            height: 22rem;
            left: -10rem;
            bottom: -10rem;
            background: rgba(255, 255, 255, .055);
        }

        .brand-top,
        .brand-copy,
        .feature-list {
            position: relative;
            z-index: 1;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .logo-mark {
            width: 2.7rem;
            height: 2.7rem;
            display: grid;
            place-items: center;
            border-radius: .85rem;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .24);
            backdrop-filter: blur(10px);
        }

        .logo-mark svg {
            width: 1.55rem;
            height: 1.55rem;
        }

        .brand-copy {
            max-width: 42rem;
            padding: 5rem 0 3rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .75rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .20);
            background: rgba(255, 255, 255, .10);
            font-size: .78rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .eyebrow-dot {
            width: .48rem;
            height: .48rem;
            border-radius: 999px;
            background: #7ff2d8;
            box-shadow: 0 0 0 .3rem rgba(127, 242, 216, .15);
        }

        .brand-copy h1 {
            margin: 1.5rem 0 1rem;
            max-width: 12ch;
            font-size: clamp(2.5rem, 5vw, 4.8rem);
            line-height: .98;
            letter-spacing: -.06em;
        }

        .brand-copy p {
            margin: 0;
            max-width: 38rem;
            color: rgba(255, 255, 255, .82);
            font-size: clamp(1rem, 1.6vw, 1.2rem);
            line-height: 1.75;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .feature {
            min-height: 7rem;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .09);
            backdrop-filter: blur(10px);
        }

        .feature strong {
            display: block;
            margin-bottom: .35rem;
            font-size: .92rem;
        }

        .feature span {
            color: rgba(255, 255, 255, .70);
            font-size: .78rem;
            line-height: 1.45;
        }

        .login-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .login-card {
            width: min(100%, 30rem);
            animation: enter .55s ease both;
        }

        .mobile-logo {
            display: none;
            margin-bottom: 2rem;
            color: var(--pw-dark);
        }

        .mobile-logo .logo-mark {
            color: #fff;
            border: 0;
            background: linear-gradient(135deg, var(--pw-primary-dark), var(--pw-primary), var(--pw-accent));
        }

        .login-card h2 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 2.7rem);
            line-height: 1.1;
            letter-spacing: -.045em;
        }

        .subtitle {
            margin: .8rem 0 2rem;
            color: var(--pw-muted);
            line-height: 1.6;
        }

        .status {
            margin-bottom: 1.25rem;
            padding: .85rem 1rem;
            border-radius: .8rem;
            color: var(--pw-success);
            background: #ecfdf3;
            border: 1px solid #abefc6;
            font-size: .88rem;
        }

        .field {
            margin-bottom: 1.15rem;
        }

        .field-row {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: .5rem;
        }

        label {
            display: block;
            font-size: .88rem;
            font-weight: 600;
        }

        .input-wrap {
            position: relative;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 3.25rem;
            padding: 0 2.9rem 0 1rem;
            color: var(--pw-dark);
            background: var(--pw-surface);
            border: 1px solid var(--pw-border);
            border-radius: .8rem;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: var(--pw-primary);
            box-shadow: 0 0 0 .25rem rgba(21, 94, 239, .11);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            right: 1rem;
            width: 1.15rem;
            height: 1.15rem;
            color: #98a2b3;
            transform: translateY(-50%);
            pointer-events: none;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: .75rem;
            display: grid;
            place-items: center;
            width: 2rem;
            height: 2rem;
            padding: 0;
            color: #667085;
            background: transparent;
            border: 0;
            border-radius: .45rem;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .password-toggle:hover {
            background: #f2f4f7;
        }

        .error {
            margin: .45rem 0 0;
            color: var(--pw-danger);
            font-size: .8rem;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: .25rem 0 1.4rem;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            color: #475467;
            font-size: .86rem;
            cursor: pointer;
        }

        .remember input {
            width: 1rem;
            height: 1rem;
            accent-color: var(--pw-primary);
        }

        .forgot {
            color: var(--pw-primary);
            font-size: .86rem;
            font-weight: 600;
        }

        .forgot:hover,
        .back-link:hover {
            text-decoration: underline;
        }

        .submit {
            width: 100%;
            min-height: 3.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            padding: .8rem 1.2rem;
            color: #fff;
            background: linear-gradient(135deg, var(--pw-primary-dark), var(--pw-primary));
            border: 0;
            border-radius: .8rem;
            box-shadow: 0 10px 25px rgba(21, 94, 239, .20);
            font-weight: 700;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, opacity .18s ease;
        }

        .submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(21, 94, 239, .26);
        }

        .submit:active {
            transform: translateY(0);
        }

        .submit:disabled {
            opacity: .7;
            cursor: wait;
        }

        .back {
            margin-top: 1.5rem;
            text-align: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: var(--pw-muted);
            font-size: .88rem;
            font-weight: 500;
        }

        .security-note {
            display: flex;
            align-items: flex-start;
            gap: .65rem;
            margin-top: 2rem;
            padding-top: 1.25rem;
            color: #98a2b3;
            border-top: 1px solid #eaecf0;
            font-size: .76rem;
            line-height: 1.5;
        }

        .security-note svg {
            flex: 0 0 auto;
            width: 1rem;
            height: 1rem;
            margin-top: .12rem;
        }

        @keyframes enter {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 980px) {
            .page {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                display: none;
            }

            .login-panel {
                min-height: 100vh;
                padding: 2rem 1.25rem;
            }

            .mobile-logo {
                display: inline-flex;
            }
        }

        @media (max-width: 520px) {
            .login-panel {
                align-items: flex-start;
                padding-top: 2rem;
            }

            .form-options {
                align-items: flex-start;
                flex-direction: column;
                gap: .75rem;
            }

            .login-card h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <section class="brand-panel" aria-label="Apresentação do PontoWeb">
        <div class="brand-top">
            <a href="{{ url('/') }}" class="logo" aria-label="Voltar para o PontoWeb">
                <span class="logo-mark">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12.5V7.8C5 6.806 5.806 6 6.8 6h10.4c.994 0 1.8.806 1.8 1.8v8.4c0 .994-.806 1.8-1.8 1.8H9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8.5 9.5v5M6 12h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14.5 10h2M14.5 14h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>PontoWeb</span>
            </a>
        </div>

        <div class="brand-copy">
            <span class="eyebrow">
                <span class="eyebrow-dot"></span>
                Controle inteligente
            </span>

            <h1>Jornada de trabalho sob controle.</h1>

            <p>
                Centralize funcionários, horários, escalas, banco de horas,
                equipamentos Control iD e relatórios em uma única plataforma.
            </p>
        </div>

        <div class="feature-list" aria-label="Principais benefícios">
            <div class="feature">
                <strong>Gestão centralizada</strong>
                <span>Informações importantes reunidas em um único ambiente.</span>
            </div>
            <div class="feature">
                <strong>Integração Control iD</strong>
                <span>Equipamentos e marcações conectados ao sistema.</span>
            </div>
            <div class="feature">
                <strong>Acesso em nuvem</strong>
                <span>Consulte a operação com segurança de qualquer lugar.</span>
            </div>
        </div>
    </section>

    <main class="login-panel">
        <div class="login-card">
            <a href="{{ url('/') }}" class="logo mobile-logo" aria-label="Voltar para o PontoWeb">
                <span class="logo-mark">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M5 12.5V7.8C5 6.806 5.806 6 6.8 6h10.4c.994 0 1.8.806 1.8 1.8v8.4c0 .994-.806 1.8-1.8 1.8H9.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8.5 9.5v5M6 12h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M14.5 10h2M14.5 14h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
                <span>PontoWeb</span>
            </a>

            <h2>Bem-vindo ao PontoWeb</h2>
            <p class="subtitle">Entre com seus dados para acessar o painel de gestão.</p>

            @if (session('status'))
                <div class="status" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="field">
                    <div class="field-row">
                        <label for="email">E-mail</label>
                    </div>

                    <div class="input-wrap">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nome@empresa.com.br"
                            aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        >

                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="m4 7 6.8 5.1a2 2 0 0 0 2.4 0L20 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="1.8"/>
                        </svg>
                    </div>

                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <div class="field-row">
                        <label for="password">Senha</label>
                    </div>

                    <div class="input-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Digite sua senha"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                        >

                        <button
                            class="password-toggle"
                            type="button"
                            id="password-toggle"
                            aria-label="Mostrar senha"
                            aria-pressed="false"
                        >
                            <svg id="eye-open" viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true">
                                <path d="M2.8 12s3.2-5.2 9.2-5.2S21.2 12 21.2 12s-3.2 5.2-9.2 5.2S2.8 12 2.8 12Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="2.4" stroke="currentColor" stroke-width="1.8"/>
                            </svg>
                            <svg id="eye-closed" viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true" hidden>
                                <path d="m4 4 16 16M10.6 7a8.5 8.5 0 0 1 1.4-.2c6 0 9.2 5.2 9.2 5.2a14.4 14.4 0 0 1-2.3 2.8M6.7 7.4A14.7 14.7 0 0 0 2.8 12s3.2 5.2 9.2 5.2a9 9 0 0 0 3.1-.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember" for="remember_me">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span>Lembrar de mim</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot" href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    @endif
                </div>

                <button class="submit" type="submit" id="submit-button">
                    <span id="submit-label">Entrar no PontoWeb</span>
                    <svg viewBox="0 0 24 24" fill="none" width="18" height="18" aria-hidden="true">
                        <path d="M5 12h14M14 7l5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </form>

            <div class="back">
                <a class="back-link" href="{{ url('/') }}">
                    <span aria-hidden="true">←</span>
                    Voltar ao site
                </a>
            </div>

            <div class="security-note">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M12 3 5 6v5c0 4.8 2.8 8.2 7 10 4.2-1.8 7-5.2 7-10V6l-7-3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="m9.2 12 1.8 1.8 3.8-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Seus dados de acesso são transmitidos de forma segura.</span>
            </div>
        </div>
    </main>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    const loginForm = document.getElementById('login-form');
    const submitButton = document.getElementById('submit-button');
    const submitLabel = document.getElementById('submit-label');

    passwordToggle?.addEventListener('click', () => {
        const showing = passwordInput.type === 'text';

        passwordInput.type = showing ? 'password' : 'text';
        passwordToggle.setAttribute('aria-pressed', showing ? 'false' : 'true');
        passwordToggle.setAttribute('aria-label', showing ? 'Mostrar senha' : 'Ocultar senha');

        eyeOpen.hidden = !showing;
        eyeClosed.hidden = showing;

        passwordInput.focus();
    });

    loginForm?.addEventListener('submit', () => {
        submitButton.disabled = true;
        submitLabel.textContent = 'Entrando...';
    });
</script>
</body>
</html>
