<x-app-layout>

<x-slot name="header">
    <x-pw.page-header
        title="Fechamento Mensal"
        subtitle="Controle de períodos fechados."
    />
</x-slot>

<div class="py-8">

<div class="mx-auto max-w-7xl px-4">

<x-pw.card>

<form method="POST"
      action="{{ route('fechamentos.store') }}"
      class="grid grid-cols-3 gap-4 mb-8">

@csrf

<input
    type="number"
    name="mes"
    min="1"
    max="12"
    value="{{ date('n') }}"
    class="rounded-lg border"
/>

<input
    type="number"
    name="ano"
    value="{{ date('Y') }}"
    class="rounded-lg border"
/>

<button class="rounded-lg bg-indigo-600 text-white px-4">
Fechar Período
</button>

</form>

<table class="min-w-full">

<thead>

<tr>

<th>Mês</th>

<th>Ano</th>

<th>Fechado em</th>

</tr>

</thead>

<tbody>

@foreach($fechamentos as $item)

<tr>

<td>{{ $item->mes }}</td>

<td>{{ $item->ano }}</td>

<td>{{ $item->fechado_em->format('d/m/Y H:i') }}</td>

</tr>

@endforeach

</tbody>

</table>

{{ $fechamentos->links() }}

</x-pw.card>

</div>

</div>

</x-app-layout>