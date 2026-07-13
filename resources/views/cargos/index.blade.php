<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Cargos</h2>
    </x-slot>

    <x-pw.page-header
        title="Cargos"
        description="Organize funções, departamentos e códigos CBO da sua empresa."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('cargos.create')">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Novo cargo
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.flash />

    <x-pw.table-card
        title="Cargos cadastrados"
        description="{{ $items->total() }} {{ $items->total() === 1 ? 'cargo encontrado' : 'cargos encontrados' }}"
    >
        <x-slot name="actions">
            <x-pw.search-form placeholder="Buscar por cargo, departamento ou CBO" />
        </x-slot>

        <table class="pw-table">
            <thead>
                <tr>
                    <th>Cargo</th>
                    <th>Departamento</th>
                    <th>CBO</th>
                    <th>Funcionários</th>
                    <th>Status</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-900">{{ $item->nome }}</div>
                            @if ($item->descricao)
                                <div class="mt-1 max-w-md truncate text-xs text-slate-500" title="{{ $item->descricao }}">
                                    {{ $item->descricao }}
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($item->departamento)
                                <span class="font-medium text-slate-700">{{ $item->departamento->nome }}</span>
                            @else
                                <span class="text-slate-400">Sem departamento</span>
                            @endif
                        </td>
                        <td>
                            <span class="font-mono text-sm text-slate-600">{{ $item->cbo ?: '—' }}</span>
                        </td>
                        <td>
                            <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $item->funcionarios_count }}
                            </span>
                        </td>
                        <td>
                            <x-pw.badge :variant="$item->ativo ? 'success' : 'neutral'">
                                {{ $item->ativo ? 'Ativo' : 'Inativo' }}
                            </x-pw.badge>
                        </td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <x-pw.button :href="route('cargos.edit', $item)" variant="ghost" size="sm">
                                    Editar
                                </x-pw.button>

                                <form method="POST" action="{{ route('cargos.destroy', $item) }}" onsubmit="return confirm('Deseja realmente excluir este cargo?')">
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
                        <td colspan="6">
                            <x-pw.empty-state
                                title="Nenhum cargo cadastrado"
                                description="Cadastre os cargos da empresa para vinculá-los aos funcionários."
                            >
                                <x-pw.button :href="route('cargos.create')">
                                    Cadastrar primeiro cargo
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
