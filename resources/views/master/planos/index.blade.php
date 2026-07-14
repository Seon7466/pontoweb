<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Planos</h2>
            <p class="text-xs text-slate-500">Catálogo comercial da plataforma</p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Planos comerciais"
        description="Gerencie preços, limites e recursos oferecidos aos clientes do PontoWeb."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('master.planos.create')">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Novo plano
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.summary-grid>
        <x-pw.stat-card label="Planos cadastrados" :value="$indicadores['total']" />
        <x-pw.stat-card label="Planos ativos" :value="$indicadores['ativos']" tone="success" />
        <x-pw.stat-card label="Com API" :value="$indicadores['com_api']" tone="brand" />
        <x-pw.stat-card label="Ofertas ilimitadas" :value="$indicadores['enterprise']" tone="warning" />
    </x-pw.summary-grid>

    <x-pw.toolbar>
        <form method="GET" class="grid gap-3 md:grid-cols-[minmax(260px,1fr)_180px_auto]">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Buscar por nome, descrição ou suporte" class="pw-field !mt-0 pl-9">
            </div>

            <select name="status" class="pw-field !mt-0">
                <option value="">Todos os status</option>
                <option value="ativo" @selected(request('status') === 'ativo')>Ativos</option>
                <option value="inativo" @selected(request('status') === 'inativo')>Inativos</option>
            </select>

            <div class="flex gap-2">
                <x-pw.button type="submit" variant="secondary">Filtrar</x-pw.button>
                @if (request()->hasAny(['search', 'status']))
                    <x-pw.button :href="route('master.planos.index')" variant="ghost">Limpar</x-pw.button>
                @endif
            </div>
        </form>
    </x-pw.toolbar>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        @forelse ($planos as $plano)
            <x-pw.card class="relative flex h-full flex-col overflow-hidden" :padding="false">
                <div class="h-1.5 {{ $plano->ativo ? 'bg-brand-600' : 'bg-slate-300' }}"></div>

                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-600">
                                Plano {{ $plano->ordem ?: '—' }}
                            </p>
                            <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $plano->nome }}</h3>
                        </div>

                        <x-pw.badge :variant="$plano->ativo ? 'success' : 'neutral'">
                            {{ $plano->ativo ? 'Ativo' : 'Inativo' }}
                        </x-pw.badge>
                    </div>

                    <p class="mt-3 min-h-16 text-sm leading-6 text-slate-500">
                        {{ $plano->descricao ?: 'Sem descrição comercial.' }}
                    </p>

                    <div class="mt-5">
                        <p class="text-2xl font-bold tracking-tight text-slate-900">{{ $plano->valor_label }}</p>
                        @if ($plano->valor_mensal !== null)
                            <p class="text-xs text-slate-500">por mês</p>
                        @endif
                    </div>

                    <dl class="mt-5 space-y-3 border-y border-slate-200 py-5 text-sm">
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">Funcionários</dt>
                            <dd class="font-semibold text-slate-900">{{ $plano->limite_funcionarios_label }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">Relógios</dt>
                            <dd class="font-semibold text-slate-900">{{ $plano->limite_relogios_label }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">Armazenamento</dt>
                            <dd class="font-semibold text-slate-900">{{ $plano->armazenamento_gb ? $plano->armazenamento_gb.' GB' : 'Ilimitado' }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">Suporte</dt>
                            <dd class="text-right font-semibold text-slate-900">{{ $plano->suporte }}</dd>
                        </div>
                    </dl>

                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach ([
                            'API' => $plano->api_disponivel,
                            'Mobile' => $plano->app_mobile,
                            'BI' => $plano->bi_disponivel,
                            'Geo' => $plano->geolocalizacao,
                            'Integrações' => $plano->integracoes,
                            'Marketplace' => $plano->marketplace,
                        ] as $recurso => $habilitado)
                            @if ($habilitado)
                                <x-pw.badge variant="info">{{ $recurso }}</x-pw.badge>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-auto flex items-center justify-between gap-2 pt-6">
                        <x-pw.button :href="route('master.planos.edit', $plano)" variant="secondary" size="sm">
                            Editar
                        </x-pw.button>

                        <form method="POST" action="{{ route('master.planos.destroy', $plano) }}" onsubmit="return confirm('Deseja excluir o plano {{ addslashes($plano->nome) }}?');">
                            @csrf
                            @method('DELETE')
                            <x-pw.button type="submit" variant="ghost" size="sm" class="text-red-600 hover:bg-red-50 hover:text-red-700">
                                Excluir
                            </x-pw.button>
                        </form>
                    </div>
                </div>
            </x-pw.card>
        @empty
            <div class="md:col-span-2 xl:col-span-4">
                <x-pw.card :padding="false">
                    <x-pw.empty-state
                        title="Nenhum plano encontrado"
                        description="Cadastre o primeiro plano comercial da plataforma PontoWeb."
                    >
                        <x-pw.button :href="route('master.planos.create')">Cadastrar plano</x-pw.button>
                    </x-pw.empty-state>
                </x-pw.card>
            </div>
        @endforelse
    </div>

    @if ($planos->hasPages())
        <div class="mt-6">{{ $planos->links() }}</div>
    @endif
</x-app-layout>
