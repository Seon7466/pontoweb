<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Dashboard</h2>
            <p class="text-xs text-slate-500">Visão geral da operação da empresa</p>
        </div>
    </x-slot>

    {{-- Destaque principal: somente apresentação, sem novas variáveis ou regras. --}}
    <section class="relative overflow-hidden rounded-2xl bg-slate-950 px-5 py-6 text-white shadow-lg sm:px-7 sm:py-7 lg:px-8">
        <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-blue-600 opacity-30 blur-3xl"></div>
        <div class="absolute -bottom-24 left-1/3 h-56 w-56 rounded-full bg-cyan-500 opacity-20 blur-3xl"></div>
        <div class="absolute inset-y-0 right-0 hidden w-1/3 bg-gradient-to-l from-blue-600/20 to-transparent lg:block"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold text-blue-100 backdrop-blur-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                    Operação em acompanhamento
                </div>

                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">
                    Olá, {{ auth()->user()->name ?? 'usuário' }}.
                </h1>

                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300 sm:text-base">
                    Acompanhe os principais indicadores, as últimas marcações e a situação dos equipamentos do PontoWeb.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('ponto.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20 focus:outline-none focus:ring-4 focus:ring-white/10"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                    </svg>
                    Ver batidas
                </a>

                <a
                    href="{{ route('funcionarios.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-400/30"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    Novo funcionário
                </a>
            </div>
        </div>
    </section>

    {{-- Indicadores: mesmas variáveis já utilizadas na versão auditada. --}}
    <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ route('funcionarios.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Funcionários</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $totalFuncionarios }}</p>
                    <p class="mt-1 text-xs text-slate-500">Cadastrados na empresa</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700 ring-1 ring-blue-100 transition group-hover:bg-blue-600 group-hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full w-3/4 rounded-full bg-blue-600"></div>
            </div>
        </a>

        <a href="{{ route('departamentos.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Departamentos</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $totalDepartamentos }}</p>
                    <p class="mt-1 text-xs text-slate-500">Estrutura organizacional</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-700 ring-1 ring-violet-100 transition group-hover:bg-violet-600 group-hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 9h.01M9 13h.01M9 17h.01M15 9h.01M15 13h.01M15 17h.01" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full w-1/2 rounded-full bg-violet-600"></div>
            </div>
        </a>

        <a href="{{ route('equipamentos.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Equipamentos</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $totalEquipamentos }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $equipamentosAtivos }} ativos</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $equipamentosSemComunicacao > 0 ? 'bg-amber-50 text-amber-700 ring-amber-100 group-hover:bg-amber-500' : 'bg-emerald-50 text-emerald-700 ring-emerald-100 group-hover:bg-emerald-600' }} ring-1 transition group-hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="4" y="3" width="16" height="18" rx="2" />
                        <path stroke-linecap="round" d="M8 7h8M8 11h8M9 17h6" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs font-medium {{ $equipamentosSemComunicacao > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                <span class="h-2 w-2 rounded-full {{ $equipamentosSemComunicacao > 0 ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                {{ $equipamentosSemComunicacao > 0 ? $equipamentosSemComunicacao.' sem comunicação' : 'Operação normal' }}
            </div>
        </a>

        <a href="{{ route('ponto.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Batidas hoje</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-950">{{ $batidasHoje }}</p>
                    <p class="mt-1 text-xs text-slate-500">Marcações recebidas</p>
                </div>
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100 transition group-hover:bg-emerald-600 group-hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2" />
                    </svg>
                </span>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs font-medium text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Atualização em tempo real
            </div>
        </a>
    </section>

    @if ($equipamentosSemComunicacao > 0)
        <section class="mt-6 overflow-hidden rounded-2xl border border-amber-200 bg-amber-50 shadow-sm">
            <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 4.6 2.8 17.5A2 2 0 0 0 4.5 20h15a2 2 0 0 0 1.7-2.5L13.7 4.6a2 2 0 0 0-3.4 0Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-amber-950">Atenção necessária</h2>
                        <p class="mt-1 text-sm leading-6 text-amber-800">
                            {{ $equipamentosSemComunicacao }} equipamento(s) ativo(s) estão sem comunicação há mais de 10 minutos.
                        </p>
                    </div>
                </div>

                <a href="{{ route('equipamentos.index') }}" class="inline-flex shrink-0 items-center justify-center rounded-lg bg-amber-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-amber-700">
                    Verificar equipamentos
                </a>
            </div>
        </section>
    @endif

    <section class="mt-6 grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                            <h2 class="font-bold text-slate-950">Últimas marcações</h2>
                        </div>
                        <p class="mt-1 text-sm text-slate-500">Registros mais recentes recebidos pelo sistema</p>
                    </div>

                    <a href="{{ route('ponto.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 transition hover:text-blue-800">
                        Ver todas
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                        </svg>
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
                                        <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-600">
                                                    {{ mb_strtoupper(mb_substr($batida->funcionario?->nome ?? 'N', 0, 1)) }}
                                                </span>
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">
                                                        {{ $batida->funcionario?->nome ?? 'Não identificado' }}
                                                    </p>
                                                    <p class="text-xs text-slate-500">
                                                        {{ $batida->funcionario?->matricula ? 'Matrícula '.$batida->funcionario->matricula : 'Sem matrícula' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-700">
                                            {{ optional($batida->data_hora)->format('d/m/Y H:i:s') ?? '—' }}
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                            {{ $batida->equipamento?->nome ?? ucfirst((string) ($batida->origem ?: 'Sistema')) }}
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4">
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
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                    <div>
                        <h2 class="font-bold text-slate-950">Equipamentos</h2>
                        <p class="mt-0.5 text-sm text-slate-500">Situação das conexões</p>
                    </div>

                    <a href="{{ route('equipamentos.index') }}" class="text-sm font-semibold text-blue-600 transition hover:text-blue-800">
                        Gerenciar
                    </a>
                </div>

                <div class="space-y-3 p-4">
                    @forelse ($equipamentos as $equipamento)
                        @php
                            $status = $equipamento->status_dashboard;
                            $statusLabel = match ($status) {
                                'online' => 'Online',
                                'inactive' => 'Inativo',
                                default => 'Offline',
                            };
                            $statusClass = match ($status) {
                                'online' => 'bg-emerald-500 ring-emerald-100',
                                'inactive' => 'bg-slate-400 ring-slate-100',
                                default => 'bg-red-500 ring-red-100',
                            };
                            $statusTextClass = match ($status) {
                                'online' => 'text-emerald-700 bg-emerald-50',
                                'inactive' => 'text-slate-600 bg-slate-100',
                                default => 'text-red-700 bg-red-50',
                            };
                        @endphp

                        <div class="group rounded-xl border border-slate-200 p-3.5 transition hover:border-blue-200 hover:bg-blue-50/30">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2.5 w-2.5 shrink-0 rounded-full ring-4 {{ $statusClass }}"></span>
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $equipamento->nome }}</p>
                                    </div>
                                    <p class="mt-1.5 truncate pl-5 text-xs text-slate-500">
                                        {{ $equipamento->modelo ?: $equipamento->fabricante ?: 'Modelo não informado' }}
                                    </p>
                                </div>

                                <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusTextClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <p class="mt-3 border-t border-slate-100 pt-2.5 text-right text-xs text-slate-400">
                                {{ $equipamento->ultima_conexao_em?->diffForHumans() ?? 'Nunca conectado' }}
                            </p>
                        </div>
                    @empty
                        <x-pw.empty-state
                            title="Nenhum equipamento"
                            description="Cadastre o primeiro relógio para acompanhar seu status."
                        >
                            <x-pw.button :href="route('equipamentos.create')" size="sm">Cadastrar</x-pw.button>
                        </x-pw.empty-state>
                    @endforelse
                </div>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="font-bold text-slate-950">Ações rápidas</h2>
                    <p class="mt-0.5 text-sm text-slate-500">Atalhos para tarefas frequentes</p>
                </div>

                <div class="grid gap-2 p-4">
                    <a href="{{ route('funcionarios.create') }}" class="group flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                        <span class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                            </span>
                            Cadastrar funcionário
                        </span>
                        <span aria-hidden="true">→</span>
                    </a>

                    <a href="{{ route('equipamentos.create') }}" class="group flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                        <span class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-700 transition group-hover:bg-blue-600 group-hover:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="5" y="3" width="14" height="18" rx="2" /><path stroke-linecap="round" d="M9 7h6M9 11h6" /></svg>
                            </span>
                            Cadastrar equipamento
                        </span>
                        <span aria-hidden="true">→</span>
                    </a>

                    <a href="{{ route('departamentos.create') }}" class="group flex items-center justify-between rounded-xl border border-slate-200 px-3.5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                        <span class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-700 transition group-hover:bg-violet-600 group-hover:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" stroke-linejoin="round" d="M4 21h16M6 21V8l6-4 6 4v13M9 12h.01M12 12h.01M15 12h.01" /></svg>
                            </span>
                            Cadastrar departamento
                        </span>
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
