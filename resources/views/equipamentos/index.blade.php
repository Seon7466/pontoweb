<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Equipamentos de ponto</h2></x-slot>
    <x-ui.alert />
    <div class="space-y-6">
        <div class="flex justify-end"><a href="{{ route('equipamentos.create') }}" class="rounded bg-blue-600 px-4 py-2 text-white">Novo equipamento</a></div>
        <div class="overflow-x-auto rounded bg-white p-6 shadow">
            <table class="w-full"><thead><tr><th class="border-b p-2 text-left">Nome</th><th class="border-b p-2 text-left">Fabricante / modelo</th><th class="border-b p-2 text-left">Endereço</th><th class="border-b p-2 text-left">Último NSR</th><th class="border-b p-2 text-left">Status</th><th class="border-b p-2 text-left">Ações</th></tr></thead><tbody>
            @forelse($items as $item)
                <tr>
                    <td class="border-b p-2">{{ $item->nome }}</td>
                    <td class="border-b p-2">{{ trim($item->fabricante.' '.$item->modelo) ?: '-' }}<div class="text-xs text-gray-500">Série: {{ $item->numero_serie ?: '-' }} | FW: {{ $item->versao_firmware ?: '-' }}</div></td>
                    <td class="border-b p-2">{{ $item->ip ? $item->baseUrl() : '-' }}</td>
                    <td class="border-b p-2">{{ $item->ultimo_nsr ?? '-' }}</td>
                    <td class="border-b p-2"><div>{{ $item->ultima_sincronizacao_em?->format('d/m/Y H:i') ?? 'Nunca sincronizado' }}</div><div class="max-w-xs truncate text-xs {{ $item->ultima_falha_em && (!$item->ultima_conexao_em || $item->ultima_falha_em->gt($item->ultima_conexao_em)) ? 'text-red-600' : 'text-gray-500' }}" title="{{ $item->ultima_mensagem }}">{{ $item->ultima_mensagem }}</div></td>
                    <td class="border-b p-2">
                        <div class="flex flex-wrap gap-3">
                            <a class="text-blue-600" href="{{ route('equipamentos.edit', $item) }}">Editar</a>
                            @if($item->isControlIdClass())
                                <form method="POST" action="{{ route('equipamentos.testar-conexao', $item) }}">@csrf<button class="text-indigo-600">Testar</button></form>
                                <form method="POST" action="{{ route('equipamentos.sincronizar', $item) }}">@csrf<input type="hidden" name="processar" value="1"><button class="text-emerald-600">Sincronizar</button></form>
                            @endif
                            <form method="POST" action="{{ route('equipamentos.destroy', $item) }}" onsubmit="return confirm('Excluir equipamento?')">@csrf @method('DELETE')<button class="text-red-600">Excluir</button></form>
                        </div>
                    </td>
                </tr>
            @empty<tr><td colspan="6" class="p-4 text-center text-gray-500">Nenhum equipamento cadastrado.</td></tr>@endforelse
            </tbody></table><div class="mt-4">{{ $items->links() }}</div>
        </div>
        <div class="rounded bg-white p-6 shadow">
            <h3 class="mb-2 text-lg font-semibold">Importação de contingência por CSV</h3>
            <p class="mb-4 text-sm text-gray-600">Cabeçalho: <code>codigo;data_hora;nsr</code>. Para o iDClass, prefira o botão <strong>Sincronizar</strong>.</p>
            <form method="POST" action="{{ route('equipamentos.importar') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-3">@csrf
                <select name="equipamento_id" required class="rounded-md border-gray-300"><option value="">Selecione o equipamento</option>@foreach($items as $item)<option value="{{ $item->id }}">{{ $item->nome }}</option>@endforeach</select>
                <input type="file" name="arquivo" accept=".csv,.txt" required class="rounded-md border border-gray-300 p-2">
                <button class="rounded bg-gray-700 px-4 py-2 text-white">Importar arquivo</button>
            </form>
        </div>
    </div>
</x-app-layout>
