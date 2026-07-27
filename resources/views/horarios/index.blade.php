<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">
            Horários
        </h2>
    </x-slot>

    <x-pw.flash />

    <x-pw.page-header
        title="Horários"
        description="Defina jornadas, intervalos e tolerâncias utilizadas pelos funcionários."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('horarios.create')">
                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 5v14M5 12h14" />
                </svg>

                Novo horário
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.table-card
        title="Jornadas cadastradas"
        :description="$items->total() . ' ' . ($items->total() === 1 ? 'horário encontrado' : 'horários encontrados')"
    >
        <x-slot name="actions">
            <x-pw.search-form
                placeholder="Buscar pela descrição do horário"
            />
        </x-slot>

        <table class="pw-table min-w-[980px]">
            <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Jornada</th>
                    <th>Intervalo</th>
                    <th>Tolerâncias</th>
                    <th>Funcionários</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-900">
                                {{ $item->descricao }}
                            </div>

                            <div class="mt-0.5 text-xs text-slate-500">
                                {{ $item->carga_horaria_label }}
                            </div>
                        </td>

                        <td>
                            <div class="font-medium text-slate-700">
                                {{ $item->entrada }} → {{ $item->saida }}
                            </div>
                        </td>

                        <td>
                            @if ($item->inicio_intervalo && $item->fim_intervalo)
                                <span class="text-slate-700">
                                    {{ $item->inicio_intervalo }}
                                    –
                                    {{ $item->fim_intervalo }}
                                </span>
                            @else
                                <span class="text-slate-400">
                                    Sem intervalo
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="space-y-1 text-xs text-slate-600">
                                <div>
                                    Entrada:
                                    <strong>{{ (int) ($item->tolerancia_entrada ?? 0) }} min</strong>
                                </div>

                                <div>
                                    Saída:
                                    <strong>{{ (int) ($item->tolerancia_saida ?? 0) }} min</strong>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $item->funcionarios_count }}
                            </span>
                        </td>

                        <td>
                            <div class="flex justify-end gap-1">
                                <x-pw.button
                                    :href="route('horarios.edit', $item)"
                                    variant="ghost"
                                    size="sm"
                                >
                                    Editar
                                </x-pw.button>

                                <form
                                    method="POST"
                                    action="{{ route('horarios.destroy', $item) }}"
                                    onsubmit="return confirm('Deseja realmente excluir o horário {{ addslashes($item->descricao) }}?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <x-pw.button
                                        type="submit"
                                        variant="ghost"
                                        size="sm"
                                        class="!text-red-600 hover:!bg-red-50"
                                    >
                                        Excluir
                                    </x-pw.button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="!p-0">
                            <x-pw.empty-state
                                title="Nenhum horário encontrado"
                                description="Cadastre jornadas para definir entrada, saída, intervalo e tolerâncias."
                            >
                                <x-pw.button :href="route('horarios.create')">
                                    Cadastrar primeiro horário
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
