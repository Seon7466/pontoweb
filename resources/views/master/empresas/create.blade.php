<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Nova Empresa
            </h2>

            <p class="text-xs text-slate-500">
                Cadastro de um novo cliente da plataforma.
            </p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Cadastrar empresa"
        description="Informe os dados da empresa, administrador e plano."
    />

    <x-pw.card>
        <form
            method="POST"
            action="{{ route('empresas.store') }}"
            class="space-y-6"
        >
            @include('empresas._form')

            <div class="flex justify-end gap-3">

                <x-pw.button
                    :href="route('empresas.index')"
                    variant="ghost"
                >
                    Cancelar
                </x-pw.button>

                <x-pw.button type="submit">
                    Salvar Empresa
                </x-pw.button>

            </div>

        </form>
    </x-pw.card>

</x-app-layout>
