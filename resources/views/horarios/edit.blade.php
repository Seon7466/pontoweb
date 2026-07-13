<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Editar horário</h2>
    </x-slot>

    <x-pw.page-header
        title="Editar horário"
        description="Atualize a jornada {{ $item->descricao }}."
    />

    <x-pw.card class="max-w-5xl">
        <form method="POST" action="{{ route('horarios.update', $item) }}">
            @csrf
            @method('PUT')

            @include('horarios.form')

            <x-pw.form-actions
                :cancel-href="route('horarios.index')"
                submit-label="Salvar alterações"
            />
        </form>
    </x-pw.card>
</x-app-layout>
