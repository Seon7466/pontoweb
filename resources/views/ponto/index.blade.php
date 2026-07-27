@php
    $dataFormatada = \Carbon\Carbon::parse($data)->format('d/m/Y');

    $tipoLabels = [
        'entrada' => 'Entrada',
        'saida_intervalo' => 'Saída para intervalo',
        'retorno_intervalo' => 'Retorno do intervalo',
        'saida' => 'Saída',
    ];

    $origemVariants = [
        'web_contingencia' => 'warning',
        'api' => 'success',
        'relogio' => 'success',
        'control_id' => 'success',
        'arquivo' => 'info',
        'csv' => 'info',
        'afd' => 'info',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">
            Registro de ponto
        </h2>
    </x-slot>

    <x-pw.page-header title="Registro de ponto"
        description="Consulte marcações recebidas e registre contingências com rastreabilidade." />

    <x-pw.summary-grid>
        <x-pw.stat-card label="Batidas" :value="$indicadores['batidas']" :hint="'Data: ' . $dataFormatada" tone="brand" />

        <x-pw.stat-card label="Funcionários com batida" :value="$indicadores['funcionarios_com_batida']" hint="Colaboradores distintos" tone="success" />

        <x-pw.stat-card label="Registros manuais" :value="$indicadores['manuais']" hint="Contingências e ajustes" :tone="$indicadores['manuais'] > 0 ? 'warning' : 'neutral'" />

        <x-pw.stat-card label="Marcações com erro" :value="$indicadores['pendentes']" hint="Aguardando tratamento" :tone="$indicadores['pendentes'] > 0 ? 'danger' : 'neutral'" />
    </x-pw.summary-grid>

    <div class="space-y-6">
        <x-pw.card>
            <div class="mb-5">
                <h3 class="text-base font-semibold text-slate-900">
                    Batida web de contingência
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Utilize somente quando o relógio estiver indisponível.
                    Usuário, IP e justificativa ficam registrados.
                </p>
            </div>

            @if ($funcionarios->isEmpty())
                <x-pw.empty-state title="Nenhum funcionário ativo"
                    description="Cadastre ou ative um funcionário antes de registrar uma batida de contingência.">
                    <x-pw.button :href="route('funcionarios.create')" variant="primary" size="sm">
                        Cadastrar funcionário
                    </x-pw.button>
                </x-pw.empty-state>
            @else
                <form method="POST" action="{{ route('ponto.contingencia') }}"
                    class="grid gap-4 lg:grid-cols-[1fr,2fr,auto] lg:items-end">
                    @csrf

                    <x-pw.select label="Funcionário" name="funcionario_id" placeholder="Selecione um funcionário"
                        required>
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}" @selected((int) old('funcionario_id') === $funcionario->id)>
                                {{ $funcionario->nome }}

                                @if ($funcionario->matricula)
                                    — {{ $funcionario->matricula }}
                                @endif
                            </option>
                        @endforeach
                    </x-pw.select>

                    <x-pw.input label="Justificativa" name="observacao" :value="old('observacao')" minlength="5" maxlength="500"
                        placeholder="Informe o motivo da contingência" required />

                    <x-pw.button type="submit" variant="primary" class="w-full lg:w-auto">
                        Registrar agora
                    </x-pw.button>
                </form>
            @endif
        </x-pw.card>
        <x-pw.card>
            <div class="mb-5">
                <h3 class="text-base font-semibold text-slate-900">
                    Espelho de ponto
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Consulte ou exporte o espelho individual por período.
                </p>
            </div>

            @if ($funcionarios->isEmpty())
                <x-pw.empty-state title="Nenhum funcionário ativo"
                    description="Cadastre ou ative um funcionário para gerar o espelho." />
            @else
                <form method="GET" action="{{ route('ponto.espelho') }}"
                    class="grid gap-4 lg:grid-cols-[2fr,1fr,1fr,auto] lg:items-end">
                    <x-pw.select label="Funcionário" name="funcionario_id" placeholder="Selecione um funcionário"
                        required>
                        @foreach ($funcionarios as $funcionario)
                            <option value="{{ $funcionario->id }}">
                                {{ $funcionario->nome }}

                                @if ($funcionario->matricula)
                                    — {{ $funcionario->matricula }}
                                @endif
                            </option>
                        @endforeach
                    </x-pw.select>

                    <x-pw.input label="Data inicial" type="date" name="inicio" :value="now()->startOfMonth()->toDateString()" required />

                    <x-pw.input label="Data final" type="date" name="fim" :value="now()->endOfMonth()->toDateString()" required />

                    <div class="flex flex-col gap-2">
                        <x-pw.button type="submit" variant="secondary" class="w-full">
                            Visualizar

                        <x-pw.button type="submit" variant="primary" class="w-full"

                        </x-pw.button>
                    </div>
                </form>
            @endif
        </x-pw.card>

        <x-pw.toolbar>
            <form method="GET" action="{{ route('ponto.espelho') }}"
                class="grid w-full gap-3 md:grid-cols-2 xl:grid-cols-4">
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
                    <x-pw.button type="submit" variant="secondary" class="flex-1">
                        Filtrar
                    </x-pw.button>

                    @if (request()->hasAny(['data', 'funcionario_id', 'origem']))
                        <x-pw.button :href="route('ponto.index')" variant="ghost">
                            Limpar
                        </x-pw.button>
                    @endif
                </div>
            </form>
        </x-pw.toolbar>

        <x-pw.table-card title="Marcações" :description="'Registros encontrados para ' . $dataFormatada . '.'">
            @if ($batidas->isEmpty())
                <x-pw.empty-state title="Nenhuma batida encontrada"
                    description="Não há registros para os filtros selecionados." />
            @else
                <table class="pw-table min-w-[980px]">
                    <thead>
                        <tr>
                            <th>Data e hora</th>
                            <th>Funcionário</th>
                            <th>Tipo</th>
                            <th>Origem</th>
                            <th>Equipamento / auditoria</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($batidas as $batida)
                            @php
                                $origemLabel = $batida->origem
                                    ? ucfirst(str_replace('_', ' ', $batida->origem))
                                    : 'Não informada';

                                $origemVariant = $origemVariants[$batida->origem] ?? 'neutral';

                                $tipoLabel =
                                    $tipoLabels[$batida->tipo] ?? ucfirst(str_replace('_', ' ', $batida->tipo));
                            @endphp

                            <tr>
                                <td class="align-top">
                                    <div class="font-semibold text-slate-900">
                                        {{ $batida->data_hora->format('H:i:s') }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ $batida->data_hora->format('d/m/Y') }}
                                    </div>
                                </td>

                                <td class="align-top">
                                    <div class="font-medium text-slate-900">
                                        {{ $batida->funcionario?->nome ?? 'Funcionário não localizado' }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-400">
                                        Matrícula:
                                        {{ $batida->funcionario?->matricula ?: 'Não informada' }}
                                    </div>
                                </td>

                                <td class="align-top">
                                    <x-pw.badge variant="brand">
                                        {{ $tipoLabel }}
                                    </x-pw.badge>
                                </td>

                                <td class="align-top">
                                    <x-pw.badge :variant="$origemVariant">
                                        {{ $origemLabel }}
                                    </x-pw.badge>

                                    @if ($batida->manual)
                                        <div class="mt-2 text-xs font-medium text-amber-700">
                                            Registro manual
                                        </div>
                                    @endif
                                </td>

                                <td class="align-top text-sm text-slate-600">
                                    <div>
                                        {{ $batida->equipamento?->nome ?? 'Sem equipamento associado' }}
                                    </div>

                                    @if ($batida->equipamento?->modelo)
                                        <div class="mt-1 text-xs text-slate-400">
                                            Modelo: {{ $batida->equipamento->modelo }}
                                        </div>
                                    @endif

                                    @if ($batida->usuarioRegistro)
                                        <div class="mt-1 text-xs text-slate-400">
                                            Registrado por {{ $batida->usuarioRegistro->name }}
                                        </div>
                                    @endif

                                    @if ($batida->observacao)
                                        <div class="mt-2 max-w-sm text-xs text-slate-500"
                                            title="{{ $batida->observacao }}">
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
