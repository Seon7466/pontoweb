<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input label="Nome" name="nome" :value="$item->nome ?? ''" required />
    <x-form.input label="Responsável" name="responsavel" :value="$item->responsavel ?? ''" />
</div>
<div class="mt-4">
    <x-form.checkbox label="Departamento ativo" name="ativo" :checked="$item->ativo ?? true" />
</div>
