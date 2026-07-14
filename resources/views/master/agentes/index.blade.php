<x-app-layout>
    <x-slot name="header"><div><h2 class="text-lg font-semibold text-slate-900">Agentes</h2><p class="text-xs text-slate-500">Monitoramento das instalações Windows</p></div></x-slot>

    <x-pw.page-header title="PontoWeb Agents" description="Acompanhe computadores, versões e última comunicação dos clientes." />

    <x-pw.summary-grid>
        <x-pw.stat-card label="Total" :value="$indicadores['total']" />
        <x-pw.stat-card label="Online" :value="$indicadores['online']" tone="success" />
        <x-pw.stat-card label="Offline" :value="$indicadores['offline']" tone="danger" />
        <x-pw.stat-card label="Desatualizados" :value="$indicadores['desatualizados']" tone="warning" />
    </x-pw.summary-grid>

    <div class="mt-6">
        <x-pw.table-card title="Instalações">
            <x-slot name="actions">
                <form method="GET" class="flex flex-wrap gap-2">
                    <input class="pw-field !mt-0 min-w-64" type="search" name="search" value="{{ request('search') }}" placeholder="Empresa, máquina ou versão">
                    <select class="pw-field !mt-0" name="status"><option value="">Todos</option><option value="online" @selected(request('status')==='online')>Online</option><option value="offline" @selected(request('status')==='offline')>Offline</option></select>
                    <x-pw.button type="submit" variant="secondary">Filtrar</x-pw.button>
                </form>
            </x-slot>

            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50"><tr><th class="px-5 py-3 text-left font-semibold text-slate-600">Empresa / Máquina</th><th class="px-5 py-3 text-left font-semibold text-slate-600">Versão</th><th class="px-5 py-3 text-left font-semibold text-slate-600">Sistema</th><th class="px-5 py-3 text-left font-semibold text-slate-600">Último contato</th><th class="px-5 py-3 text-left font-semibold text-slate-600">Status</th><th class="px-5 py-3"></th></tr></thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($agentes as $agente)
                        <tr class="hover:bg-slate-50"><td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $agente->empresa?->nome_fantasia ?: $agente->empresa?->razao_social }}</p><p class="text-xs text-slate-500">{{ $agente->machine_name }} · {{ $agente->local_ip ?: 'IP não informado' }}</p></td><td class="px-5 py-4 text-slate-600">{{ $agente->agent_version ?: '—' }}</td><td class="px-5 py-4 text-slate-600">{{ trim(($agente->os_name ?? '').' '.($agente->os_version ?? '')) ?: '—' }}</td><td class="px-5 py-4 text-slate-600">{{ $agente->last_seen_at?->diffForHumans() ?? 'Nunca' }}</td><td class="px-5 py-4"><x-pw.badge :variant="$agente->online ? 'success' : 'danger'">{{ $agente->online ? 'Online' : 'Offline' }}</x-pw.badge></td><td class="px-5 py-4 text-right"><x-pw.button :href="route('master.agentes.show', $agente)" variant="secondary" size="sm">Detalhes</x-pw.button></td></tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12"><x-pw.empty-state title="Nenhum agente conectado" description="O agente aparecerá aqui após o primeiro heartbeat autenticado." /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-slot name="footer">{{ $agentes->links() }}</x-slot>
        </x-pw.table-card>
    </div>
</x-app-layout>
