<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Painel PontoWeb
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="mb-6 flex flex-wrap gap-3">
            <a href="{{ route('funcionarios.index') }}"
               class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Funcionários
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="text-sm font-medium text-gray-500">Empresas</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $totalEmpresas ?? 0 }}
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="text-sm font-medium text-gray-500">Funcionários</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $totalFuncionarios ?? 0 }}
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="text-sm font-medium text-gray-500">Batidas Hoje</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $batidasHoje ?? 0 }}
                </p>
            </div>

            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="text-sm font-medium text-gray-500">Pendências</h3>
                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $totalPendencias ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
