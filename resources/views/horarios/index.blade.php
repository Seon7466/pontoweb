<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Horários</h2>
    </x-slot>

    <x-pw.page-header
        title="Horários"
        description="Defina jornadas, intervalos e tolerâncias utilizadas pelos funcionários."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('horarios.create')">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Novo horário
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.flash />

    <x-pw.table-card
        title="Jornadas cadastradas"
        description="{{ $items->total() }} {{ $items->total() === 1 ? 'horário encontrado' : 'horários encontrados' }}"
    >
        <x-slot name="actions">
            <x-pw.search-form placeholder="Buscar pela descrição do horário" />
        </x-slot>

        <table class="pw-table">
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
                    @php
                        $entrada = $item->entrada ? substr($item->entrada, 0, 5) : '—';
                        $saida = $item->saida ? substr($item->saida, 0, 5) : '—';
                        $inicioIntervalo = $item->inicio_intervalo ? substr($item->inicio_intervalo, 0, 5) : null;
                        $fimIntervalo = $item->fim_intervalo ? substr($item->fim_intervalo, 0, 5) : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="font-semibold text-slate-900">{{ $item->descricao }}</div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2 font-medium text-slate-700">
                                <span>{{ $entrada }}</span>
                                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                                <span>{{ $saida }}</span>
                            </div>
                        </td>
                        <td>
                            @if ($inicioIntervalo && $fimIntervalo)
                                <span class="text-slate-700">{{ $inicioIntervalo }}–{{ $fimIntervalo }}</span>
                            @else
                                <span class="text-slate-400">Sem intervalo</span>
                            @endif
                        </td>
                        <td>
                            <div class="space-y-1 text-xs text-slate-600">
                                <div>Entrada: <strong>{{ (int) ($item->tolerancia_entrada ?? 0) }} min</strong></div>
                                <div>Saída: <strong>{{ (int) ($item->tolerancia_saida ?? 0) }} min</strong></div>
                            </div>
                        </td>
                        <td>
                            <span class="inline-flex min-w-8 items-center justify-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                                {{ $item->funcionarios_count }}
                            </span>
                        </td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <x-pw.button :href="route('horarios.edit', $item)" variant="ghost" size="sm">
                                    Editar
                                </x-pw.button>

                                <form method="POST" action="{{ route('horarios.destroy', $item) }}" onsubmit="return confirm('Deseja realmente excluir este horário?')">
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
                                title="Nenhum horário cadastrado"
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
