<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Novo plano</h2>
            <p class="text-xs text-slate-500">Catálogo comercial da plataforma</p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Cadastrar plano"
        description="Defina preço, limites e recursos disponíveis para uma nova oferta do PontoWeb."
    />

    <form method="POST" action="{{ route('master.planos.store') }}">
        @csrf
        @include('master.planos.form')
    </form>
</x-app-layout>
