<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Editar equipamento</h2>
    </x-slot>

    <x-pw.page-header
        title="Editar equipamento"
        :description="'Atualize a configuração de '.$equipamento->nome.'.'"
    />

    <x-pw.flash />

    <form method="POST" action="{{ route('equipamentos.update', $equipamento) }}">
        @csrf
        @method('PUT')

        @include('equipamentos.form')

        <x-pw.form-actions
            :cancel-href="route('equipamentos.index')"
            submit-label="Salvar alterações"
        />
    </form>
</x-app-layout>
