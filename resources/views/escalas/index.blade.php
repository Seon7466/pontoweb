<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">
            Escalas
        </h2>
    </x-slot>

    <x-pw.flash />

    <x-pw.page-header title="Escalas" description="Organize os dias de trabalho e acompanhe os funcionários vinculados.">
        <x-slot name="actions">
            <x-pw.button :href="route('escalas.create')" variant="primary">
                Nova escala
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.summary-grid>
        <x-pw.stat-card label="Total de escalas" :value="$resumo['total']" hint="Escalas cadastradas" tone="brand" />

        <x-pw.stat-card label="Escalas ativas" :value="$resumo['ativas']" hint="Disponíveis para vínculo" tone="success" />

        <x-pw.stat-card label="Escalas inativas" :value="$resumo['inativas']" hint="Fora de utilização" tone="neutral" />

        <x-pw.stat-card label="Vínculos" :value="$resumo['funcionarios']" hint="Funcionários em escalas" tone="warning" />
    </x-pw.summary-grid>

    <x-pw.toolbar>
        <x-pw.search-form placeholder="Buscar por descrição ou tipo..." />

        <x-slot name="actions">
            <x-pw.button :href="route('escalas.create')" variant="primary" size="sm">
                Nova escala
            </x-pw.button>
        </x-slot>
    </x-pw.toolbar>

    <x-pw.table-card title="Escalas cadastradas"
        description="Dias úteis, tipo da escala e quantidade de funcionários vinculados.">
        @if ($items->isEmpty())
            <x-pw.empty-state title="Nenhuma escala encontrada"
                description="Cadastre uma escala para organizar a jornada dos funcionários.">
                <x-pw.button :href="route('escalas.create')" variant="primary" size="sm">
                    Cadastrar escala
                </x-pw.button>
            </x-pw.empty-state>
        @else
            <table class="pw-table min-w-[920px]">
                <thead>
                    <tr>
                        <th>Escala</th>
                        <th>Tipo</th>
                        <th>Dias</th>
                        <th class="text-center">Funcionários</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <div class="font-semibold text-slate-900">
                                    {{ $item->descricao }}
                                </div>

                                <div class="mt-0.5 text-xs text-slate-500">
                                    {{ $item->dias_trabalhados_label }}
                                </div>
                            </td>

                            <td>
                                <x-pw.badge variant="info">
                                    {{ $item->tipo }}
                                </x-pw.badge>
                            </td>

                            <td>
                                <span class="text-sm text-slate-700">
                                    {{ collect($item->dias_trabalhados ?? [])->filter()->join(', ') }}
                                </span>
                            </td>>

                            <td class="text-center">
                                <span
                                    class="inline-flex min-w-8 items-center justify-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                    {{ $item->funcionarios_count }}
                                </span>
                            </td>

                            <td>
                                <x-pw.badge :variant="$item->ativo ? 'success' : 'neutral'">
                                    {{ $item->status_label }}
                                </x-pw.badge>
                            </td>

                            <td>
                                <div class="flex justify-end gap-1">
                                    <x-pw.button :href="route('escalas.edit', $item)" variant="ghost" size="sm">
                                        Editar
                                    </x-pw.button>

                                    <form method="POST" action="{{ route('escalas.destroy', $item) }}"
                                        onsubmit="return confirm('Deseja realmente excluir a escala {{ addslashes($item->descricao) }}?')">
                                        @csrf
                                        @method('DELETE')

                                        <x-pw.button type="submit" variant="ghost" size="sm"
                                            class="!text-red-600 hover:!bg-red-50">
                                            Excluir
                                        </x-pw.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <x-slot name="footer">
                {{ $items->links() }}
            </x-slot>
        @endif
    </x-pw.table-card>
</x-app-layout>
