<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Novo equipamento</h2>
    </x-slot>

    <x-pw.page-header
        title="Novo equipamento"
        description="Cadastre um relógio de ponto ou agente de integração."
    />

    <x-pw.flash />

    <form method="POST" action="{{ route('equipamentos.store') }}">
        @csrf

        @include('equipamentos.form')

        <x-pw.form-actions
            :cancel-href="route('equipamentos.index')"
            submit-label="Cadastrar equipamento"
        />
    </form>
</x-app-layout>
