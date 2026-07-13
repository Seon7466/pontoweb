<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Registro de ponto</h2>
    </x-slot>

    <x-pw.page-header
        title="Registro de ponto"
        description="Consulte marcações recebidas e registre contingências com rastreabilidade."
    />

    <x-pw.flash />

    <x-pw.summary-grid>
        <x-pw.stat-card label="Batidas" :value="$indicadores['batidas']" :hint="'Data: '.\Carbon\Carbon::parse($data)->format('d/m/Y')" tone="brand" />
        <x-pw.stat-card label="Funcionários com batida" :value="$indicadores['funcionarios_com_batida']" hint="Colaboradores distintos" tone="success" />
        <x-pw.stat-card label="Registros manuais" :value="$indicadores['manuais']" hint="Contingências e ajustes" :tone="$indicadores['manuais'] ? 'warning' : 'neutral'" />
        <x-pw.stat-card label="Marcações com erro" :value="$indicadores['pendentes']" hint="Aguardando tratamento" :tone="$indicadores['pendentes'] ? 'danger' : 'neutral'" />
    </x-pw.summary-grid>

    <div class="space-y-6">
        <x-pw.card>
            <div class="mb-5">
                <h3 class="text-base font-semibold text-slate-900">Batida web de contingência</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Utilize somente quando o relógio estiver indisponível. Usuário, IP e justificativa ficam registrados.
                </p>
            </div>

            <form method="POST" action="{{ route('ponto.contingencia') }}" class="grid gap-4 lg:grid-cols-[1fr,2fr,auto] lg:items-end">
                @csrf

                <x-pw.select label="Funcionário" name="funcionario_id" required>
                    @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" @selected(old('funcionario_id') == $funcionario->id)>
                            {{ $funcionario->nome }}{{ $funcionario->matricula ? ' — '.$funcionario->matricula : '' }}
                        </option>
                    @endforeach
                </x-pw.select>

                <x-pw.input
                    label="Justificativa"
                    name="observacao"
                    :value="old('observacao')"
                    minlength="5"
                    maxlength="500"
                    placeholder="Informe o motivo da contingência"
                    required
                />

                <x-pw.button type="submit" variant="primary" class="w-full lg:w-auto">
                    Registrar agora
                </x-pw.button>
            </form>
        </x-pw.card>

        <x-pw.toolbar>
            <form method="GET" class="grid w-full gap-3 md:grid-cols-2 xl:grid-cols-4">
                <x-pw.input label="Data" name="data" type="date" :value="$data" />

                <x-pw.select label="Funcionário" name="funcionario_id" placeholder="Todos os funcionários">
                    @foreach ($funcionarios as $funcionario)
                        <option value="{{ $funcionario->id }}" @selected($funcionarioId === $funcionario->id)>
                            {{ $funcionario->nome }}
                        </option>
                    @endforeach
                </x-pw.select>

                <x-pw.select label="Origem" name="origem" placeholder="Todas as origens">
                    @foreach ($origens as $origemDisponivel)
                        <option value="{{ $origemDisponivel }}" @selected($origem === $origemDisponivel)>
                            {{ ucfirst(str_replace('_', ' ', $origemDisponivel)) }}
                        </option>
                    @endforeach
                </x-pw.select>

                <div class="flex items-end gap-2">
                    <x-pw.button type="submit" variant="secondary" class="flex-1">Filtrar</x-pw.button>
                    @if (request()->hasAny(['data', 'funcionario_id', 'origem']))
                        <x-pw.button :href="route('ponto.index')" variant="ghost">Limpar</x-pw.button>
                    @endif
                </div>
            </form>
        </x-pw.toolbar>

        <x-pw.table-card
            title="Marcações"
            :description="'Registros encontrados para '.\Carbon\Carbon::parse($data)->format('d/m/Y').'.'"
        >
            @if ($batidas->isEmpty())
                <x-pw.empty-state
                    title="Nenhuma batida encontrada"
                    description="Não há registros para os filtros selecionados."
                />
            @else
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Data e hora</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Funcionário</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tipo</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Origem</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Equipamento / auditoria</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($batidas as $batida)
                            @php
                                $tipoLabels = [
                                    'entrada' => 'Entrada',
                                    'saida_intervalo' => 'Saída para intervalo',
                                    'retorno_intervalo' => 'Retorno do intervalo',
                                    'saida' => 'Saída',
                                ];
                                $origemVariant = match ($batida->origem) {
                                    'web_contingencia' => 'warning',
                                    'api', 'relogio', 'control_id' => 'success',
                                    'arquivo', 'csv', 'afd' => 'info',
                                    default => 'neutral',
                                };
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 align-top">
                                    <div class="font-semibold text-slate-900">{{ $batida->data_hora->format('H:i:s') }}</div>
                                    <div class="mt-1 text-xs text-slate-400">{{ $batida->data_hora->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="font-medium text-slate-900">{{ $batida->funcionario?->nome ?? 'Funcionário não localizado' }}</div>
                                    <div class="mt-1 text-xs text-slate-400">Matrícula: {{ $batida->funcionario?->matricula ?: '—' }}</div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <x-pw.badge variant="brand">
                                        {{ $tipoLabels[$batida->tipo] ?? ucfirst(str_replace('_', ' ', $batida->tipo)) }}
                                    </x-pw.badge>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <x-pw.badge :variant="$origemVariant">
                                        {{ ucfirst(str_replace('_', ' ', $batida->origem)) }}
                                    </x-pw.badge>
                                    @if ($batida->manual)
                                        <div class="mt-2 text-xs font-medium text-amber-700">Registro manual</div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top text-sm text-slate-600">
                                    <div>{{ $batida->equipamento?->nome ?? 'Sem equipamento associado' }}</div>
                                    @if ($batida->usuarioRegistro)
                                        <div class="mt-1 text-xs text-slate-400">Registrado por {{ $batida->usuarioRegistro->name }}</div>
                                    @endif
                                    @if ($batida->observacao)
                                        <div class="mt-1 max-w-sm text-xs text-slate-500" title="{{ $batida->observacao }}">
                                            {{ \Illuminate\Support\Str::limit($batida->observacao, 100) }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($batidas->hasPages())
                <x-slot name="footer">
                    {{ $batidas->links() }}
                </x-slot>
            @endif
        </x-pw.table-card>
    </div>
</x-app-layout>
