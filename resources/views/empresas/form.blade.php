@php
    $empresa = $empresa ?? $item ?? null;
@endphp

<div class="space-y-8">

    <section>
        <h3 class="text-base font-semibold text-slate-900">
            Identificação
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">

            <x-pw.input
                label="Razão social"
                name="razao_social"
                :value="$empresa?->razao_social"
                required
            />

            <x-pw.input
                label="Nome fantasia"
                name="nome_fantasia"
                :value="$empresa?->nome_fantasia"
                required
            />

            <x-pw.input
                label="CNPJ"
                name="cnpj"
                :value="$empresa?->cnpj"
                required
            />

            <x-pw.input
                label="Inscrição estadual"
                name="inscricao_estadual"
                :value="$empresa?->inscricao_estadual"
            />

        </div>
    </section>


    <section class="border-t pt-6">

        <h3 class="text-base font-semibold">
            Contato
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">

            <x-pw.input
                label="E-mail"
                name="email"
                type="email"
                :value="$empresa?->email"
            />

            <x-pw.input
                label="Telefone"
                name="telefone"
                :value="$empresa?->telefone"
            />

        </div>

    </section>


    <section class="border-t pt-6">

        <h3 class="text-base font-semibold">
            Endereço
        </h3>

        <div class="grid grid-cols-6 gap-5 mt-4">

            <div class="col-span-2">
                <x-pw.input
                    label="CEP"
                    name="cep"
                    :value="$empresa?->cep"
                />
            </div>

            <div class="col-span-3">
                <x-pw.input
                    label="Endereço"
                    name="endereco"
                    :value="$empresa?->endereco"
                />
            </div>

            <div class="col-span-1">
                <x-pw.input
                    label="Número"
                    name="numero"
                    :value="$empresa?->numero"
                />
            </div>

            <div class="col-span-2">
                <x-pw.input
                    label="Bairro"
                    name="bairro"
                    :value="$empresa?->bairro"
                />
            </div>

            <div class="col-span-3">
                <x-pw.input
                    label="Cidade"
                    name="cidade"
                    :value="$empresa?->cidade"
                />
            </div>

            <div class="col-span-1">
                <x-pw.input
                    label="UF"
                    name="estado"
                    maxlength="2"
                    :value="$empresa?->estado"
                />
            </div>

        </div>

    </section>


    <section class="border-t pt-6">

        <h3 class="text-base font-semibold">
            Logo da Empresa
        </h3>

        <div class="mt-4">

            <input
                type="file"
                name="logo"
                accept=".png,.jpg,.jpeg,.webp"
                class="block w-full rounded border px-3 py-2"
            >

        </div>

        @error('logo')

            <div class="text-red-600 mt-2">

                {{ $message }}

            </div>

        @enderror

        @if($empresa?->logo)

            <div class="mt-5">

                <div class="text-sm text-slate-600 mb-2">

                    Logo atual

                </div>

                <img
                    src="{{ asset('storage/'.$empresa->logo) }}"
                    style="max-height:100px"
                    class="border rounded p-2 bg-white"
                >

            </div>

        @endif

    </section>


    <section class="border-t pt-6">

        <x-pw.checkbox
            name="ativo"
            label="Empresa ativa"
            :checked="$empresa?->ativo ?? true"
        />

    </section>

</div>