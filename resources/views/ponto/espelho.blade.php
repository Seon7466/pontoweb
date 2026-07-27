<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">
            Espelho de ponto
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="overflow-hidden rounded-xl bg-white shadow-sm">

                <div class="border-b border-gray-200 p-6">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $funcionario->nome }}
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Período:
                                {{ $inicio->format('d/m/Y') }}
                                até
                                {{ $fim->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="flex gap-3">

                            <a
                                href="{{ route('ponto.index') }}"
                                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50"
                            >
                                Voltar
                            </a>

                            <a
                                href="{{ route('ponto.espelho.pdf', [
                                    'funcionario_id' => $funcionario->id,
                                    'inicio' => $inicio->format('Y-m-d'),
                                    'fim' => $fim->format('Y-m-d'),
                                ]) }}"
                                class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"
                            >
                                Baixar PDF
                            </a>

                        </div>

                    </div>

                </div>

                <div class="p-6">

                    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

                        <div class="rounded-lg border border-gray-200 p-4">
                            <span class="text-xs font-semibold uppercase text-gray-500">
                                Funcionário
                            </span>

                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $funcionario->nome }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-gray-200 p-4">
                            <span class="text-xs font-semibold uppercase text-gray-500">
                                Matrícula
                            </span>

                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $funcionario->matricula ?? 'Não informado' }}
                            </p>
                        </div>

                        <div class="rounded-lg border border-gray-200 p-4">
                            <span class="text-xs font-semibold uppercase text-gray-500">
                                Empresa
                            </span>

                            <p class="mt-1 text-sm font-medium text-gray-900">
                                {{ $funcionario->empresa->nome ?? 'Não informado' }}
                            </p>
                        </div>

                    </div>

                    <div class="overflow-x-auto">

                        <table class="min-w-full border-collapse">

                            <thead>
                                <tr class="bg-blue-600 text-white">

                                    <th class="border border-blue-500 px-3 py-2 text-left text-xs font-semibold">
                                        Data
                                    </th>

                                    <th class="border border-blue-500 px-3 py-2 text-left text-xs font-semibold">
                                        Batidas
                                    </th>

                                    <th class="border border-blue-500 px-3 py-2 text-center text-xs font-semibold">
                                        Previsto
                                    </th>

                                    <th class="border border-blue-500 px-3 py-2 text-center text-xs font-semibold">
                                        Trabalhado
                                    </th>

                                    <th class="border border-blue-500 px-3 py-2 text-center text-xs font-semibold">
                                        Crédito
                                    </th>

                                    <th class="border border-blue-500 px-3 py-2 text-center text-xs font-semibold">
                                        Débito
                                    </th>

                                    <th class="border border-blue-500 px-3 py-2 text-center text-xs font-semibold">
                                        Saldo
                                    </th>

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($dias as $dia)

                                    <tr class="odd:bg-white even:bg-gray-50">

                                        <td class="border border-gray-200 px-3 py-2 text-sm">
                                            {{ $dia['data']->format('d/m/Y') }}
                                        </td>

                                        <td class="border border-gray-200 px-3 py-2 text-sm">

                                            @forelse ($dia['batidas'] as $batida)

                                                <span class="mr-1 inline-block rounded bg-gray-200 px-2 py-1 text-xs">
                                                    {{ $batida->data_hora->format('H:i') }}
                                                </span>

                                            @empty

                                                <span class="text-gray-400">
                                                    Sem batidas
                                                </span>

                                            @endforelse

                                        </td>

                                        <td class="border border-gray-200 px-3 py-2 text-center text-sm">
                                            {{ gmdate('H:i', $dia['previstos'] * 60) }}
                                        </td>

                                        <td class="border border-gray-200 px-3 py-2 text-center text-sm">
                                            {{ gmdate('H:i', $dia['trabalhados'] * 60) }}
                                        </td>

                                        <td class="border border-gray-200 px-3 py-2 text-center text-sm text-blue-700">
                                            {{ gmdate('H:i', $dia['creditos'] * 60) }}
                                        </td>

                                        <td class="border border-gray-200 px-3 py-2 text-center text-sm text-red-600">
                                            {{ gmdate('H:i', $dia['debitos'] * 60) }}
                                        </td>

                                        <td
                                            class="border border-gray-200 px-3 py-2 text-center text-sm font-semibold
                                            {{ $dia['saldo'] < 0 ? 'text-red-600' : 'text-blue-700' }}"
                                        >
                                            {{ $dia['saldo'] < 0 ? '-' : '+' }}
                                            {{ gmdate('H:i', abs($dia['saldo']) * 60) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">

                                    <td
                                        colspan="2"
                                        class="border border-gray-200 px-3 py-3 text-sm"
                                    >
                                        Totais
                                    </td>

                                    <td class="border border-gray-200 px-3 py-3 text-center text-sm">
                                        {{ gmdate('H:i', $totais['previstos'] * 60) }}
                                    </td>

                                    <td class="border border-gray-200 px-3 py-3 text-center text-sm">
                                        {{ gmdate('H:i', $totais['trabalhados'] * 60) }}
                                    </td>

                                    <td class="border border-gray-200 px-3 py-3 text-center text-sm text-blue-700">
                                        {{ gmdate('H:i', $totais['creditos'] * 60) }}
                                    </td>

                                    <td class="border border-gray-200 px-3 py-3 text-center text-sm text-red-600">
                                        {{ gmdate('H:i', $totais['debitos'] * 60) }}
                                    </td>

                                    <td
                                        class="border border-gray-200 px-3 py-3 text-center text-sm
                                        {{ $totais['saldo'] < 0 ? 'text-red-600' : 'text-blue-700' }}"
                                    >
                                        {{ $totais['saldo'] < 0 ? '-' : '+' }}
                                        {{ gmdate('H:i', abs($totais['saldo']) * 60) }}
                                    </td>

                                </tr>
                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
