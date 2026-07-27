<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">
                Editar Empresa
            </h2>

            <p class="text-xs text-slate-500">
                Atualize as informações da empresa.
            </p>
        </div>
    </x-slot>

    <x-pw.page-header
        title="Editar empresa"
        :description="$empresa->razao_social"
    />

    <x-pw.card>

        <form
            method="POST"
            action="{{ route('empresas.update', $empresa) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            @include('empresas._form')

            <div class="flex justify-end gap-3">

                <x-pw.button
                    :href="route('empresas.index')"
                    variant="ghost"
                >
                    Cancelar
                </x-pw.button>

                <x-pw.button type="submit">
                    Salvar Alterações
                </x-pw.button>

            </div>

        </form>

    </x-pw.card>

</x-app-layout>
