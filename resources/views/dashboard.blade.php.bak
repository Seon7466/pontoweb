<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Dashboard</h2>
            <p class="text-xs text-slate-500">Visão geral da operação da empresa</p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Painel PontoWeb"
        :description="'Olá, '.(auth()->user()->name ?? 'usuário').'. Acompanhe os indicadores e as últimas movimentações.'"
    >
        <x-slot name="actions">
            <div class="flex flex-wrap gap-2">
                <x-pw.button :href="route('ponto.index')" variant="secondary">
                    Ver batidas
                </x-pw.button>

                <x-pw.button :href="route('funcionarios.create')">
                    Novo funcionário
                </x-pw.button>
            </div>
        </x-slot>
    </x-pw.page-header>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-pw.stat-card
            label="Funcionários"
            :value="$totalFuncionarios"
            hint="Cadastrados na empresa"
            tone="brand"
        >
            <x-slot name="icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </x-slot>
        </x-pw.stat-card>

        <x-pw.stat-card
            label="Departamentos"
            :value="$totalDepartamentos"
            hint="Estrutura organizacional"
            tone="neutral"
        >
            <x-slot name="icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01"/>
                </svg>
            </x-slot>
        </x-pw.stat-card>

        <x-pw.stat-card
            label="Equipamentos"
            :value="$totalEquipamentos"
            :hint="$equipamentosAtivos.' ativos'"
            :tone="$equipamentosSemComunicacao > 0 ? 'warning' : 'success'"
        >
            <x-slot name="icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="4" y="3" width="16" height="18" rx="2"/>
                    <path stroke-linecap="round" d="M8 7h8M8 11h8M9 17h6"/>
                </svg>
            </x-slot>
        </x-pw.stat-card>

        <x-pw.stat-card
            label="Batidas hoje"
            :value="$batidasHoje"
            hint="Marcações recebidas"
            tone="success"
        >
            <x-slot name="icon">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                </svg>
            </x-slot>
        </x-pw.stat-card>
    </div>

    @if ($equipamentosSemComunicacao > 0)
        <div class="mt-6">
            <x-pw.alert variant="warning">
                {{ $equipamentosSemComunicacao }} equipamento(s) ativo(s) estão sem comunicação há mais de 10 minutos.
                <a href="{{ route('equipamentos.index') }}" class="ml-1 font-semibold underline">Verificar equipamentos</a>
            </x-pw.alert>
        </div>
    @endif

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <x-pw.card :padding="false" class="overflow-hidden">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <h3 class="font-semibold text-slate-900">Últimas marcações</h3>
                        <p class="mt-0.5 text-sm text-slate-500">Registros mais recentes recebidos pelo sistema</p>
                    </div>

                    <a href="{{ route('ponto.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Ver todas
                    </a>
                </div>

                @if ($ultimasBatidas->isEmpty())
                    <div class="p-6">
                        <x-pw.empty-state
                            title="Nenhuma marcação recebida"
                            description="As batidas do relógio ou os registros de contingência aparecerão aqui."
                        />
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 sm:px-6">Funcionário</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Data e hora</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Origem</th>
                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($ultimasBatidas as $batida)
                                    <tr class="transition hover:bg-blue-50/50">
                                        <td class="whitespace-nowrap px-5 py-3.5 sm:px-6">
                                            <p class="text-sm font-medium text-slate-900">
                                                {{ $batida->funcionario?->nome ?? 'Não identificado' }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ $batida->funcionario?->matricula ? 'Matrícula '.$batida->funcionario->matricula : 'Sem matrícula' }}
                                            </p>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-700">
                                            {{ optional($batida->data_hora)->format('d/m/Y H:i:s') ?? '—' }}
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-600">
                                            {{ $batida->equipamento?->nome ?? ucfirst((string) ($batida->origem ?: 'Sistema')) }}
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-3.5">
                                            <x-pw.badge variant="brand">
                                                {{ ucfirst(str_replace('_', ' ', (string) ($batida->tipo ?: 'marcação'))) }}
                                            </x-pw.badge>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-pw.card>
        </div>

        <div class="space-y-6">
            <x-pw.card>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-slate-900">Equipamentos</h3>
                        <p class="mt-0.5 text-sm text-slate-500">Situação das últimas conexões</p>
                    </div>

                    <a href="{{ route('equipamentos.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                        Gerenciar
                    </a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($equipamentos as $equipamento)
                        @php
                            $status = $equipamento->status_dashboard;
                            $statusLabel = match ($status) {
                                'online' => 'Online',
                                'inactive' => 'Inativo',
                                default => 'Offline',
                            };
                            $statusClass = match ($status) {
                                'online' => 'bg-emerald-500',
                                'inactive' => 'bg-slate-400',
                                default => 'bg-red-500',
                            };
                        @endphp

                        <div class="flex items-center justify-between gap-4 rounded-lg border border-slate-200 px-3.5 py-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $statusClass }}"></span>
                                    <p class="truncate text-sm font-medium text-slate-900">{{ $equipamento->nome }}</p>
                                </div>
                                <p class="mt-1 truncate pl-[18px] text-xs text-slate-500">
                                    {{ $equipamento->modelo ?: $equipamento->fabricante ?: 'Modelo não informado' }}
                                </p>
                            </div>

                            <div class="shrink-0 text-right">
                                <p class="text-xs font-semibold text-slate-600">{{ $statusLabel }}</p>
                                <p class="mt-1 text-[11px] text-slate-400">
                                    {{ $equipamento->ultima_conexao_em?->diffForHumans() ?? 'Nunca conectado' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <x-pw.empty-state
                            title="Nenhum equipamento"
                            description="Cadastre o primeiro relógio para acompanhar seu status."
                        >
                            <x-slot name="actions">
                                <x-pw.button :href="route('equipamentos.create')" size="sm">Cadastrar</x-pw.button>
                            </x-slot>
                        </x-pw.empty-state>
                    @endforelse
                </div>
            </x-pw.card>

            <x-pw.card>
                <h3 class="font-semibold text-slate-900">Ações rápidas</h3>
                <div class="mt-4 grid gap-2">
                    <x-pw.button :href="route('funcionarios.create')" variant="secondary" class="justify-start">
                        Cadastrar funcionário
                    </x-pw.button>
                    <x-pw.button :href="route('equipamentos.create')" variant="secondary" class="justify-start">
                        Cadastrar equipamento
                    </x-pw.button>
                    <x-pw.button :href="route('departamentos.create')" variant="secondary" class="justify-start">
                        Cadastrar departamento
                    </x-pw.button>
                </div>
            </x-pw.card>
        </div>
    </div>
</x-app-layout>
