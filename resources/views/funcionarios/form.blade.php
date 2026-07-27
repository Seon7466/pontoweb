@php
    $funcionario = $funcionario ?? null;
@endphp

<div class="space-y-7">
    <x-pw.alert variant="info">
        Este funcionário será vinculado à empresa
        <strong>
            {{ auth()->user()->empresa?->nome_fantasia
                ?? auth()->user()->empresa?->razao_social
                ?? 'não identificada' }}
        </strong>.
    </x-pw.alert>

    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">
                Dados pessoais
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Informações de identificação e contato do colaborador.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="md:col-span-2 xl:col-span-2">
                <x-pw.input
                    label="Nome completo"
                    name="nome"
                    :value="old('nome', $funcionario?->nome)"
                    required
                />
            </div>

            <x-pw.input
                label="CPF"
                name="cpf"
                :value="old('cpf', $funcionario?->cpf)"
                required
            />

            <x-pw.input
                label="RG"
                name="rg"
                :value="old('rg', $funcionario?->rg)"
            />

            <x-pw.input
                label="PIS"
                name="pis"
                :value="old('pis', $funcionario?->pis)"
                help="Utilizado em integrações e arquivos de ponto."
            />

            <x-pw.input
                label="Data de nascimento"
                name="nascimento"
                type="date"
                :value="old(
                    'nascimento',
                    $funcionario?->nascimento?->format('Y-m-d')
                )"
            />

            <x-pw.input
                label="E-mail"
                name="email"
                type="email"
                :value="old('email', $funcionario?->email)"
            />

            <x-pw.input
                label="Telefone"
                name="telefone"
                :value="old('telefone', $funcionario?->telefone)"
            />
        </div>
    </section>

    <section class="border-t border-slate-200 pt-6">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">
                Vínculo profissional
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Dados contratuais e lotação dentro da empresa.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <x-pw.input
                label="Matrícula"
                name="matricula"
                :value="old('matricula', $funcionario?->matricula)"
            />

            <x-pw.input
                label="Código no relógio"
                name="codigo_relogio"
                :value="old(
                    'codigo_relogio',
                    $funcionario?->codigo_relogio
                )"
                help="Identificador utilizado pelo relógio biométrico."
            />

            <x-pw.input
                label="Data de admissão"
                name="admissao"
                type="date"
                :value="old(
                    'admissao',
                    $funcionario?->admissao?->format('Y-m-d')
                )"
                required
            />

            <x-pw.input
                label="Data de demissão"
                name="demissao"
                type="date"
                :value="old(
                    'demissao',
                    $funcionario?->demissao?->format('Y-m-d')
                )"
            />

            <x-pw.select
                label="Departamento"
                name="departamento_id"
                :value="old(
                    'departamento_id',
                    $funcionario?->departamento_id
                )"
            >
                <option value="">
                    Selecione um departamento
                </option>

                @foreach ($departamentos as $departamento)
                    <option
                        value="{{ $departamento->id }}"
                        @selected(
                            (string) old(
                                'departamento_id',
                                $funcionario?->departamento_id
                            ) === (string) $departamento->id
                        )
                    >
                        {{ $departamento->nome }}
                    </option>
                @endforeach
            </x-pw.select>

            <x-pw.select
                label="Cargo"
                name="cargo_id"
                :value="old(
                    'cargo_id',
                    $funcionario?->cargo_id
                )"
            >
                <option value="">
                    Selecione um cargo
                </option>

                @foreach ($cargos as $cargo)
                    <option
                        value="{{ $cargo->id }}"
                        data-departamento-id="{{ $cargo->departamento_id }}"
                        @selected(
                            (string) old(
                                'cargo_id',
                                $funcionario?->cargo_id
                            ) === (string) $cargo->id
                        )
                    >
                        {{ $cargo->nome }}
                    </option>
                @endforeach
            </x-pw.select>
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">
                Jornada de trabalho
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Associe horário e escala para permitir a apuração da jornada.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.select
                label="Horário"
                name="horario_id"
                :value="old(
                    'horario_id',
                    $funcionario?->horario_id
                )"
            >
                <option value="">
                    Selecione um horário
                </option>

                @foreach ($horarios as $horario)
                    <option
                        value="{{ $horario->id }}"
                        @selected(
                            (string) old(
                                'horario_id',
                                $funcionario?->horario_id
                            ) === (string) $horario->id
                        )
                    >
                        {{ $horario->descricao }}
                    </option>
                @endforeach
            </x-pw.select>

            <x-pw.select
                label="Escala"
                name="escala_id"
                :value="old(
                    'escala_id',
                    $funcionario?->escala_id
                )"
            >
                <option value="">
                    Selecione uma escala
                </option>

                @foreach ($escalas as $escala)
                    <option
                        value="{{ $escala->id }}"
                        @selected(
                            (string) old(
                                'escala_id',
                                $funcionario?->escala_id
                            ) === (string) $escala->id
                        )
                    >
                        {{ $escala->descricao }}
                    </option>
                @endforeach
            </x-pw.select>
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <x-pw.checkbox
            label="Funcionário ativo"
            name="status"
            :checked="(bool) old(
                'status',
                $funcionario?->status ?? true
            )"
            help="Funcionários inativos permanecem no histórico, mas deixam de participar da operação normal."
        />
    </section>
</div>
