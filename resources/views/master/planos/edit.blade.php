<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Editar plano</h2>
            <p class="text-xs text-slate-500">Catálogo comercial da plataforma</p>
        </div>
    </x-slot>

    <x-pw.page-header
        :title="'Editar '.$plano->nome"
        description="Atualize os limites, recursos e condições comerciais do plano."
    />

    <form method="POST" action="{{ route('master.planos.update', $plano) }}">
        @csrf
        @method('PUT')
        @include('master.planos.form')
    </form>
</x-app-layout>
