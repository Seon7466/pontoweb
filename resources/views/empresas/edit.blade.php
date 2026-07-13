<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Editar empresa</h2>
    </x-slot>

    <x-pw.flash />

    <x-pw.page-header
        title="Editar empresa"
        :description="$empresa->nome_fantasia"
    />

    <x-pw.card>
        <form method="POST" action="{{ route('empresas.update', $empresa) }}">
            @csrf
            @method('PUT')
            @include('empresas.form')

            <x-pw.form-actions
                :cancel-href="route('empresas.index')"
                submit-label="Salvar alterações"
            />
        </form>
    </x-pw.card>
</x-app-layout>
