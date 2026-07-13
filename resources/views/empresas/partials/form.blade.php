<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Razão Social *</label>
        <input type="text" name="razao_social" class="form-control" value="{{ old('razao_social', $empresa?->razao_social) }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Nome Fantasia</label>
        <input type="text" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia', $empresa?->nome_fantasia) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">CNPJ *</label>
        <input type="text" name="cnpj" class="form-control" value="{{ old('cnpj', $empresa?->cnpj) }}" required>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Inscrição Estadual</label>
        <input type="text" name="inscricao_estadual" class="form-control" value="{{ old('inscricao_estadual', $empresa?->inscricao_estadual) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Telefone</label>
        <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $empresa?->telefone) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $empresa?->email) }}">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">CEP</label>
        <input type="text" name="cep" class="form-control" value="{{ old('cep', $empresa?->cep) }}">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">Estado</label>
        <input type="text" name="estado" class="form-control" maxlength="2" value="{{ old('estado', $empresa?->estado) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Endereço</label>
        <input type="text" name="endereco" class="form-control" value="{{ old('endereco', $empresa?->endereco) }}">
    </div>

    <div class="col-md-2 mb-3">
        <label class="form-label">Número</label>
        <input type="text" name="numero" class="form-control" value="{{ old('numero', $empresa?->numero) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Bairro</label>
        <input type="text" name="bairro" class="form-control" value="{{ old('bairro', $empresa?->bairro) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Cidade</label>
        <input type="text" name="cidade" class="form-control" value="{{ old('cidade', $empresa?->cidade) }}">
    </div>

    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="ativo" value="0">
            <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $empresa?->ativo ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="ativo">Empresa ativa</label>
        </div>
    </div>
</div>
