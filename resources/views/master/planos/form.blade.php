@php
    $plano = $plano ?? null;
@endphp

<div class="grid gap-6 xl:grid-cols-3">
    <div class="space-y-6 xl:col-span-2">
        <x-pw.card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-slate-900">Identificação comercial</h2>
                <p class="mt-1 text-sm text-slate-500">Nome, descrição e posicionamento do plano.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <x-pw.input
                    label="Nome do plano"
                    name="nome"
                    :value="$plano?->nome"
                    placeholder="Ex.: Business"
                    required
                />

                <x-pw.input
                    label="Ordem de exibição"
                    name="ordem"
                    type="number"
                    min="0"
                    :value="$plano?->ordem ?? 0"
                    help="Planos com menor número aparecem primeiro."
                />

                <div class="md:col-span-2">
                    <x-pw.textarea
                        label="Descrição"
                        name="descricao"
                        :value="$plano?->descricao"
                        rows="4"
                        placeholder="Descreva o público e os principais benefícios do plano."
                    />
                </div>
            </div>
        </x-pw.card>

        <x-pw.card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-slate-900">Limites e preço</h2>
                <p class="mt-1 text-sm text-slate-500">Deixe limites e valor vazios para representar uma oferta sob consulta ou ilimitada.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                <x-pw.input
                    label="Funcionários"
                    name="max_funcionarios"
                    type="number"
                    min="1"
                    :value="$plano?->max_funcionarios"
                    placeholder="Ilimitado"
                />

                <x-pw.input
                    label="Relógios"
                    name="max_relogios"
                    type="number"
                    min="1"
                    :value="$plano?->max_relogios"
                    placeholder="Ilimitado"
                />

                <x-pw.input
                    label="Valor mensal (R$)"
                    name="valor_mensal"
                    type="number"
                    min="0"
                    step="0.01"
                    :value="$plano?->valor_mensal"
                    placeholder="Sob consulta"
                />

                <x-pw.input
                    label="Armazenamento (GB)"
                    name="armazenamento_gb"
                    type="number"
                    min="1"
                    :value="$plano?->armazenamento_gb"
                    placeholder="Ilimitado"
                />
            </div>
        </x-pw.card>

        <x-pw.card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-slate-900">Recursos habilitados</h2>
                <p class="mt-1 text-sm text-slate-500">Defina quais funcionalidades comerciais fazem parte desta oferta.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <x-pw.checkbox label="API" name="api_disponivel" :checked="$plano?->api_disponivel ?? false" help="Permite integrações externas pela API." />
                <x-pw.checkbox label="Aplicativo móvel" name="app_mobile" :checked="$plano?->app_mobile ?? false" help="Acesso aos recursos do aplicativo." />
                <x-pw.checkbox label="BI e análises" name="bi_disponivel" :checked="$plano?->bi_disponivel ?? false" help="Dashboards e análises avançadas." />
                <x-pw.checkbox label="Geolocalização" name="geolocalizacao" :checked="$plano?->geolocalizacao ?? false" help="Recursos de ponto com localização." />
                <x-pw.checkbox label="Integrações" name="integracoes" :checked="$plano?->integracoes ?? false" help="Integrações com ERP e folha." />
                <x-pw.checkbox label="Backup automático" name="backup_automatico" :checked="$plano?->backup_automatico ?? true" help="Rotina de backup incluída." />
                <x-pw.checkbox label="Marketplace" name="marketplace" :checked="$plano?->marketplace ?? false" help="Permite contratar módulos adicionais." />
            </div>
        </x-pw.card>
    </div>

    <div class="space-y-6">
        <x-pw.card>
            <div class="mb-5">
                <h2 class="text-base font-semibold text-slate-900">Atendimento e situação</h2>
                <p class="mt-1 text-sm text-slate-500">Configure o nível de suporte e a disponibilidade comercial.</p>
            </div>

            <div class="space-y-5">
                <x-pw.select label="Suporte" name="suporte" required>
                    @foreach (['E-mail', 'E-mail + Chat', 'Prioritário', 'Dedicado / SLA'] as $suporte)
                        <option value="{{ $suporte }}" @selected(old('suporte', $plano?->suporte ?? 'E-mail') === $suporte)>
                            {{ $suporte }}
                        </option>
                    @endforeach
                </x-pw.select>

                <x-pw.checkbox
                    label="Plano ativo"
                    name="ativo"
                    :checked="$plano?->ativo ?? true"
                    help="Planos inativos permanecem no histórico, mas não devem ser oferecidos a novos clientes."
                />
            </div>
        </x-pw.card>

        <x-pw.card class="bg-slate-900 text-white">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Prévia</p>
            <h3 class="mt-3 text-xl font-bold">{{ old('nome', $plano?->nome ?? 'Novo plano') }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-300">
                {{ old('descricao', $plano?->descricao ?? 'O resumo comercial será exibido aqui após o cadastro.') }}
            </p>
            <div class="mt-5 border-t border-slate-700 pt-5">
                <p class="text-2xl font-bold">
                    {{ $plano?->valor_label ?? 'Defina o valor' }}
                </p>
                @if ($plano?->valor_mensal !== null)
                    <p class="text-xs text-slate-400">por mês</p>
                @endif
            </div>
        </x-pw.card>
    </div>
</div>

<x-pw.form-actions
    :cancel-href="route('master.planos.index')"
    :submit-label="$plano ? 'Salvar alterações' : 'Cadastrar plano'"
/>
