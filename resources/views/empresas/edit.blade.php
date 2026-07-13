<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Empresa
        </h2>
    </x-slot>

    <div class="bg-white shadow rounded p-6">
        <form method="POST" action="{{ route('empresas.update', $item->id) }}">
            @csrf
            @method('PUT')

            @include('empresas.form', ['empresa' => $item])

            <div class="mt-6 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    Atualizar
                </button>

                <a href="{{ route('empresas.index') }}" class="px-4 py-2 bg-gray-300 rounded">
                    Voltar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
