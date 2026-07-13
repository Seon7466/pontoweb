<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Empresas
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex justify-end">
                <a href="{{ route('empresas.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    Nova Empresa
                </a>
            </div>

            <div class="bg-white shadow rounded p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b p-2 text-left">ID</th>
                            <th class="border-b p-2 text-left">Razão Social</th>
                            <th class="border-b p-2 text-left">Nome Fantasia</th>
                            <th class="border-b p-2 text-left">CNPJ</th>
                            <th class="border-b p-2 text-left">Status</th>
                            <th class="border-b p-2 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $empresa)
                            <tr>
                                <td class="border-b p-2">{{ $empresa->id }}</td>
                                <td class="border-b p-2">{{ $empresa->razao_social }}</td>
                                <td class="border-b p-2">{{ $empresa->nome_fantasia }}</td>
                                <td class="border-b p-2">{{ $empresa->cnpj }}</td>
                                <td class="border-b p-2">
                                    {{ $empresa->ativo ? 'Ativo' : 'Inativo' }}
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('empresas.edit', $empresa) }}"
                                       class="text-blue-600">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">
                                    Nenhuma empresa encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $items->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
