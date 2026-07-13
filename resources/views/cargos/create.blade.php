<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Novo cargo</h2>
    </x-slot>

    <x-pw.page-header
        title="Novo cargo"
        description="Cadastre uma função e, opcionalmente, associe-a a um departamento."
    />

    <x-pw.card class="max-w-5xl">
        <form method="POST" action="{{ route('cargos.store') }}">
            @csrf

            @include('cargos.form')

            <x-pw.form-actions
                :cancel-href="route('cargos.index')"
                submit-label="Cadastrar cargo"
            />
        </form>
    </x-pw.card>
</x-app-layout>
