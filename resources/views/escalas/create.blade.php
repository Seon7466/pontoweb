<x-app-layout>
    <x-slot name="header"><h2 class="text-lg font-semibold text-slate-900">Nova escala</h2></x-slot>

    <x-pw.page-header title="Nova escala" description="Configure os dias e o tipo de jornada." />

    <x-pw.card>
        <form method="POST" action="{{ route('escalas.store') }}">
            @csrf
            @include('escalas.form')
            <x-pw.form-actions :cancel-href="route('escalas.index')" submit-label="Cadastrar escala" />
        </form>
    </x-pw.card>
</x-app-layout>
