<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-slate-900">Equipamentos</h2>
    </x-slot>

    <x-pw.page-header
        title="Equipamentos"
        description="Gerencie relógios de ponto, agentes e integrações da empresa."
    >
        <x-slot name="actions">
            <x-pw.button :href="route('equipamentos.create')" variant="primary">
                Novo equipamento
            </x-pw.button>
        </x-slot>
    </x-pw.page-header>

    <x-pw.flash />

    <x-pw.summary-grid>
        <x-pw.stat-card label="Equipamentos" :value="$indicadores['total']" hint="Total cadastrado" tone="brand" />
        <x-pw.stat-card label="Ativos" :value="$indicadores['ativos']" hint="Disponíveis para operação" tone="success" />
        <x-pw.stat-card label="Online" :value="$indicadores['online']" hint="Contato nos últimos 10 minutos" tone="success" />
        <x-pw.stat-card label="Offline" :value="$indicadores['offline']" hint="Sem comunicação recente" :tone="$indicadores['offline'] ? 'danger' : 'neutral'" />
    </x-pw.summary-grid>

    <x-pw.toolbar>
        <x-pw.search-form
            placeholder="Buscar por nome, modelo, IP, série ou fabricante..."
            :value="$search"
        />
    </x-pw.toolbar>

    <div class="space-y-6">
        <x-pw.table-card
            title="Relógios e agentes"
            description="A situação online considera o último contato realizado nos últimos 10 minutos."
        >
            @if ($items->isEmpty())
                <x-pw.empty-state
                    title="Nenhum equipamento encontrado"
                    description="Cadastre um relógio de ponto ou altere os termos da pesquisa."
                >
                    <x-pw.button :href="route('equipamentos.create')" variant="primary">
                        Cadastrar equipamento
                    </x-pw.button>
                </x-pw.empty-state>
            @else
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Equipamento</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Conexão</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Sincronização</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Situação</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($items as $item)
                            @php
                                $online = $item->ativo
                                    && $item->ultima_conexao_em
                                    && $item->ultima_conexao_em->gte(now()->subMinutes(10));
                                $falhaAtual = $item->ultima_falha_em
                                    && (! $item->ultima_conexao_em || $item->ultima_falha_em->gt($item->ultima_conexao_em));
                            @endphp

                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 align-top">
                                    <div class="font-semibold text-slate-900">{{ $item->nome }}</div>
                                    <div class="mt-1 text-sm text-slate-500">
                                        {{ trim($item->fabricante.' '.$item->modelo) ?: 'Modelo não informado' }}
                                    </div>
                                    <div class="mt-1 text-xs text-slate-400">
                                        Série: {{ $item->numero_serie ?: '—' }} · Firmware: {{ $item->versao_firmware ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top text-sm text-slate-600">
                                    <div>{{ $item->ip ? $item->baseUrl() : 'Endereço não informado' }}</div>
                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ strtoupper($item->tipo_integracao ?: 'arquivo') }} · {{ $item->timezone ?: 'Sem fuso' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top text-sm text-slate-600">
                                    <div>{{ $item->ultima_sincronizacao_em?->format('d/m/Y H:i') ?? 'Nunca sincronizado' }}</div>
                                    <div class="mt-1 text-xs text-slate-400">Último NSR: {{ $item->ultimo_nsr ?? '—' }}</div>
                                    @if ($item->ultima_mensagem)
                                        <div class="mt-1 max-w-xs truncate text-xs {{ $falhaAtual ? 'text-red-600' : 'text-slate-400' }}" title="{{ $item->ultima_mensagem }}">
                                            {{ $item->ultima_mensagem }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top">
                                    @if (! $item->ativo)
                                        <x-pw.badge variant="neutral">Inativo</x-pw.badge>
                                    @elseif ($online)
                                        <x-pw.badge variant="success">Online</x-pw.badge>
                                    @else
                                        <x-pw.badge variant="danger">Offline</x-pw.badge>
                                    @endif

                                    <div class="mt-2 text-xs text-slate-400">
                                        Último contato: {{ $item->ultima_conexao_em?->diffForHumans() ?? 'nunca' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        @if ($item->isControlIdClass())
                                            <form method="POST" action="{{ route('equipamentos.testar-conexao', $item) }}">
                                                @csrf
                                                <x-pw.button type="submit" variant="secondary" size="sm">Testar</x-pw.button>
                                            </form>

                                            <form method="POST" action="{{ route('equipamentos.sincronizar', $item) }}">
                                                @csrf
                                                <input type="hidden" name="processar" value="1">
                                                <x-pw.button type="submit" variant="success" size="sm">Sincronizar</x-pw.button>
                                            </form>
                                        @endif

                                        <x-pw.button :href="route('equipamentos.edit', $item)" variant="ghost" size="sm">
                                            Editar
                                        </x-pw.button>

                                        <form method="POST" action="{{ route('equipamentos.destroy', $item) }}" onsubmit="return confirm('Deseja realmente excluir este equipamento?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-pw.button type="submit" variant="danger" size="sm">Excluir</x-pw.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if ($items->hasPages())
                <x-slot name="footer">
                    {{ $items->links() }}
                </x-slot>
            @endif
        </x-pw.table-card>

        <x-pw.card>
            <div class="grid gap-6 lg:grid-cols-[1fr,2fr]">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Importação de contingência</h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Importe arquivos CSV ou TXT quando a sincronização direta não estiver disponível.
                    </p>
                    <p class="mt-3 text-xs text-slate-400">
                        Cabeçalho esperado: <code class="rounded bg-slate-100 px-1.5 py-0.5">codigo;data_hora;nsr</code>
                    </p>
                </div>

                <form method="POST" action="{{ route('equipamentos.importar') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-3">
                    @csrf

                    <x-pw.select label="Equipamento" name="equipamento_id" required>
                        @foreach ($equipamentosImportacao as $equipamento)
                            <option value="{{ $equipamento->id }}" @selected(old('equipamento_id') == $equipamento->id)>
                                {{ $equipamento->nome }}{{ $equipamento->modelo ? ' — '.$equipamento->modelo : '' }}
                            </option>
                        @endforeach
                    </x-pw.select>

                    <div>
                        <label for="arquivo" class="pw-label">Arquivo <span class="text-red-500">*</span></label>
                        <input id="arquivo" type="file" name="arquivo" accept=".csv,.txt" required class="pw-field bg-white file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700">
                        @error('arquivo')<p class="pw-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-end">
                        <x-pw.button type="submit" variant="secondary" class="w-full">
                            Importar arquivo
                        </x-pw.button>
                    </div>
                </form>
            </div>
        </x-pw.card>
    </div>
</x-app-layout>
