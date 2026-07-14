<x-app-layout>
    <x-slot name="header"><div><h2 class="text-lg font-semibold text-slate-900">Licenças</h2><p class="text-xs text-slate-500">Contratos e acesso à plataforma</p></div></x-slot>

    <x-pw.page-header title="Licenças comerciais" description="Vincule empresas aos planos, acompanhe vencimentos e controle o acesso do PontoWeb Agent.">
        <x-slot name="actions"><x-pw.button :href="route('master.licencas.create')">Nova licença</x-pw.button></x-slot>
    </x-pw.page-header>

    <x-pw.summary-grid>
        <x-pw.stat-card label="Licenças" :value="$indicadores['total']" />
        <x-pw.stat-card label="Ativas e em teste" :value="$indicadores['ativas']" tone="success" />
        <x-pw.stat-card label="Vencem em 15 dias" :value="$indicadores['vencendo']" tone="warning" />
        <x-pw.stat-card label="Bloqueadas" :value="$indicadores['bloqueadas']" tone="danger" />
    </x-pw.summary-grid>

    <x-pw.toolbar>
        <form method="GET" class="grid gap-3 lg:grid-cols-[minmax(260px,1fr)_180px_220px_auto]">
            <input class="pw-field !mt-0" type="search" name="search" value="{{ request('search') }}" placeholder="Empresa, CNPJ ou código">
            <select class="pw-field !mt-0" name="status">
                <option value="">Todos os status</option>
                @foreach(['teste'=>'Em teste','ativa'=>'Ativa','suspensa'=>'Suspensa','vencida'=>'Vencida','cancelada'=>'Cancelada'] as $value=>$label)
                    <option value="{{ $value }}" @selected(request('status')===$value)>{{ $label }}</option>
                @endforeach
            </select>
            <select class="pw-field !mt-0" name="plano_id"><option value="">Todos os planos</option>@foreach($planos as $plano)<option value="{{ $plano->id }}" @selected((string)request('plano_id')===(string)$plano->id)>{{ $plano->nome }}</option>@endforeach</select>
            <div class="flex gap-2"><x-pw.button type="submit" variant="secondary">Filtrar</x-pw.button>@if(request()->hasAny(['search','status','plano_id']))<x-pw.button :href="route('master.licencas.index')" variant="ghost">Limpar</x-pw.button>@endif</div>
        </form>
    </x-pw.toolbar>

    <x-pw.table-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50"><tr>@foreach(['Empresa','Plano','Código','Vigência','Valor','Status','Ações'] as $h)<th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $h }}</th>@endforeach</tr></thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($licencas as $licenca)
                        @php $variant=['ativa'=>'success','teste'=>'info','suspensa'=>'warning','vencida'=>'danger','cancelada'=>'neutral'][$licenca->status_efetivo] ?? 'neutral'; @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $licenca->empresa->nome_fantasia }}</p><p class="text-xs text-slate-500">{{ $licenca->empresa->cnpj }}</p></td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $licenca->plano->nome }}</td>
                            <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $licenca->codigo }}</td>
                            <td class="px-5 py-4 text-slate-600"><p>{{ $licenca->inicia_em->format('d/m/Y') }}</p><p class="text-xs">até {{ $licenca->termina_em?->format('d/m/Y') ?? 'sem vencimento' }}</p></td>
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $licenca->valor_label }}</td>
                            <td class="px-5 py-4"><x-pw.badge :variant="$variant">{{ $licenca->status_label }}</x-pw.badge></td>
                            <td class="px-5 py-4"><x-pw.button :href="route('master.licencas.edit',$licenca)" variant="secondary" size="sm">Gerenciar</x-pw.button></td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-pw.empty-state title="Nenhuma licença encontrada" description="Cadastre a primeira licença comercial."><x-pw.button :href="route('master.licencas.create')">Nova licença</x-pw.button></x-pw.empty-state></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-pw.table-card>
    @if($licencas->hasPages())<div class="mt-6">{{ $licencas->links() }}</div>@endif
</x-app-layout>
