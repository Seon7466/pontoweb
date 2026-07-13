<x-app-layout>
    <x-slot name="header"><h2 class="text-lg font-semibold text-slate-900">Editar escala</h2></x-slot>

    <x-pw.page-header title="Editar escala" :description="'Atualize a escala ' . $item->descricao . '.'" />
    <x-pw.flash />

    <x-pw.card>
        <form method="POST" action="{{ route('escalas.update', $item) }}">
            @csrf
            @method('PUT')
            @include('escalas.form')
            <x-pw.form-actions :cancel-href="route('escalas.index')" submit-label="Salvar alterações" />
        </form>
    </x-pw.card>
</x-app-layout>
