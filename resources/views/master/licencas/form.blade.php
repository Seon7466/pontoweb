@php $editing=isset($licenca); @endphp
@if(session('agent_token'))
    <x-pw.alert variant="warning" title="Token do PontoWeb Agent gerado">
        <p>Copie este token agora. Por segurança, ele não será exibido novamente.</p>
        <div class="mt-3 rounded-lg bg-slate-950 p-3 font-mono text-xs text-emerald-300 break-all">{{ session('agent_token') }}</div>
    </x-pw.alert>
@endif

<form method="POST" action="{{ $editing ? route('master.licencas.update',$licenca) : route('master.licencas.store') }}">
    @csrf @if($editing) @method('PUT') @endif
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <x-pw.card>
                <h3 class="text-base font-semibold text-slate-900">Contrato</h3>
                <div class="mt-5 grid gap-5 md:grid-cols-2">
                    <x-pw.select label="Empresa" name="empresa_id" required><option value="">Selecione</option>@foreach($empresas as $empresa)<option value="{{ $empresa->id }}" @selected((string)old('empresa_id',$licenca->empresa_id ?? '')===(string)$empresa->id)>{{ $empresa->nome_fantasia }} — {{ $empresa->cnpj }}</option>@endforeach</x-pw.select>
                    <x-pw.select label="Plano" name="plano_id" required><option value="">Selecione</option>@foreach($planos as $plano)<option value="{{ $plano->id }}" @selected((string)old('plano_id',$licenca->plano_id ?? '')===(string)$plano->id)>{{ $plano->nome }} — {{ $plano->valor_label }}</option>@endforeach</x-pw.select>
                    <x-pw.select label="Status" name="status" required>@foreach(['teste'=>'Em teste','ativa'=>'Ativa','suspensa'=>'Suspensa','vencida'=>'Vencida','cancelada'=>'Cancelada'] as $v=>$l)<option value="{{ $v }}" @selected(old('status',$licenca->status ?? 'teste')===$v)>{{ $l }}</option>@endforeach</x-pw.select>
                    <x-pw.select label="Ciclo de cobrança" name="ciclo_cobranca" required>@foreach(['mensal'=>'Mensal','anual'=>'Anual','personalizado'=>'Personalizado'] as $v=>$l)<option value="{{ $v }}" @selected(old('ciclo_cobranca',$licenca->ciclo_cobranca ?? 'mensal')===$v)>{{ $l }}</option>@endforeach</x-pw.select>
                    <x-pw.input label="Ativação" name="inicia_em" type="date" :value="isset($licenca)?$licenca->inicia_em?->format('Y-m-d'):today()->format('Y-m-d')" required />
                    <x-pw.input label="Vencimento" name="termina_em" type="date" :value="isset($licenca)?$licenca->termina_em?->format('Y-m-d'):today()->addMonth()->format('Y-m-d')" help="Deixe vazio para licença sem data final." />
                    <x-pw.input label="Fim do período de teste" name="periodo_teste_ate" type="date" :value="isset($licenca)?$licenca->periodo_teste_ate?->format('Y-m-d'):null" />
                    <x-pw.input label="Valor contratado" name="valor_contratado" type="number" step="0.01" min="0" :value="$licenca->valor_contratado ?? ''" help="Vazio usa o preço do plano." />
                </div>
            </x-pw.card>
            <x-pw.card>
                <h3 class="text-base font-semibold text-slate-900">Limites personalizados</h3><p class="mt-1 text-sm text-slate-500">Deixe vazio para usar os limites do plano.</p>
                <div class="mt-5 grid gap-5 md:grid-cols-2"><x-pw.input label="Funcionários" name="limite_funcionarios" type="number" min="1" :value="$licenca->limite_funcionarios ?? ''" /><x-pw.input label="Relógios" name="limite_relogios" type="number" min="1" :value="$licenca->limite_relogios ?? ''" /></div>
                <div class="mt-5"><x-pw.checkbox label="Renovação automática" name="renovacao_automatica" :checked="$licenca->renovacao_automatica ?? false" /></div>
                <div class="mt-5"><x-pw.textarea label="Observações administrativas" name="observacoes" :value="$licenca->observacoes ?? ''" rows="5" /></div>
            </x-pw.card>
        </div>
        <div class="space-y-6">
            @if($editing)
                <x-pw.card><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Código</p><p class="mt-2 break-all font-mono text-sm font-semibold text-slate-900">{{ $licenca->codigo }}</p><p class="mt-4 text-xs text-slate-500">Token atual: {{ $licenca->agent_token_prefix ? $licenca->agent_token_prefix.'••••••••' : 'não gerado' }}</p></x-pw.card>
                <x-pw.card><h3 class="font-semibold text-slate-900">Ações rápidas</h3><div class="mt-4 space-y-3"><x-pw.button type="button" variant="secondary" class="w-full" onclick="document.getElementById('renovar-licenca').submit()">Renovar licença</x-pw.button><x-pw.button type="button" variant="danger" class="w-full" onclick="if(confirm('O token anterior deixará de funcionar. Continuar?')) document.getElementById('regenerar-token').submit()">Regenerar token</x-pw.button></div></x-pw.card>
            @endif
        </div>
    </div>
    <x-pw.form-actions :cancel-href="route('master.licencas.index')" :submit-label="$editing?'Salvar alterações':'Cadastrar licença'" />
</form>
@if($editing)
<form id="renovar-licenca" method="POST" action="{{ route('master.licencas.renovar',$licenca) }}" class="hidden">@csrf<input type="hidden" name="meses" value="1"></form>
<form id="regenerar-token" method="POST" action="{{ route('master.licencas.regenerar-token',$licenca) }}" class="hidden">@csrf</form>
<x-pw.card class="mt-6"><h3 class="font-semibold text-slate-900">Histórico</h3><div class="mt-4 space-y-4">@forelse($licenca->historicos->take(10) as $historico)<div class="border-l-2 border-brand-200 pl-4"><p class="text-sm font-medium text-slate-800">{{ $historico->descricao }}</p><p class="mt-1 text-xs text-slate-500">{{ $historico->created_at->format('d/m/Y H:i') }} · {{ $historico->usuario?->name ?? 'Sistema' }}</p></div>@empty<p class="text-sm text-slate-500">Sem histórico.</p>@endforelse</div></x-pw.card>
@endif
