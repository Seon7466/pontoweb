@php
    $funcionario = $funcionario ?? null;
@endphp
<div class="space-y-8">
    <x-pw.alert variant="info">
        Este funcionário será vinculado à empresa
        <strong>{{ auth()->user()->empresa?->nome_fantasia ?? auth()->user()->empresa?->razao_social }}</strong>.
    </x-pw.alert>

    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Dados pessoais</h3>
            <p class="mt-1 text-sm text-slate-500">Informações de identificação e contato do colaborador.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <div class="md:col-span-2 xl:col-span-2">
                <x-pw.input label="Nome completo" name="nome" :value="$funcionario->nome ?? ''" required />
            </div>
            <x-pw.input label="CPF" name="cpf" :value="$funcionario->cpf ?? ''" required />
            <x-pw.input label="RG" name="rg" :value="$funcionario->rg ?? ''" />
            <x-pw.input label="PIS" name="pis" :value="$funcionario->pis ?? ''" help="Utilizado em integrações e arquivos de ponto." />
            <x-pw.input label="Data de nascimento" name="nascimento" type="date" :value="optional($funcionario?->nascimento)->format('Y-m-d')" />
            <x-pw.input label="E-mail" name="email" type="email" :value="$funcionario->email ?? ''" />
            <x-pw.input label="Telefone" name="telefone" :value="$funcionario->telefone ?? ''" />
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Vínculo profissional</h3>
            <p class="mt-1 text-sm text-slate-500">Dados contratuais e lotação dentro da empresa.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <x-pw.input label="Matrícula" name="matricula" :value="$funcionario->matricula ?? ''" />
            <x-pw.input label="Código no relógio" name="codigo_relogio" :value="$funcionario->codigo_relogio ?? ''" help="Identificador utilizado pelo relógio biométrico." />
            <x-pw.input label="Data de admissão" name="admissao" type="date" :value="optional($funcionario?->admissao)->format('Y-m-d')" required />
            <x-pw.input label="Data de demissão" name="demissao" type="date" :value="optional($funcionario?->demissao)->format('Y-m-d')" />

            <x-pw.select label="Departamento" name="departamento_id" :value="$funcionario->departamento_id ?? ''">
                @foreach ($departamentos as $departamento)
                    <option value="{{ $departamento->id }}" @selected((string) old('departamento_id', $funcionario->departamento_id ?? '') === (string) $departamento->id)>
                        {{ $departamento->nome }}
                    </option>
                @endforeach
            </x-pw.select>

            <x-pw.select label="Cargo" name="cargo_id" :value="$funcionario->cargo_id ?? ''">
                @foreach ($cargos as $cargo)
                    <option value="{{ $cargo->id }}" @selected((string) old('cargo_id', $funcionario->cargo_id ?? '') === (string) $cargo->id)>
                        {{ $cargo->nome }}
                    </option>
                @endforeach
            </x-pw.select>
        </div>
    </section>

    <section class="border-t border-slate-200 pt-7">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Jornada de trabalho</h3>
            <p class="mt-1 text-sm text-slate-500">Associe horário e escala para permitir a futura apuração da jornada.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.select label="Horário" name="horario_id" :value="$funcionario->horario_id ?? ''">
                @foreach ($horarios as $horario)
                    <option value="{{ $horario->id }}" @selected((string) old('horario_id', $funcionario->horario_id ?? '') === (string) $horario->id)>
                        {{ $horario->descricao }}
                    </option>
                @endforeach
            </x-pw.select>

            <x-pw.select label="Escala" name="escala_id" :value="$funcionario->escala_id ?? ''">
                @foreach ($escalas as $escala)
                    <option value="{{ $escala->id }}" @selected((string) old('escala_id', $funcionario->escala_id ?? '') === (string) $escala->id)>
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
            :checked="$funcionario->status ?? true"
            help="Funcionários inativos permanecem no histórico, mas deixam de participar da operação normal."
        />
    </section>
</div>
