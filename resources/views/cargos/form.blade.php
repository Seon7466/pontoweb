@if ($errors->any())
    <x-pw.alert type="error" title="Revise os campos informados" class="mb-6">
        Existem dados inválidos ou obrigatórios que precisam ser corrigidos.
    </x-pw.alert>
@endif

<div class="space-y-8">
    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Informações principais</h3>
            <p class="mt-1 text-sm text-slate-500">Identifique o cargo e seu departamento responsável.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <x-pw.input
                label="Nome do cargo"
                name="nome"
                :value="$item->nome ?? ''"
                placeholder="Ex.: Analista administrativo"
                required
                autofocus
            />

            <x-pw.select
                label="Departamento"
                name="departamento_id"
                :value="$item->departamento_id ?? ''"
                placeholder="Sem departamento"
                help="Somente departamentos ativos são exibidos."
            >
                @foreach ($departamentos as $id => $nome)
                    <option value="{{ $id }}" @selected((string) old('departamento_id', $item->departamento_id ?? '') === (string) $id)>
                        {{ $nome }}
                    </option>
                @endforeach
            </x-pw.select>

            <x-pw.input
                label="Código CBO"
                name="cbo"
                :value="$item->cbo ?? ''"
                placeholder="Ex.: 2521-05"
                help="Classificação Brasileira de Ocupações, quando aplicável."
            />
        </div>
    </section>

    <section class="border-t border-slate-200 pt-6">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Descrição e situação</h3>
            <p class="mt-1 text-sm text-slate-500">Registre responsabilidades ou observações importantes.</p>
        </div>

        <div class="space-y-5">
            <x-pw.textarea
                label="Descrição"
                name="descricao"
                :value="$item->descricao ?? ''"
                rows="5"
                placeholder="Descreva as atribuições e responsabilidades do cargo..."
            />

            <x-pw.checkbox
                label="Cargo ativo"
                name="ativo"
                :checked="$item->ativo ?? true"
                help="Cargos inativos permanecem no histórico, mas não devem ser usados em novos vínculos."
            />
        </div>
    </section>
</div>
