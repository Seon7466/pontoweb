<x-app-layout>
    <x-slot name="header"><h2 class="text-lg font-semibold text-slate-900">Novo funcionário</h2></x-slot>

    <x-pw.page-header title="Novo funcionário" description="Cadastre o colaborador e configure seu vínculo e jornada." />
    <x-pw.flash />

    <x-pw.card>
        <form method="POST" action="{{ route('funcionarios.store') }}">
            @csrf
            @include('funcionarios.form', ['funcionario' => null])
            <x-pw.form-actions :cancel-href="route('funcionarios.index')" submit-label="Cadastrar funcionário" />
        </form>
    </x-pw.card>
</x-app-layout>
