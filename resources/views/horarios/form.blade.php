<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input label="Descrição" name="descricao" :value="$item->descricao ?? ''" required />
    <div></div>
    <x-form.input label="Entrada" name="entrada" type="time" :value="isset($item) ? substr($item->entrada, 0, 5) : ''" required />
    <x-form.input label="Saída" name="saida" type="time" :value="isset($item) ? substr($item->saida, 0, 5) : ''" required />
    <x-form.input label="Início do intervalo" name="inicio_intervalo" type="time" :value="isset($item) && $item->inicio_intervalo ? substr($item->inicio_intervalo, 0, 5) : ''" />
    <x-form.input label="Fim do intervalo" name="fim_intervalo" type="time" :value="isset($item) && $item->fim_intervalo ? substr($item->fim_intervalo, 0, 5) : ''" />
    <x-form.input label="Tolerância na entrada (minutos)" name="tolerancia_entrada" type="number" min="0" max="180" :value="$item->tolerancia_entrada ?? 0" />
    <x-form.input label="Tolerância na saída (minutos)" name="tolerancia_saida" type="number" min="0" max="180" :value="$item->tolerancia_saida ?? 0" />
</div>
