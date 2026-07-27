@csrf

@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Corrija os campos abaixo:</p>

        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-5">
        <div>
            <label for="razao_social" class="text-sm font-medium text-slate-700">
                Razão social
            </label>

            <input
                id="razao_social"
                type="text"
                name="razao_social"
                value="{{ old('razao_social', $empresa->razao_social ?? '') }}"
                class="pw-field"
                required
            >
        </div>

        <div>
            <label for="nome_fantasia" class="text-sm font-medium text-slate-700">
                Nome fantasia
            </label>

            <input
                id="nome_fantasia"
                type="text"
                name="nome_fantasia"
                value="{{ old('nome_fantasia', $empresa->nome_fantasia ?? '') }}"
                class="pw-field"
                required
            >
        </div>

        <div>
            <label for="cnpj" class="text-sm font-medium text-slate-700">
                CNPJ
            </label>

            <input
                id="cnpj"
                type="text"
                name="cnpj"
                value="{{ old('cnpj', $empresa->cnpj ?? '') }}"
                class="pw-field"
                maxlength="18"
                required
            >
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="email" class="text-sm font-medium text-slate-700">
                    E-mail
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $empresa->email ?? '') }}"
                    class="pw-field"
                >
            </div>

            <div>
                <label for="telefone" class="text-sm font-medium text-slate-700">
                    Telefone
                </label>

                <input
                    id="telefone"
                    type="text"
                    name="telefone"
                    value="{{ old('telefone', $empresa->telefone ?? '') }}"
                    class="pw-field"
                    maxlength="20"
                >
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div>
            <label for="cep" class="text-sm font-medium text-slate-700">
                CEP
            </label>

            <input
                id="cep"
                type="text"
                name="cep"
                value="{{ old('cep', $empresa->cep ?? '') }}"
                class="pw-field"
                maxlength="10"
            >
        </div>

        <div class="grid gap-5 sm:grid-cols-[1fr_140px]">
            <div>
                <label for="endereco" class="text-sm font-medium text-slate-700">
                    Endereço
                </label>

                <input
                    id="endereco"
                    type="text"
                    name="endereco"
                    value="{{ old('endereco', $empresa->endereco ?? '') }}"
                    class="pw-field"
                >
            </div>

            <div>
                <label for="numero" class="text-sm font-medium text-slate-700">
                    Número
                </label>

                <input
                    id="numero"
                    type="text"
                    name="numero"
                    value="{{ old('numero', $empresa->numero ?? '') }}"
                    class="pw-field"
                    maxlength="20"
                >
            </div>
        </div>

        <div>
            <label for="bairro" class="text-sm font-medium text-slate-700">
                Bairro
            </label>

            <input
                id="bairro"
                type="text"
                name="bairro"
                value="{{ old('bairro', $empresa->bairro ?? '') }}"
                class="pw-field"
            >
        </div>

        <div class="grid gap-5 sm:grid-cols-[1fr_100px]">
            <div>
                <label for="cidade" class="text-sm font-medium text-slate-700">
                    Cidade
                </label>

                <input
                    id="cidade"
                    type="text"
                    name="cidade"
                    value="{{ old('cidade', $empresa->cidade ?? '') }}"
                    class="pw-field"
                >
            </div>

            <div>
                <label for="estado" class="text-sm font-medium text-slate-700">
                    UF
                </label>

                <input
                    id="estado"
                    type="text"
                    name="estado"
                    value="{{ old('estado', $empresa->estado ?? '') }}"
                    class="pw-field uppercase"
                    maxlength="2"
                >
            </div>
        </div>
    </div>
</div>

<div class="border-t border-slate-200 pt-6">
    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <label for="plano_id" class="text-sm font-medium text-slate-700">
                Plano
            </label>

            <select id="plano_id" name="plano_id" class="pw-field" required>
                <option value="">Selecione um plano</option>

                @foreach ($planos as $plano)
                    <option
                        value="{{ $plano->id }}"
                        @selected(
                            (string) old(
                                'plano_id',
                                $empresa->licenca?->plano_id ?? ''
                            ) === (string) $plano->id
                        )
                    >
                        {{ $plano->nome }} — {{ $plano->valor_label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end">
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 px-4 py-3">
                <input
                    type="checkbox"
                    name="ativo"
                    value="1"
                    class="rounded border-slate-300 text-brand-600 focus:ring-brand-500"
                    @checked(old('ativo', $empresa->ativo ?? true))
                >

                <span>
                    <span class="block text-sm font-semibold text-slate-900">
                        Empresa ativa
                    </span>

                    <span class="block text-xs text-slate-500">
                        Permite o acesso e uso da plataforma.
                    </span>
                </span>
            </label>
        </div>
    </div>
</div>

@if (! isset($empresa))
    <div class="border-t border-slate-200 pt-6">
        <div>
            <h3 class="text-base font-semibold text-slate-900">
                Administrador da empresa
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Este usuário será criado junto com a empresa.
            </p>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-2">
            <div>
                <label for="admin_name" class="text-sm font-medium text-slate-700">
                    Nome
                </label>

                <input
                    id="admin_name"
                    type="text"
                    name="admin_name"
                    value="{{ old('admin_name') }}"
                    class="pw-field"
                    required
                >
            </div>

            <div>
                <label for="admin_email" class="text-sm font-medium text-slate-700">
                    E-mail
                </label>

                <input
                    id="admin_email"
                    type="email"
                    name="admin_email"
                    value="{{ old('admin_email') }}"
                    class="pw-field"
                    required
                >
            </div>

            <div>
                <label for="admin_password" class="text-sm font-medium text-slate-700">
                    Senha
                </label>

                <input
                    id="admin_password"
                    type="password"
                    name="admin_password"
                    class="pw-field"
                    required
                >
            </div>

            <div>
                <label for="admin_password_confirmation" class="text-sm font-medium text-slate-700">
                    Confirmar senha
                </label>

                <input
                    id="admin_password_confirmation"
                    type="password"
                    name="admin_password_confirmation"
                    class="pw-field"
                    required
                >
            </div>
        </div>
    </div>
@endif
