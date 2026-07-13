<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Editar departamento</h2>
    </x-slot>

    <x-pw.flash />

    <x-pw.page-header
        title="Editar departamento"
        :description="$item->nome"
    />

    <x-pw.card>
        <form method="POST" action="{{ route('departamentos.update', $item) }}">
            @csrf
            @method('PUT')
            @include('departamentos.form')

            <x-pw.form-actions
                :cancel-href="route('departamentos.index')"
                submit-label="Salvar alterações"
            />
        </form>
    </x-pw.card>
</x-app-layout>
