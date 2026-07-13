<x-app-layout><x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Escala</h2></x-slot><x-ui.alert />
<div class="bg-white shadow rounded p-6"><form method="POST" action="{{ route('escalas.update', $item) }}">@csrf @method('PUT') @include('escalas.form')
<div class="mt-6 flex gap-2"><x-button.primary>Atualizar</x-button.primary><x-button.secondary href="{{ route('escalas.index') }}">Voltar</x-button.secondary></div></form></div></x-app-layout>
