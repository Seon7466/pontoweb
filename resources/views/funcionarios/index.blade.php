<x-app-layout>
    <x-slot name="header"><h2 class="text-lg font-semibold text-slate-900">Funcionários</h2></x-slot>

    <x-pw.page-header
        title="Funcionários"
        description="Gerencie os colaboradores, vínculos e configurações de jornada."
    >
        <x-slot:actions>
            <x-pw.button :href="route('funcionarios.create')" variant="primary">Novo funcionário</x-pw.button>
        </x-slot:actions>
    </x-pw.page-header>

    <x-pw.flash />

    <x-pw.summary-grid>
        <x-pw.stat-card label="Total" :value="$resumo['total']" hint="Funcionários cadastrados" tone="brand" />
        <x-pw.stat-card label="Ativos" :value="$resumo['ativos']" hint="Em atividade" tone="success" />
        <x-pw.stat-card label="Inativos" :value="$resumo['inativos']" hint="Fora de atividade" tone="neutral" />
        <x-pw.stat-card label="Sem jornada" :value="$resumo['sem_jornada']" hint="Sem horário e escala" tone="warning" />
    </x-pw.summary-grid>

    <x-pw.toolbar>
        <form method="GET" class="flex w-full flex-col gap-2 md:flex-row">
            <div class="relative min-w-0 flex-1 md:max-w-xl">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" /><path d="m21 21-4.3-4.3" />
                </svg>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Nome, CPF, matrícula, cargo ou departamento..." class="pw-field !mt-0 w-full pl-9">
            </div>

            <select name="status" class="pw-field !mt-0 md:w-44">
                <option value="">Todos os status</option>
                <option value="1" @selected(request('status') === '1')>Ativos</option>
                <option value="0" @selected(request('status') === '0')>Inativos</option>
            </select>

            <div class="flex gap-2">
                <x-pw.button type="submit" variant="secondary">Filtrar</x-pw.button>
                @if (request()->filled('search') || request()->filled('status'))
                    <x-pw.button :href="route('funcionarios.index')" variant="ghost">Limpar</x-pw.button>
                @endif
            </div>
        </form>

        <x-slot:actions>
            <x-pw.button :href="route('funcionarios.create')" variant="primary" size="sm">Novo funcionário</x-pw.button>
        </x-slot:actions>
    </x-pw.toolbar>

    <x-pw.table-card title="Equipe cadastrada" description="Dados de vínculo e jornada dos funcionários.">
        @if ($items->isEmpty())
            <x-pw.empty-state title="Nenhum funcionário encontrado" description="Cadastre o primeiro funcionário ou altere os filtros utilizados.">
                <x-pw.button :href="route('funcionarios.create')" variant="primary" size="sm">Cadastrar funcionário</x-pw.button>
            </x-pw.empty-state>
        @else
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Funcionário</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Vínculo</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Jornada</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Contato</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach ($items as $funcionario)
                        <tr class="transition hover:bg-blue-50/40">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-900">{{ $funcionario->nome }}</p>
                                <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-500">
                                    <span>CPF: {{ $funcionario->cpf }}</span>
                                    <span>Matrícula: {{ $funcionario->matricula ?: '—' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <p class="font-medium text-slate-700">{{ $funcionario->departamento?->nome ?? 'Sem departamento' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $funcionario->cargo?->nome ?? 'Sem cargo' }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <p class="font-medium text-slate-700">{{ $funcionario->horario?->descricao ?? 'Sem horário' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $funcionario->escala?->descricao ?? 'Sem escala' }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-600">
                                <p>{{ $funcionario->email ?: '—' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $funcionario->telefone ?: 'Sem telefone' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <x-pw.badge :variant="$funcionario->status ? 'success' : 'neutral'">
                                    {{ $funcionario->status ? 'Ativo' : 'Inativo' }}
                                </x-pw.badge>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-pw.button :href="route('funcionarios.edit', $funcionario)" variant="secondary" size="sm">Editar</x-pw.button>
                                    <form method="POST" action="{{ route('funcionarios.destroy', $funcionario) }}" onsubmit="return confirm('Deseja realmente excluir este funcionário?')">
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

            <x-slot:footer>{{ $items->links() }}</x-slot:footer>
        @endif
    </x-pw.table-card>
</x-app-layout>
