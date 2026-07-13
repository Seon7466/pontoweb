<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Escalas</h2>
    </x-slot>

    <x-pw.page-header
        title="Escalas"
        description="Organize os dias de trabalho e acompanhe os funcionários vinculados."
    >
        <x-slot:actions>
            <x-pw.button :href="route('escalas.create')" variant="primary">
                Nova escala
            </x-pw.button>
        </x-slot:actions>
    </x-pw.page-header>

    <x-pw.flash />

    <x-pw.summary-grid>
        <x-pw.stat-card label="Total de escalas" :value="$resumo['total']" hint="Escalas cadastradas" tone="brand" />
        <x-pw.stat-card label="Escalas ativas" :value="$resumo['ativas']" hint="Disponíveis para vínculo" tone="success" />
        <x-pw.stat-card label="Escalas inativas" :value="$resumo['inativas']" hint="Fora de utilização" tone="neutral" />
        <x-pw.stat-card label="Vínculos" :value="$resumo['funcionarios']" hint="Funcionários em escalas" tone="warning" />
    </x-pw.summary-grid>

    <x-pw.toolbar>
        <x-pw.search-form placeholder="Buscar por descrição ou tipo..." />
        <x-slot:actions>
            <x-pw.button :href="route('escalas.create')" variant="primary" size="sm">
                Nova escala
            </x-pw.button>
        </x-slot:actions>
    </x-pw.toolbar>

    <x-pw.table-card
        title="Escalas cadastradas"
        description="Dias úteis, tipo da escala e quantidade de funcionários vinculados."
    >
        @if ($items->isEmpty())
            <x-pw.empty-state
                title="Nenhuma escala encontrada"
                description="Cadastre uma escala para organizar a jornada dos funcionários."
            >
                <x-pw.button :href="route('escalas.create')" variant="primary" size="sm">
                    Cadastrar escala
                </x-pw.button>
            </x-pw.empty-state>
        @else
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Escala</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Dias de trabalho</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Funcionários</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($items as $item)
                        @php
                            $dias = collect([
                                'Dom' => $item->domingo,
                                'Seg' => $item->segunda,
                                'Ter' => $item->terca,
                                'Qua' => $item->quarta,
                                'Qui' => $item->quinta,
                                'Sex' => $item->sexta,
                                'Sáb' => $item->sabado,
                            ])->filter()->keys();
                        @endphp

                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $item->descricao }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Código #{{ $item->id }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <x-pw.badge variant="info">{{ $item->tipo }}</x-pw.badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse ($dias as $dia)
                                        <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-600">{{ $dia }}</span>
                                    @empty
                                        <span class="text-sm text-slate-400">Nenhum dia</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="text-sm font-semibold text-slate-900">{{ $item->funcionarios_count }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <x-pw.badge :variant="$item->ativo ? 'success' : 'neutral'">
                                    {{ $item->ativo ? 'Ativa' : 'Inativa' }}
                                </x-pw.badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-pw.button :href="route('escalas.edit', $item)" variant="secondary" size="sm">Editar</x-pw.button>
                                    <form method="POST" action="{{ route('escalas.destroy', $item) }}" onsubmit="return confirm('Deseja realmente excluir esta escala?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-pw.button type="submit" variant="danger" size="sm">Excluir</x-pw.button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <x-slot:footer>
                {{ $items->links() }}
            </x-slot:footer>
        @endif
    </x-pw.table-card>
</x-app-layout>
