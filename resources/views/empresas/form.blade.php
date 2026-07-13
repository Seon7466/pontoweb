<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input
        label="Razão Social"
        name="razao_social"
        :value="$empresa->razao_social ?? ''"
    />

    <x-form.input
        label="Nome Fantasia"
        name="nome_fantasia"
        :value="$empresa->nome_fantasia ?? ''"
    />

    <x-form.input
        label="CNPJ"
        name="cnpj"
        :value="$empresa->cnpj ?? ''"
    />

    <x-form.input
        label="E-mail"
        name="email"
        type="email"
        :value="$empresa->email ?? ''"
    />

    <x-form.input
        label="Telefone"
        name="telefone"
        :value="$empresa->telefone ?? ''"
    />

    <x-form.input
        label="Cidade"
        name="cidade"
        :value="$empresa->cidade ?? ''"
    />
</div>
