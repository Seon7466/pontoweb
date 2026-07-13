<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Novo horário</h2>
    </x-slot>

    <x-pw.page-header
        title="Novo horário"
        description="Configure a jornada diária, o intervalo e as tolerâncias."
    />

    <x-pw.card class="max-w-5xl">
        <form method="POST" action="{{ route('horarios.store') }}">
            @csrf

            @include('horarios.form')

            <x-pw.form-actions
                :cancel-href="route('horarios.index')"
                submit-label="Cadastrar horário"
            />
        </form>
    </x-pw.card>
</x-app-layout>
