@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Acompanhe os principais indicadores da sua operação')

@push('styles')
<style>
    .dashboard-grid {
        display: grid;
        gap: 1.25rem;
    }

    .welcome-banner {
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        gap: 2rem;
        padding: clamp(1.35rem, 3vw, 2rem);
        color: #fff;
        border-radius: 1.25rem;
        background:
            radial-gradient(circle at 90% 10%, rgba(255,255,255,.18), transparent 12rem),
            linear-gradient(135deg, #0b3b91 0%, #155eef 58%, #16b8c4 100%);
        box-shadow: 0 18px 45px rgba(21,94,239,.18);
    }

    .welcome-banner::after {
        content: "";
        position: absolute;
        right: -4rem;
        bottom: -6rem;
        width: 18rem;
        height: 18rem;
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 999px;
        box-shadow: 0 0 0 3rem rgba(255,255,255,.035), 0 0 0 6rem rgba(255,255,255,.025);
    }

    .welcome-copy {
        position: relative;
        z-index: 1;
        max-width: 42rem;
    }

    .welcome-copy small {
        display: block;
        margin-bottom: .45rem;
        color: rgba(255,255,255,.72);
        font-weight: 600;
    }

    .welcome-copy h2 {
        margin: 0;
        font-size: clamp(1.65rem, 3vw, 2.35rem);
        letter-spacing: -.05em;
    }

    .welcome-copy p {
        max-width: 38rem;
        margin: .75rem 0 0;
        color: rgba(255,255,255,.80);
        line-height: 1.65;
    }

    .welcome-action {
        position: relative;
        z-index: 1;
        align-self: center;
        flex: 0 0 auto;
    }

    .primary-button,
    .secondary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .5rem;
        min-height: 2.75rem;
        padding: .75rem 1rem;
        border-radius: .75rem;
        font-size: .84rem;
        font-weight: 700;
    }

    .primary-button {
        color: #155eef;
        background: #fff;
        box-shadow: 0 10px 25px rgba(0,0,0,.12);
    }

    .secondary-button {
        color: #344054;
        background: #fff;
        border: 1px solid #e4e7ec;
    }

    .metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }

    .metric-card {
        padding: 1.15rem;
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 1rem;
        box-shadow: 0 5px 16px rgba(16,24,40,.035);
    }

    .metric-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }

    .metric-icon {
        width: 2.65rem;
        height: 2.65rem;
        display: grid;
        place-items: center;
        border-radius: .8rem;
        background: #eff4ff;
        color: #155eef;
    }

    .metric-icon.success { color: #027a48; background: #ecfdf3; }
    .metric-icon.warning { color: #b54708; background: #fffaeb; }
    .metric-icon.danger { color: #b42318; background: #fef3f2; }

    .metric-icon svg { width: 1.25rem; height: 1.25rem; }

    .metric-label {
        margin-top: 1rem;
        color: #667085;
        font-size: .78rem;
        font-weight: 600;
    }

    .metric-value {
        margin: .3rem 0 0;
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -.05em;
    }

    .metric-change {
        margin-top: .55rem;
        color: #667085;
        font-size: .72rem;
    }

    .metric-change strong { color: #027a48; }
    .metric-change strong.warning { color: #b54708; }

    .two-columns {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(19rem, .75fr);
        gap: 1.25rem;
    }

    .panel {
        background: #fff;
        border: 1px solid #e4e7ec;
        border-radius: 1rem;
        box-shadow: 0 5px 16px rgba(16,24,40,.035);
    }

    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.2rem;
        border-bottom: 1px solid #f0f2f5;
    }

    .panel-title h3 {
        margin: 0;
        font-size: 1rem;
        letter-spacing: -.025em;
    }

    .panel-title p {
        margin: .25rem 0 0;
        color: #667085;
        font-size: .74rem;
    }

    .select {
        min-height: 2.35rem;
        padding: 0 .75rem;
        color: #475467;
        background: #fff;
        border: 1px solid #d0d5dd;
        border-radius: .65rem;
        outline: none;
        font-size: .76rem;
    }

    .chart-wrap {
        padding: 1.25rem 1.25rem 1rem;
    }

    .chart {
        height: 15rem;
        display: flex;
        align-items: flex-end;
        gap: .9rem;
        padding: 1rem .5rem 0;
        border-bottom: 1px solid #eaecf0;
        background-image: linear-gradient(to top, rgba(228,231,236,.55) 1px, transparent 1px);
        background-size: 100% 25%;
    }

    .bar-group {
        flex: 1;
        height: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: .28rem;
    }

    .bar {
        width: min(1.1rem, 42%);
        min-height: .5rem;
        border-radius: .45rem .45rem 0 0;
        background: linear-gradient(180deg, #155eef, #0b3b91);
    }

    .bar.secondary {
        background: linear-gradient(180deg, #4fd1c5, #16b8c4);
    }

    .chart-labels {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: .9rem;
        margin-top: .7rem;
        color: #98a2b3;
        font-size: .67rem;
        text-align: center;
    }

    .quick-actions {
        padding: .6rem;
    }

    .quick-action {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .8rem;
        border-radius: .8rem;
        transition: background .18s ease;
    }

    .quick-action:hover { background: #f8fafc; }

    .quick-action-icon {
        width: 2.4rem;
        height: 2.4rem;
        display: grid;
        place-items: center;
        color: #155eef;
        background: #eff4ff;
        border-radius: .7rem;
    }

    .quick-action-icon svg { width: 1.1rem; height: 1.1rem; }

    .quick-action strong,
    .quick-action span { display: block; }

    .quick-action strong { font-size: .8rem; }
    .quick-action span { margin-top: .18rem; color: #667085; font-size: .68rem; }

    .bottom-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 1.25rem;
    }

    .status-list,
    .activity-list {
        padding: .4rem 1.1rem .8rem;
    }

    .status-item,
    .activity-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: .85rem .1rem;
        border-bottom: 1px solid #f2f4f7;
    }

    .status-item:last-child,
    .activity-item:last-child { border-bottom: 0; }

    .status-main {
        display: flex;
        align-items: center;
        gap: .75rem;
        min-width: 0;
    }

    .status-dot {
        flex: 0 0 auto;
        width: .55rem;
        height: .55rem;
        border-radius: 999px;
        background: #12b76a;
        box-shadow: 0 0 0 .25rem #ecfdf3;
    }

    .status-dot.warning {
        background: #f79009;
        box-shadow: 0 0 0 .25rem #fffaeb;
    }

    .status-main strong,
    .status-main span { display: block; }

    .status-main strong {
        overflow: hidden;
        font-size: .78rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .status-main span {
        margin-top: .18rem;
        color: #667085;
        font-size: .68rem;
    }

    .badge {
        flex: 0 0 auto;
        padding: .35rem .55rem;
        color: #027a48;
        background: #ecfdf3;
        border-radius: 999px;
        font-size: .65rem;
        font-weight: 700;
    }

    .badge.warning {
        color: #b54708;
        background: #fffaeb;
    }

    .activity-user {
        display: flex;
        align-items: center;
        gap: .7rem;
        min-width: 0;
    }

    .activity-user .mini-avatar {
        flex: 0 0 auto;
        width: 2rem;
        height: 2rem;
        display: grid;
        place-items: center;
        color: #155eef;
        background: #eff4ff;
        border-radius: 999px;
        font-size: .68rem;
        font-weight: 800;
    }

    .activity-user strong,
    .activity-user span { display: block; }

    .activity-user strong {
        overflow: hidden;
        font-size: .78rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .activity-user span {
        margin-top: .15rem;
        color: #667085;
        font-size: .67rem;
    }

    .activity-time {
        flex: 0 0 auto;
        color: #98a2b3;
        font-size: .66rem;
    }

    @media (max-width: 1180px) {
        .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .two-columns { grid-template-columns: 1fr; }
    }

    @media (max-width: 760px) {
        .welcome-banner { flex-direction: column; }
        .welcome-action { align-self: flex-start; }
        .metrics, .bottom-grid { grid-template-columns: 1fr; }
        .chart { gap: .45rem; }
        .chart-labels { gap: .45rem; }
    }
</style>
@endpush

@section('content')
<div class="dashboard-grid">
    <section class="welcome-banner">
        <div class="welcome-copy">
            <small>{{ now()->translatedFormat('l, d \d\e F') }}</small>
            <h2>Olá, {{ explode(' ', auth()->user()->name ?? 'Usuário')[0] }}.</h2>
            <p>
                Aqui está o resumo da jornada de trabalho da sua equipe.
                Existem 3 ocorrências que precisam da sua atenção hoje.
            </p>
        </div>

        <div class="welcome-action">
            <a href="{{ url('/funcionarios/create') }}" class="primary-button">
                <span>＋</span>
                Novo funcionário
            </a>
        </div>
    </section>

    <section class="metrics" aria-label="Indicadores principais">
        <article class="metric-card">
            <div class="metric-top">
                <div class="metric-icon">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M16 20v-1.6c0-2.2-1.8-4-4-4H7c-2.2 0-4 1.8-4 4V20M9.5 10.4A3.7 3.7 0 1 0 9.5 3a3.7 3.7 0 0 0 0 7.4ZM16 11c2 0 3.5-1.6 3.5-3.5S18 4 16 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </div>
            </div>
            <div class="metric-label">Funcionários ativos</div>
            <div class="metric-value">128</div>
            <div class="metric-change"><strong>+4</strong> neste mês</div>
        </article>

        <article class="metric-card">
            <div class="metric-top">
                <div class="metric-icon success">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M7 11.5 10.2 15 17 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/></svg>
                </div>
            </div>
            <div class="metric-label">Presentes hoje</div>
            <div class="metric-value">112</div>
            <div class="metric-change"><strong>87,5%</strong> da equipe</div>
        </article>

        <article class="metric-card">
            <div class="metric-top">
                <div class="metric-icon warning">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/><path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </div>
            </div>
            <div class="metric-label">Banco de horas</div>
            <div class="metric-value">+84h</div>
            <div class="metric-change"><strong class="warning">12 funcionários</strong> com saldo alto</div>
        </article>

        <article class="metric-card">
            <div class="metric-top">
                <div class="metric-icon danger">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 8v5M12 17h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M10.3 4.8 2.9 17.5A2 2 0 0 0 4.6 20h14.8a2 2 0 0 0 1.7-2.5L13.7 4.8a2 2 0 0 0-3.4 0Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                </div>
            </div>
            <div class="metric-label">Ocorrências pendentes</div>
            <div class="metric-value">3</div>
            <div class="metric-change">Marcações que exigem revisão</div>
        </article>
    </section>

    <section class="two-columns">
        <article class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <h3>Presença da equipe</h3>
                    <p>Comparativo entre presença e ausência nos últimos dias</p>
                </div>
                <select class="select" aria-label="Período do gráfico">
                    <option>Últimos 7 dias</option>
                    <option>Últimos 30 dias</option>
                </select>
            </div>

            <div class="chart-wrap">
                <div class="chart" aria-label="Gráfico ilustrativo de presença">
                    @foreach ([82, 88, 84, 92, 89, 76, 87] as $index => $height)
                        <div class="bar-group">
                            <div class="bar" style="height: {{ $height }}%"></div>
                            <div class="bar secondary" style="height: {{ max(12, 100 - $height) }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="chart-labels">
                    <span>Seg</span><span>Ter</span><span>Qua</span><span>Qui</span><span>Sex</span><span>Sáb</span><span>Dom</span>
                </div>
            </div>
        </article>

        <article class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <h3>Atalhos rápidos</h3>
                    <p>Acesse as ações mais utilizadas</p>
                </div>
            </div>

            <div class="quick-actions">
                <a href="{{ url('/funcionarios/create') }}" class="quick-action">
                    <span class="quick-action-icon">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    </span>
                    <span>
                        <strong>Cadastrar funcionário</strong>
                        <span>Adicione uma pessoa à equipe</span>
                    </span>
                </a>

                <a href="{{ url('/escalas/create') }}" class="quick-action">
                    <span class="quick-action-icon">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2.5" stroke="currentColor" stroke-width="1.8"/><path d="M8 3v4M16 3v4M3 10h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <span>
                        <strong>Criar escala</strong>
                        <span>Organize jornadas e turnos</span>
                    </span>
                </a>

                <a href="{{ url('/relatorios') }}" class="quick-action">
                    <span class="quick-action-icon">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M5 20V10M12 20V4M19 20v-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <span>
                        <strong>Gerar relatório</strong>
                        <span>Acesse o espelho de ponto</span>
                    </span>
                </a>

                <a href="{{ url('/equipamentos') }}" class="quick-action">
                    <span class="quick-action-icon">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="5" y="3" width="14" height="18" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M9 7h6M9 11h6M9 15h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </span>
                    <span>
                        <strong>Ver equipamentos</strong>
                        <span>Confira os relógios conectados</span>
                    </span>
                </a>
            </div>
        </article>
    </section>

    <section class="bottom-grid">
        <article class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <h3>Status dos equipamentos</h3>
                    <p>Relógios integrados ao PontoWeb</p>
                </div>
                <a href="{{ url('/equipamentos') }}" class="secondary-button">Ver todos</a>
            </div>

            <div class="status-list">
                <div class="status-item">
                    <div class="status-main">
                        <span class="status-dot"></span>
                        <span>
                            <strong>Recepção principal</strong>
                            <span>Control iD • Última comunicação há 2 min</span>
                        </span>
                    </div>
                    <span class="badge">Online</span>
                </div>

                <div class="status-item">
                    <div class="status-main">
                        <span class="status-dot"></span>
                        <span>
                            <strong>Entrada do almoxarifado</strong>
                            <span>Control iD • Última comunicação há 5 min</span>
                        </span>
                    </div>
                    <span class="badge">Online</span>
                </div>

                <div class="status-item">
                    <div class="status-main">
                        <span class="status-dot warning"></span>
                        <span>
                            <strong>Unidade administrativa</strong>
                            <span>Control iD • Sem comunicação há 36 min</span>
                        </span>
                    </div>
                    <span class="badge warning">Atenção</span>
                </div>
            </div>
        </article>

        <article class="panel">
            <div class="panel-header">
                <div class="panel-title">
                    <h3>Últimas marcações</h3>
                    <p>Movimentações recentes da equipe</p>
                </div>
                <a href="{{ url('/relatorios') }}" class="secondary-button">Detalhes</a>
            </div>

            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-user">
                        <span class="mini-avatar">AS</span>
                        <span>
                            <strong>Ana Souza</strong>
                            <span>Entrada registrada</span>
                        </span>
                    </div>
                    <span class="activity-time">08:02</span>
                </div>

                <div class="activity-item">
                    <div class="activity-user">
                        <span class="mini-avatar">CM</span>
                        <span>
                            <strong>Carlos Mendes</strong>
                            <span>Entrada registrada</span>
                        </span>
                    </div>
                    <span class="activity-time">08:07</span>
                </div>

                <div class="activity-item">
                    <div class="activity-user">
                        <span class="mini-avatar">RF</span>
                        <span>
                            <strong>Rafael Ferreira</strong>
                            <span>Marcação manual pendente</span>
                        </span>
                    </div>
                    <span class="activity-time">08:13</span>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection
