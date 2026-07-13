<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="md:col-span-2 rounded border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700">
        Empresa: <strong>{{ auth()->user()->empresa?->nome_fantasia ?? auth()->user()->empresa?->razao_social }}</strong>
    </div>

    <x-form.input
        label="Nome"
        name="nome"
        :value="$funcionario->nome ?? ''"
        required
    />

    <x-form.input
        label="CPF"
        name="cpf"
        :value="$funcionario->cpf ?? ''"
        required
    />

    <x-form.input
        label="Matrícula"
        name="matricula"
        :value="$funcionario->matricula ?? ''"
    />

    <x-form.input
        label="Código no relógio"
        name="codigo_relogio"
        :value="$funcionario->codigo_relogio ?? ''"
    />

    <x-form.input
        label="E-mail"
        name="email"
        type="email"
        :value="$funcionario->email ?? ''"
    />

    <x-form.input
        label="Telefone"
        name="telefone"
        :value="$funcionario->telefone ?? ''"
    />

    <x-form.input
        label="Data de Admissão"
        name="admissao"
        type="date"
        :value="optional($funcionario?->admissao)->format('Y-m-d')"
        required
    />

</div>
