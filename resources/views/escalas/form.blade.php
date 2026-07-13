<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input label="Descrição" name="descricao" :value="$item->descricao ?? ''" required />
    <x-form.select label="Tipo" name="tipo" :options="['5x2'=>'5x2','6x1'=>'6x1','12x36'=>'12x36','Personalizada'=>'Personalizada']" :value="$item->tipo ?? '5x2'" required />
</div>
<div class="mt-5"><p class="mb-3 text-sm font-medium text-gray-700">Dias de trabalho</p><div class="grid grid-cols-2 md:grid-cols-4 gap-2">
    <x-form.checkbox label="Domingo" name="domingo" :checked="$item->domingo ?? false" />
    <x-form.checkbox label="Segunda" name="segunda" :checked="$item->segunda ?? true" />
    <x-form.checkbox label="Terça" name="terca" :checked="$item->terca ?? true" />
    <x-form.checkbox label="Quarta" name="quarta" :checked="$item->quarta ?? true" />
    <x-form.checkbox label="Quinta" name="quinta" :checked="$item->quinta ?? true" />
    <x-form.checkbox label="Sexta" name="sexta" :checked="$item->sexta ?? true" />
    <x-form.checkbox label="Sábado" name="sabado" :checked="$item->sabado ?? false" />
</div></div>
<div class="mt-4"><x-form.checkbox label="Escala ativa" name="ativo" :checked="$item->ativo ?? true" /></div>
