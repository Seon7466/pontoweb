<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Empresas</h2>
            <p class="text-xs text-slate-500">Clientes cadastrados na plataforma</p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Empresas"
        description="Gerencie clientes, licenças, usuários e equipamentos do PontoWeb."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('empresas.create')">
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 5v14M5 12h14" />
                </svg>

                Nova empresa
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    @if (session('agent_token'))
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <p class="font-semibold text-amber-900">
                Token do Agent
            </p>

            <p class="mt-1 text-sm text-amber-800">
                Copie e guarde este token. Ele não será exibido novamente.
            </p>

            <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                <input
                    id="agent-token"
                    type="text"
                    readonly
                    value="{{ session('agent_token') }}"
                    class="pw-field !mt-0 flex-1 font-mono text-xs"
                >

                <x-pw.button
                    type="button"
                    variant="secondary"
                    onclick="navigator.clipboard.writeText(document.getElementById('agent-token').value)"
                >
                    Copiar token
                </x-pw.button>
            </div>
        </div>
    @endif

    <x-pw.summary-grid>
        <x-pw.stat-card
            label="Empresas cadastradas"
            :value="$indicadores['total']"
        />

        <x-pw.stat-card
            label="Empresas ativas"
            :value="$indicadores['ativas']"
            tone="success"
        />

        <x-pw.stat-card
            label="Empresas inativas"
            :value="$indicadores['inativas']"
            tone="warning"
        />

        <x-pw.stat-card
            label="Licenças válidas"
            :value="$indicadores['licencas_ativas']"
            tone="danger"
        />
    </x-pw.summary-grid>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($empresas as $empresa)
            <x-pw.card class="relative flex h-full flex-col overflow-hidden" :padding="false">
                <div class="h-1.5 {{ $empresa->ativo ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>

                <div class="flex flex-1 flex-col p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-600">
                                Cliente #{{ $empresa->id }}
                            </p>

                            <h3 class="mt-2 truncate text-xl font-bold text-slate-900">
                                {{ $empresa->nome_fantasia }}
                            </h3>

                            <p class="mt-1 truncate text-sm text-slate-500">
                                {{ $empresa->razao_social }}
                            </p>
                        </div>

                        <x-pw.badge :variant="$empresa->ativo ? 'success' : 'neutral'">
                            {{ $empresa->ativo ? 'Ativa' : 'Inativa' }}
                        </x-pw.badge>
                    </div>

                    <div class="mt-5 rounded-xl bg-slate-50 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Plano
                                </p>

                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $empresa->licenca?->plano?->nome ?? 'Sem plano' }}
                                </p>
                            </div>

                            @if ($empresa->licenca)
                                <x-pw.badge
                                    :variant="$empresa->licenca->valida ? 'success' : 'warning'"
                                >
                                    {{ $empresa->licenca->status_label }}
                                </x-pw.badge>
                            @else
                                <x-pw.badge variant="warning">
                                    Sem licença
                                </x-pw.badge>
                            @endif
                        </div>

                        @if ($empresa->licenca?->termina_em)
                            <p class="mt-3 text-xs text-slate-500">
                                Vencimento:
                                <span class="font-medium text-slate-700">
                                    {{ $empresa->licenca->termina_em->format('d/m/Y') }}
                                </span>
                            </p>
                        @endif
                    </div>

                    <dl class="mt-5 grid grid-cols-3 gap-3 border-y border-slate-200 py-5 text-center">
                        <div>
                            <dt class="text-xs text-slate-500">Usuários</dt>
                            <dd class="mt-1 text-lg font-bold text-slate-900">
                                {{ $empresa->usuarios->count() }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs text-slate-500">Funcionários</dt>
                            <dd class="mt-1 text-lg font-bold text-slate-900">
                                {{ $empresa->funcionarios->count() }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs text-slate-500">Relógios</dt>
                            <dd class="mt-1 text-lg font-bold text-slate-900">
                                {{ $empresa->equipamentos->count() }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-5 space-y-2 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">CNPJ</span>
                            <span class="text-right font-medium text-slate-900">
                                {{ $empresa->cnpj }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">E-mail</span>
                            <span class="truncate text-right font-medium text-slate-900">
                                {{ $empresa->email ?: 'Não informado' }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Localização</span>
                            <span class="text-right font-medium text-slate-900">
                                @if ($empresa->cidade || $empresa->estado)
                                    {{ $empresa->cidade ?: '—' }}{{ $empresa->estado ? '/'.$empresa->estado : '' }}
                                @else
                                    Não informada
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="mt-auto flex items-center justify-between gap-2 pt-6">
                        <x-pw.button
                            :href="route('empresas.edit', $empresa)"
                            variant="secondary"
                            size="sm"
                        >
                            Editar
                        </x-pw.button>

                        <form
                            method="POST"
                            action="{{ route('empresas.destroy', $empresa) }}"
                            onsubmit="return confirm('Deseja excluir a empresa {{ addslashes($empresa->nome_fantasia) }}?');"
                        >
                            @csrf
                            @method('DELETE')

                            <x-pw.button
                                type="submit"
                                variant="ghost"
                                size="sm"
                                class="text-red-600 hover:bg-red-50 hover:text-red-700"
                            >
                                Excluir
                            </x-pw.button>
                        </form>
                    </div>
                </div>
            </x-pw.card>
        @empty
            <div class="md:col-span-2 xl:col-span-3">
                <x-pw.card :padding="false">
                    <x-pw.empty-state
                        title="Nenhuma empresa cadastrada"
                        description="Cadastre o primeiro cliente da plataforma PontoWeb."
                    >
                        <x-pw.button :href="route('empresas.create')">
                            Cadastrar empresa
                        </x-pw.button>
                    </x-pw.empty-state>
                </x-pw.card>
            </div>
        @endforelse
    </div>

    @if ($empresas->hasPages())
        <div class="mt-6">
            {{ $empresas->links() }}
        </div>
    @endif
</x-app-layout>
