<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Departamentos</h2></x-slot>
    <x-ui.alert />
    <div class="py-8"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:justify-between">
            <form method="GET" class="flex gap-2">
                <input name="search" value="{{ request('search') }}" placeholder="Buscar departamento" class="rounded-md border-gray-300 shadow-sm">
                <button class="px-4 py-2 bg-gray-700 text-white rounded">Buscar</button>
            </form>
            <a href="{{ route('departamentos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded text-center">Novo Departamento</a>
        </div>
        <div class="bg-white shadow rounded p-6 overflow-x-auto">
            <table class="w-full border-collapse"><thead><tr>
                <th class="border-b p-2 text-left">Nome</th><th class="border-b p-2 text-left">Responsável</th><th class="border-b p-2 text-left">Status</th><th class="border-b p-2 text-left">Ações</th>
            </tr></thead><tbody>
            @forelse ($items as $item)
                <tr><td class="border-b p-2">{{ $item->nome }}</td><td class="border-b p-2">{{ $item->responsavel ?: '-' }}</td><td class="border-b p-2">{{ $item->ativo ? 'Ativo' : 'Inativo' }}</td>
                <td class="border-b p-2"><div class="flex gap-3"><a href="{{ route('departamentos.edit', $item) }}" class="text-blue-600">Editar</a>
                <form method="POST" action="{{ route('departamentos.destroy', $item) }}" onsubmit="return confirm('Excluir este departamento?')">@csrf @method('DELETE')<button class="text-red-600">Excluir</button></form></div></td></tr>
            @empty <tr><td colspan="4" class="p-4 text-center text-gray-500">Nenhum departamento encontrado.</td></tr> @endforelse
            </tbody></table><div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
