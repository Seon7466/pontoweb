<div class="space-y-6">
    <x-pw.card>
        <div class="mb-5">
            <h3 class="text-base font-semibold text-slate-900">Identificação</h3>
            <p class="mt-1 text-sm text-slate-500">Dados usados para identificar o equipamento na plataforma.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.input
                label="Nome do equipamento"
                name="nome"
                :value="$equipamento->nome ?? ''"
                placeholder="Ex.: Relógio da portaria"
                required
            />

            <x-pw.select
                label="Fabricante"
                name="fabricante"
                :value="$equipamento->fabricante ?? ''"
                required
            >
                <option value="Control iD" @selected(old('fabricante', $equipamento->fabricante ?? '') === 'Control iD')>Control iD</option>
                <option value="Outro" @selected(old('fabricante', $equipamento->fabricante ?? '') === 'Outro')>Outro</option>
            </x-pw.select>

            <x-pw.input
                label="Modelo"
                name="modelo"
                :value="$equipamento->modelo ?? 'REP iDClass'"
                placeholder="REP iDClass Bio, Bio Prox, Facial..."
            />

            <x-pw.input
                label="Número de série"
                name="numero_serie"
                :value="$equipamento->numero_serie ?? ''"
            />
        </div>
    </x-pw.card>

    <x-pw.card>
        <div class="mb-5">
            <h3 class="text-base font-semibold text-slate-900">Conexão e integração</h3>
            <p class="mt-1 text-sm text-slate-500">Configure o acesso ao relógio ou ao agente instalado na rede local.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <x-pw.input
                label="Endereço IP"
                name="ip"
                :value="$equipamento->ip ?? ''"
                placeholder="192.168.0.100"
            />

            <x-pw.select
                label="Protocolo"
                name="protocolo"
                :value="$equipamento->protocolo ?? 'https'"
                required
            >
                <option value="https" @selected(old('protocolo', $equipamento->protocolo ?? 'https') === 'https')>HTTPS</option>
                <option value="http" @selected(old('protocolo', $equipamento->protocolo ?? 'https') === 'http')>HTTP</option>
            </x-pw.select>

            <x-pw.input
                label="Porta"
                name="porta"
                type="number"
                min="1"
                max="65535"
                :value="$equipamento->porta ?? 443"
            />

            <x-pw.select
                label="Tipo de integração"
                name="tipo_integracao"
                :value="$equipamento->tipo_integracao ?? 'api'"
                required
            >
                <option value="api" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? 'api') === 'api')>API Control iD</option>
                <option value="arquivo" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? 'api') === 'arquivo')>Arquivo CSV/AFD</option>
                <option value="rede" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? 'api') === 'rede')>Rede local</option>
                <option value="software_intermediario" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? 'api') === 'software_intermediario')>Agente local</option>
            </x-pw.select>

            <x-pw.input
                label="Usuário da API"
                name="usuario_api"
                :value="$equipamento->usuario_api ?? 'admin'"
                autocomplete="off"
            />

            <x-pw.input
                label="Senha da API"
                name="senha_api"
                type="password"
                value=""
                autocomplete="new-password"
                help="Na edição, deixe em branco para manter a senha atual."
            />

            <x-pw.input
                label="Timeout"
                name="timeout_segundos"
                type="number"
                min="3"
                max="120"
                :value="$equipamento->timeout_segundos ?? 15"
                help="Tempo máximo de espera, em segundos."
                required
            />

            <x-pw.input
                label="Fuso horário"
                name="timezone"
                :value="$equipamento->timezone ?? 'America/Sao_Paulo'"
                required
            />
        </div>
    </x-pw.card>

    <x-pw.card>
        <div class="mb-5">
            <h3 class="text-base font-semibold text-slate-900">Operação e segurança</h3>
            <p class="mt-1 text-sm text-slate-500">Defina como o equipamento será utilizado e validado.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <x-pw.checkbox
                label="Equipamento ativo"
                name="ativo"
                :checked="$equipamento->ativo ?? true"
                help="Equipamentos inativos não entram nos indicadores operacionais."
            />

            <x-pw.checkbox
                label="Usar Portaria 671"
                name="modo_671"
                :checked="$equipamento->modo_671 ?? true"
                help="Ativa os parâmetros compatíveis com a Portaria 671."
            />

            <x-pw.checkbox
                label="Validar certificado SSL"
                name="verificar_ssl"
                :checked="$equipamento->verificar_ssl ?? false"
                help="Mantenha desmarcado para certificados autoassinados em rede local."
            />
        </div>

        <x-pw.alert type="warning" class="mt-5">
            Os equipamentos iDClass normalmente usam HTTPS com certificado autoassinado. Não exponha o relógio diretamente à internet; prefira o Agente PontoWeb na rede local.
        </x-pw.alert>
    </x-pw.card>
</div>
