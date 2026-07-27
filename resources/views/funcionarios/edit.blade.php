<x-app-layout>
    <x-slot name="header"><h2 class="text-lg font-semibold text-slate-900">Editar funcionário</h2></x-slot>

    <x-pw.page-header title="Editar funcionário" :description="'Atualize os dados de ' . $funcionario->nome . '.'" />

    <x-pw.card>
        <form method="POST" action="{{ route('funcionarios.update', $funcionario) }}">
            @csrf
            @method('PUT')
            @include('funcionarios.form')
            <x-pw.form-actions :cancel-href="route('funcionarios.index')" submit-label="Salvar alterações" />
        </form>
    </x-pw.card>
</x-app-layout>
