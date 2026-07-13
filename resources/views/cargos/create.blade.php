<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Novo Cargo</h2></x-slot><x-ui.alert />
<div class="bg-white shadow rounded p-6"><form method="POST" action="{{ route('cargos.store') }}">@csrf @include('cargos.form', ['item' => null])
<div class="mt-6 flex gap-2"><x-button.primary>Salvar</x-button.primary><x-button.secondary href="{{ route('cargos.index') }}">Voltar</x-button.secondary></div></form></div></x-app-layout>
