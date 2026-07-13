<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
    <div><x-input-label for="nome" value="Nome do equipamento" /><x-text-input id="nome" name="nome" class="mt-1 block w-full" :value="old('nome', $equipamento->nome ?? '')" required /><x-input-error :messages="$errors->get('nome')" class="mt-2" /></div>
    <div>
        <x-input-label for="fabricante" value="Fabricante" />
        <select id="fabricante" name="fabricante" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            <option value="">Selecione</option>
            <option value="Control iD" @selected(old('fabricante', $equipamento->fabricante ?? '') === 'Control iD')>Control iD</option>
            <option value="Outro" @selected(old('fabricante', $equipamento->fabricante ?? '') === 'Outro')>Outro</option>
        </select>
    </div>
    <div><x-input-label for="modelo" value="Modelo" /><x-text-input id="modelo" name="modelo" class="mt-1 block w-full" :value="old('modelo', $equipamento->modelo ?? 'REP iDClass')" placeholder="REP iDClass Bio, Bio Prox, Facial..." /></div>
    <div><x-input-label for="numero_serie" value="Número de série" /><x-text-input id="numero_serie" name="numero_serie" class="mt-1 block w-full" :value="old('numero_serie', $equipamento->numero_serie ?? '')" /></div>
    <div><x-input-label for="ip" value="Endereço IP" /><x-text-input id="ip" name="ip" class="mt-1 block w-full" :value="old('ip', $equipamento->ip ?? '')" placeholder="192.168.0.100" /></div>
    <div class="grid grid-cols-2 gap-3">
        <div><x-input-label for="protocolo" value="Protocolo" /><select id="protocolo" name="protocolo" class="mt-1 block w-full rounded-md border-gray-300"><option value="https" @selected(old('protocolo', $equipamento->protocolo ?? 'https') === 'https')>HTTPS</option><option value="http" @selected(old('protocolo', $equipamento->protocolo ?? '') === 'http')>HTTP</option></select></div>
        <div><x-input-label for="porta" value="Porta" /><x-text-input id="porta" name="porta" type="number" class="mt-1 block w-full" :value="old('porta', $equipamento->porta ?? 443)" /></div>
    </div>
    <div><x-input-label for="usuario_api" value="Usuário da API" /><x-text-input id="usuario_api" name="usuario_api" class="mt-1 block w-full" :value="old('usuario_api', $equipamento->usuario_api ?? 'admin')" autocomplete="off" /></div>
    <div><x-input-label for="senha_api" value="Senha da API" /><x-text-input id="senha_api" name="senha_api" type="password" class="mt-1 block w-full" value="" autocomplete="new-password" /><p class="mt-1 text-xs text-gray-500">Na edição, deixe em branco para manter a senha atual.</p></div>
    <div><x-input-label for="tipo_integracao" value="Tipo de integração" /><select id="tipo_integracao" name="tipo_integracao" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="api" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? 'api') === 'api')>API Control iD</option><option value="arquivo" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? '') === 'arquivo')>Arquivo CSV/AFD</option><option value="rede" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? '') === 'rede')>Rede local</option><option value="software_intermediario" @selected(old('tipo_integracao', $equipamento->tipo_integracao ?? '') === 'software_intermediario')>Agente local</option></select></div>
    <div><x-input-label for="timeout_segundos" value="Timeout (segundos)" /><x-text-input id="timeout_segundos" name="timeout_segundos" type="number" min="3" max="120" class="mt-1 block w-full" :value="old('timeout_segundos', $equipamento->timeout_segundos ?? 15)" /></div>
    <div><x-input-label for="timezone" value="Fuso horário" /><x-text-input id="timezone" name="timezone" class="mt-1 block w-full" :value="old('timezone', $equipamento->timezone ?? 'America/Sao_Paulo')" required /></div>
</div>

<div class="mt-5 grid gap-3 md:grid-cols-3">
    <label class="inline-flex items-center gap-2"><input type="checkbox" name="ativo" value="1" @checked(old('ativo', $equipamento->ativo ?? true)) class="rounded border-gray-300"><span>Equipamento ativo</span></label>
    <label class="inline-flex items-center gap-2"><input type="checkbox" name="modo_671" value="1" @checked(old('modo_671', $equipamento->modo_671 ?? true)) class="rounded border-gray-300"><span>Usar Portaria 671</span></label>
    <label class="inline-flex items-center gap-2"><input type="checkbox" name="verificar_ssl" value="1" @checked(old('verificar_ssl', $equipamento->verificar_ssl ?? false)) class="rounded border-gray-300"><span>Validar certificado SSL</span></label>
</div>
<p class="mt-3 text-sm text-amber-700">Os iDClass normalmente usam HTTPS com certificado autoassinado. Em rede local, a validação SSL pode permanecer desmarcada; não exponha o equipamento diretamente à internet.</p>
