<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Nova empresa</h2>
    </x-slot>

    <x-pw.page-header
        title="Cadastrar empresa"
        description="Informe os dados da empresa que será vinculada à sua conta."
    />

    <x-pw.alert variant="info" title="Vinculação automática" class="mb-6">
        Ao concluir o cadastro, esta empresa será vinculada ao usuário autenticado.
    </x-pw.alert>

    <x-pw.card>
        <form method="POST" action="{{ route('empresas.store') }}">
            @csrf
            @include('empresas.form')

            <x-pw.form-actions
                :cancel-href="route('empresas.index')"
                submit-label="Cadastrar empresa"
            />
        </form>
    </x-pw.card>
</x-app-layout>
