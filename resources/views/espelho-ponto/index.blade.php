<x-app-layout>
    <x-slot name="header">
        <x-pw.page-header
            title="Espelho de Ponto"
            subtitle="Consulta mensal da jornada e do banco de horas."
        />
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <x-pw.card>
                <form method="GET"
                      action="{{ route('espelho-ponto.index') }}"
                      class="grid gap-4 md:grid-cols-4">

                    <x-pw.select
                        name="funcionario_id"
                        label="Funcionário"
                        required
                    >
                        <option value="">Selecione</option>

                        @foreach ($funcionarios as $item)
                            <option
                                value="{{ $item->id }}"
                                @selected((int) $funcionarioId === $item->id)
                            >
                                {{ $item->nome }}
                                @if ($item->matricula)
                                    — {{ $item->matricula }}
                                @endif
                            </option>
                        @endforeach
                    </x-pw.select>

                    <x-pw.select name="mes" label="Mês">
                        @foreach (range(1, 12) as $numeroMes)
                            <option
                                value="{{ $numeroMes }}"
                                @selected($mes === $numeroMes)
                            >
                                {{ ucfirst(
                                    \Carbon\Carbon::create(null, $numeroMes)
                                        ->locale('pt_BR')
                                        ->translatedFormat('F')
                                ) }}
                            </option>
                        @endforeach
                    </x-pw.select>

                    <x-pw.input
                        type="number"
                        name="ano"
                        label="Ano"
                        min="2020"
                        max="2100"
                        :value="$ano"
                    />

                    <div class="flex items-end gap-3">
    <x-pw.button type="submit" variant="primary">
        Consultar
    </x-pw.button>

    @if ($funcionario)
        <a
            href="{{ route('espelho-ponto.pdf', [
                'funcionario_id' => $funcionario->id,
                'mes' => $mes,
                'ano' => $ano,
            ]) }}"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
        >
            Baixar PDF
        </a>
    @endif
</div>
                </form>
            </x-pw.card>

            @if ($funcionario)
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    <x-pw.stat-card
                        label="Horas previstas"
                        :value="sprintf('%02dh%02d', intdiv($totais['previstos'], 60), $totais['previstos'] % 60)"
                        tone="brand"
                    />

                    <x-pw.stat-card
                        label="Horas trabalhadas"
                        :value="sprintf('%02dh%02d', intdiv($totais['trabalhados'], 60), $totais['trabalhados'] % 60)"
                        tone="success"
                    />

                    <x-pw.stat-card
                        label="Créditos"
                        :value="sprintf('+%02dh%02d', intdiv($totais['creditos'], 60), $totais['creditos'] % 60)"
                        tone="success"
                    />

                    <x-pw.stat-card
                        label="Débitos"
                        :value="sprintf('-%02dh%02d', intdiv($totais['debitos'], 60), $totais['debitos'] % 60)"
                        tone="danger"
                    />

                    @php
                        $saldoAbsoluto = abs($totais['saldo']);
                        $saldoFormatado = sprintf(
                            '%s%02dh%02d',
                            $totais['saldo'] >= 0 ? '+' : '-',
                            intdiv($saldoAbsoluto, 60),
                            $saldoAbsoluto % 60
                        );
                    @endphp

                    <x-pw.stat-card
                        label="Saldo do mês"
                        :value="$saldoFormatado"
                        :tone="$totais['saldo'] >= 0 ? 'success' : 'danger'"
                    />
                </div>

                <x-pw.card>
                    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                {{ $funcionario->nome }}
                            </h2>

                            <p class="text-sm text-slate-500">
                                Período de {{ $inicio->format('d/m/Y') }}
                                até {{ $fim->format('d/m/Y') }}
                            </p>
                        </div>

                        <x-pw.badge :variant="$fechado ? 'warning' : 'success'">
                            {{ $fechado ? 'Período fechado' : 'Período aberto' }}
                        </x-pw.badge>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                        Data
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                        Batidas
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                        Previsto
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                        Trabalhado
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                        Saldo
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                        Observação
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($dias as $dia)
                                    @php
                                        $saldo = (int) $dia['saldo'];
                                        $saldoAbs = abs($saldo);
                                    @endphp

                                    <tr class="hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-slate-900">
                                            {{ $dia['data']->format('d/m/Y') }}
                                            <div class="text-xs text-slate-500">
                                                {{ ucfirst(
                                                    $dia['data']
                                                        ->locale('pt_BR')
                                                        ->translatedFormat('l')
                                                ) }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-slate-700">
                                            <div class="flex flex-wrap gap-2">
                                                @forelse ($dia['batidas'] as $batida)
                                                    <x-pw.badge variant="neutral">
                                                        {{ $batida->data_hora->format('H:i') }}
                                                    </x-pw.badge>
                                                @empty
                                                    <span class="text-slate-400">Sem batidas</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                            {{ sprintf(
                                                '%02dh%02d',
                                                intdiv($dia['previstos'], 60),
                                                $dia['previstos'] % 60
                                            ) }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                            {{ sprintf(
                                                '%02dh%02d',
                                                intdiv($dia['trabalhados'], 60),
                                                $dia['trabalhados'] % 60
                                            ) }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm">
                                            <x-pw.badge :variant="$saldo >= 0 ? 'success' : 'danger'">
                                                {{ sprintf(
                                                    '%s%02dh%02d',
                                                    $saldo >= 0 ? '+' : '-',
                                                    intdiv($saldoAbs, 60),
                                                    $saldoAbs % 60
                                                ) }}
                                            </x-pw.badge>
                                        </td>

                                        <td class="px-4 py-4 text-sm text-slate-500">
                                            {{ $dia['motivo'] ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-pw.card>
            @else
                <x-pw.card>
                    <x-pw.empty-state
                        title="Selecione um funcionário"
                        description="Escolha um funcionário, mês e ano para gerar o espelho de ponto."
                    />
                </x-pw.card>
            @endif

        </div>
    </div>
</x-app-layout>use App\Http\Controllers\EspelhoPontoController;

Route::get('/espelho-ponto', [EspelhoPontoController::class, 'index'])
    ->name('espelho-ponto.index');
