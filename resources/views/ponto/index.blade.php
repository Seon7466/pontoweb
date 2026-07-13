<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Registro de ponto</h2></x-slot>
    <x-ui.alert />
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded bg-white p-5 shadow"><div class="text-sm text-gray-500">Batidas exibidas</div><div class="mt-1 text-3xl font-bold">{{ $batidas->total() }}</div></div>
            <div class="rounded bg-white p-5 shadow"><div class="text-sm text-gray-500">Funcionários ativos</div><div class="mt-1 text-3xl font-bold">{{ $funcionarios->count() }}</div></div>
            <div class="rounded bg-white p-5 shadow"><div class="text-sm text-gray-500">Marcações com erro</div><div class="mt-1 text-3xl font-bold {{ $pendentes ? 'text-red-600' : '' }}">{{ $pendentes }}</div></div>
        </div>

        <div class="rounded bg-white p-6 shadow">
            <h3 class="mb-4 text-lg font-semibold">Batida web de contingência</h3>
            <p class="mb-4 text-sm text-gray-600">Use somente quando o relógio estiver indisponível. A origem, o usuário e a justificativa ficam registrados.</p>
            <form method="POST" action="{{ route('ponto.contingencia') }}" class="grid gap-4 md:grid-cols-3">@csrf
                <select name="funcionario_id" required class="rounded-md border-gray-300"><option value="">Funcionário</option>@foreach($funcionarios as $funcionario)<option value="{{ $funcionario->id }}">{{ $funcionario->nome }}{{ $funcionario->matricula ? ' — '.$funcionario->matricula : '' }}</option>@endforeach</select>
                <input name="observacao" required minlength="5" maxlength="500" placeholder="Motivo da contingência" class="rounded-md border-gray-300">
                <button class="rounded bg-blue-600 px-4 py-2 text-white">Registrar agora</button>
            </form>
        </div>

        <div class="rounded bg-white p-6 shadow">
            <form method="GET" class="mb-5 grid gap-3 md:grid-cols-3">
                <input type="date" name="data" value="{{ $data }}" class="rounded-md border-gray-300">
                <select name="funcionario_id" class="rounded-md border-gray-300"><option value="">Todos os funcionários</option>@foreach($funcionarios as $funcionario)<option value="{{ $funcionario->id }}" @selected($funcionarioId === $funcionario->id)>{{ $funcionario->nome }}</option>@endforeach</select>
                <button class="rounded bg-gray-700 px-4 py-2 text-white">Filtrar</button>
            </form>
            <div class="overflow-x-auto"><table class="w-full"><thead><tr><th class="border-b p-2 text-left">Data/hora</th><th class="border-b p-2 text-left">Funcionário</th><th class="border-b p-2 text-left">Tipo</th><th class="border-b p-2 text-left">Origem</th><th class="border-b p-2 text-left">Equipamento</th></tr></thead><tbody>
            @forelse($batidas as $batida)<tr><td class="border-b p-2">{{ $batida->data_hora->format('d/m/Y H:i:s') }}</td><td class="border-b p-2">{{ $batida->funcionario->nome }}</td><td class="border-b p-2">{{ str_replace('_', ' ', $batida->tipo) }}</td><td class="border-b p-2">{{ str_replace('_', ' ', $batida->origem) }}</td><td class="border-b p-2">{{ $batida->equipamento?->nome ?? '-' }}</td></tr>
            @empty<tr><td colspan="5" class="p-4 text-center text-gray-500">Nenhuma batida encontrada.</td></tr>@endforelse
            </tbody></table></div><div class="mt-4">{{ $batidas->links() }}</div>
        </div>
    </div>
</x-app-layout>
