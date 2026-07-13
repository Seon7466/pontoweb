<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Departamentos</h2>
    </x-slot>

    <x-pw.flash />

    <x-pw.page-header
        title="Departamentos"
        description="Organize os setores e responsáveis da empresa."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('departamentos.create')">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Novo departamento
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-pw.stat-card
            label="Departamentos"
            :value="$items->total()"
            hint="Total cadastrado"
            tone="brand"
        />

        <x-pw.stat-card
            label="Nesta página"
            :value="$items->count()"
            hint="Registros exibidos"
            tone="neutral"
        />

        <x-pw.stat-card
            label="Ativos na página"
            :value="$items->getCollection()->where('ativo', true)->count()"
            hint="Departamentos disponíveis"
            tone="success"
        />
    </div>

    <x-pw.table-card
        title="Lista de departamentos"
        description="Use a pesquisa para localizar um setor ou responsável."
    >
        <x-slot name="actions">
            <x-pw.search-form placeholder="Buscar departamento ou responsável" />
        </x-slot>

        <table class="pw-table min-w-[720px]">
            <thead>
                <tr>
                    <th>Departamento</th>
                    <th>Responsável</th>
                    <th>Estrutura</th>
                    <th>Status</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $departamento)
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-900">{{ $departamento->nome }}</div>
                            <div class="mt-0.5 text-xs text-slate-500">ID #{{ $departamento->id }}</div>
                        </td>
                        <td>{{ $departamento->responsavel ?: 'Não definido' }}</td>
                        <td>
                            <div class="text-sm text-slate-700">
                                {{ $departamento->cargos_count }} cargo(s)
                            </div>
                            <div class="mt-0.5 text-xs text-slate-500">
                                {{ $departamento->funcionarios_count }} funcionário(s)
                            </div>
                        </td>
                        <td>
                            <x-pw.badge :variant="$departamento->ativo ? 'success' : 'neutral'">
                                {{ $departamento->ativo ? 'Ativo' : 'Inativo' }}
                            </x-pw.badge>
                        </td>
                        <td>
                            <div class="flex justify-end gap-1">
                                <x-pw.button
                                    :href="route('departamentos.edit', $departamento)"
                                    variant="ghost"
                                    size="sm"
                                >
                                    Editar
                                </x-pw.button>

                                <form
                                    method="POST"
                                    action="{{ route('departamentos.destroy', $departamento) }}"
                                    onsubmit="return confirm('Deseja realmente excluir o departamento {{ addslashes($departamento->nome) }}?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <x-pw.button type="submit" variant="ghost" size="sm" class="!text-red-600 hover:!bg-red-50">
                                        Excluir
                                    </x-pw.button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="!p-0">
                            <x-pw.empty-state
                                title="Nenhum departamento encontrado"
                                description="Cadastre o primeiro departamento para organizar cargos e funcionários."
                            >
                                <x-pw.button :href="route('departamentos.create')">
                                    Novo departamento
                                </x-pw.button>
                            </x-pw.empty-state>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($items->hasPages())
            <x-slot name="footer">
                {{ $items->links() }}
            </x-slot>
        @endif
    </x-pw.table-card>
</x-app-layout>
