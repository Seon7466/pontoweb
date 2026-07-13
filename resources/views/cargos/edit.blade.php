<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Editar cargo</h2>
    </x-slot>

    <x-pw.page-header
        title="Editar cargo"
        description="Atualize as informações de {{ $item->nome }}."
    />

    <x-pw.card class="max-w-5xl">
        <form method="POST" action="{{ route('cargos.update', $item) }}">
            @csrf
            @method('PUT')

            @include('cargos.form')

            <x-pw.form-actions
                :cancel-href="route('cargos.index')"
                submit-label="Salvar alterações"
            />
        </form>
    </x-pw.card>
</x-app-layout>
