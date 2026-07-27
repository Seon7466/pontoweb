<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Gerenciar licença
            </h2>

            <p class="text-xs text-slate-500">
                {{ $licenca->empresa->nome_fantasia }}
            </p>
        </div>
    </x-slot>

    <x-pw.page-header
        :title="$licenca->empresa->nome_fantasia"
        :description="'Plano '.$licenca->plano->nome.' · '.$licenca->codigo"
    />

    @include('master.licencas.form')
</x-app-layout>
