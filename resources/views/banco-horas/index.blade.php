<x-app-layout>
    <x-slot name="header">
        <x-pw.page-header
            title="Banco de Horas"
            subtitle="Acompanhe o saldo acumulado dos funcionários."
        />
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <x-pw.card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Funcionário
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Saldo
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Última atualização
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($registros as $registro)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-4 py-4 text-sm font-medium text-slate-900">
                                        {{ $registro->funcionario?->nome ?? 'Funcionário não localizado' }}
                                    </td>

                                    <td class="px-4 py-4 text-sm">
                                        @php
                                            $saldo = (int) $registro->saldo;
                                            $horas = intdiv(abs($saldo), 60);
                                            $minutos = abs($saldo) % 60;
                                            $saldoFormatado = sprintf(
                                                '%s%02dh%02d',
                                                $saldo >= 0 ? '+' : '-',
                                                $horas,
                                                $minutos
                                            );
                                        @endphp

                                        <x-pw.badge :variant="$saldo >= 0 ? 'success' : 'danger'">
                                            {{ $saldoFormatado }}
                                        </x-pw.badge>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($registro->ultima_data)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-12">
                                        <x-pw.empty-state
                                            title="Nenhum saldo calculado"
                                            description="Os saldos aparecerão após o processamento das batidas dos funcionários."
                                        />
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($registros->hasPages())
                    <div class="mt-6 border-t border-slate-200 pt-4">
                        {{ $registros->links() }}
                    </div>
                @endif
            </x-pw.card>

        </div>
    </div>
</x-app-layout>
