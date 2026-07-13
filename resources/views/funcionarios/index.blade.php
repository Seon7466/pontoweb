<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Funcionários
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 flex justify-end">
                <a href="{{ route('funcionarios.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded">
                    Novo Funcionário
                </a>
            </div>

            <div class="bg-white shadow rounded p-6">
                <table class="w-full border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b p-2 text-left">ID</th>
                            <th class="border-b p-2 text-left">Nome</th>
                            <th class="border-b p-2 text-left">Empresa</th>
                            <th class="border-b p-2 text-left">CPF</th>
                            <th class="border-b p-2 text-left">Matrícula</th>
                            <th class="border-b p-2 text-left">Status</th>
                            <th class="border-b p-2 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $funcionario)
                            <tr>
                                <td class="border-b p-2">{{ $funcionario->id }}</td>
                                <td class="border-b p-2">{{ $funcionario->nome }}</td>
                                <td class="border-b p-2">
                                    {{ $funcionario->empresa->nome_fantasia ?? $funcionario->empresa->razao_social ?? '-' }}
                                </td>
                                <td class="border-b p-2">{{ $funcionario->cpf }}</td>
                                <td class="border-b p-2">{{ $funcionario->matricula ?? '-' }}</td>
                                <td class="border-b p-2">
                                    {{ $funcionario->status ? 'Ativo' : 'Inativo' }}
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('funcionarios.edit', $funcionario) }}"
                                       class="text-blue-600">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-center text-gray-500">
                                    Nenhum funcionário encontrado.
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
