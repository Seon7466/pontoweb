<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Novo departamento</h2>
    </x-slot>

    <x-pw.page-header
        title="Cadastrar departamento"
        description="Crie um setor para organizar cargos e funcionários."
    />

    <x-pw.card>
        <form method="POST" action="{{ route('departamentos.store') }}">
            @csrf
            @include('departamentos.form')

            <x-pw.form-actions
                :cancel-href="route('departamentos.index')"
                submit-label="Cadastrar departamento"
            />
        </form>
    </x-pw.card>
</x-app-layout>
