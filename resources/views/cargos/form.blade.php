<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input label="Nome" name="nome" :value="$item->nome ?? ''" required />
    <x-form.select label="Departamento" name="departamento_id" :options="$departamentos" :value="$item->departamento_id ?? ''" placeholder="Sem departamento" />
    <x-form.input label="CBO" name="cbo" :value="$item->cbo ?? ''" />
</div>
<div class="mt-4"><x-form.textarea label="Descrição" name="descricao" :value="$item->descricao ?? ''" rows="4" /></div>
<div class="mt-4"><x-form.checkbox label="Cargo ativo" name="ativo" :checked="$item->ativo ?? true" /></div>
