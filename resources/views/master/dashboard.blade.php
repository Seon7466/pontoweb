<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Painel Master</h2>
            <p class="text-xs text-slate-500">Administração da plataforma</p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Visão da plataforma"
        description="Indicadores globais de clientes, usuários e infraestrutura do PontoWeb."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('master.planos.index')" variant="secondary">
                Gerenciar planos
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.summary-grid>
        <x-pw.stat-card label="Empresas" :value="$indicadores['empresas'] ?? 0" />
        <x-pw.stat-card label="Funcionários" :value="$indicadores['funcionarios'] ?? 0" tone="success" />
        <x-pw.stat-card label="Equipamentos" :value="$indicadores['equipamentos'] ?? 0" tone="neutral" />
        <x-pw.stat-card label="Licenças ativas" :value="$indicadores['licencas_ativas'] ?? 0" tone="warning" />
    </x-pw.summary-grid>

    <div class="mt-6 grid gap-5 md:grid-cols-3">
        <x-pw.card class="flex flex-col">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-600">Catálogo</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Planos</h3>
                </div>
                <x-pw.badge variant="info">{{ $indicadores['planos'] ?? 0 }} cadastrados</x-pw.badge>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-500">Preços, limites de funcionários, relógios e recursos comerciais.</p>
            <div class="mt-auto pt-5">
                <x-pw.button :href="route('master.planos.index')" variant="secondary" size="sm">Abrir planos</x-pw.button>
            </div>
        </x-pw.card>

        <x-pw.card class="flex flex-col">
            <div class="flex items-start justify-between gap-3"><div><p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-600">Contratos</p><h3 class="mt-2 text-lg font-semibold text-slate-900">Licenças</h3></div><x-pw.badge variant="info">{{ $indicadores['licencas'] ?? 0 }}</x-pw.badge></div>
            <p class="mt-3 text-sm leading-6 text-slate-500">Vigência, limites contratados, suspensões e tokens do Agente.</p>
            <div class="mt-auto pt-5"><x-pw.button :href="route('master.licencas.index')" variant="secondary" size="sm">Abrir licenças</x-pw.button></div>
        </x-pw.card>

        <x-pw.card>
            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Infraestrutura</p>
            <h3 class="mt-2 text-lg font-semibold text-slate-900">Agentes</h3>
            <p class="mt-3 text-sm leading-6 text-slate-500">Monitoramento das instalações Windows e sincronizações com relógios.</p>
        </x-pw.card>
    </div>
</x-app-layout>
