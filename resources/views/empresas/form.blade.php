<div class="space-y-8">
    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Identificação</h3>
            <p class="mt-1 text-sm text-slate-500">Dados oficiais e comerciais da empresa.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.input
                label="Razão social"
                name="razao_social"
                :value="$empresa->razao_social ?? ''"
                required
            />

            <x-pw.input
                label="Nome fantasia"
                name="nome_fantasia"
                :value="$empresa->nome_fantasia ?? ''"
                required
            />

            <x-pw.input
                label="CNPJ"
                name="cnpj"
                :value="$empresa->cnpj ?? ''"
                placeholder="00.000.000/0000-00"
                required
            />

            <x-pw.input
                label="Inscrição estadual"
                name="inscricao_estadual"
                :value="$empresa->inscricao_estadual ?? ''"
            />
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Contato</h3>
            <p class="mt-1 text-sm text-slate-500">Informações para contato administrativo.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.input
                label="E-mail"
                name="email"
                type="email"
                :value="$empresa->email ?? ''"
                placeholder="contato@empresa.com.br"
            />

            <x-pw.input
                label="Telefone"
                name="telefone"
                :value="$empresa->telefone ?? ''"
                placeholder="(00) 00000-0000"
            />
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Endereço</h3>
            <p class="mt-1 text-sm text-slate-500">Endereço principal da empresa.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-6">
            <div class="md:col-span-2">
                <x-pw.input label="CEP" name="cep" :value="$empresa->cep ?? ''" placeholder="00000-000" />
            </div>

            <div class="md:col-span-3">
                <x-pw.input label="Endereço" name="endereco" :value="$empresa->endereco ?? ''" />
            </div>

            <div class="md:col-span-1">
                <x-pw.input label="Número" name="numero" :value="$empresa->numero ?? ''" />
            </div>

            <div class="md:col-span-2">
                <x-pw.input label="Bairro" name="bairro" :value="$empresa->bairro ?? ''" />
            </div>

            <div class="md:col-span-3">
                <x-pw.input label="Cidade" name="cidade" :value="$empresa->cidade ?? ''" />
            </div>

            <div class="md:col-span-1">
                <x-pw.input
                    label="UF"
                    name="estado"
                    :value="$empresa->estado ?? ''"
                    maxlength="2"
                    placeholder="SP"
                />
            </div>
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <x-pw.checkbox
            label="Empresa ativa"
            name="ativo"
            :checked="$empresa->ativo ?? true"
            help="Empresas inativas permanecem cadastradas, mas podem ter seu acesso limitado futuramente."
        />
    </section>
</div>
