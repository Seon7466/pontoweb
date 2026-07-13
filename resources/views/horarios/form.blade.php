@if ($errors->any())
    <x-pw.alert type="error" title="Revise os campos informados" class="mb-6">
        Existem dados inválidos ou obrigatórios que precisam ser corrigidos.
    </x-pw.alert>
@endif

<div class="space-y-8">
    <section>
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Identificação</h3>
            <p class="mt-1 text-sm text-slate-500">Use uma descrição clara para facilitar a associação com funcionários e escalas.</p>
        </div>

        <div class="max-w-xl">
            <x-pw.input
                label="Descrição do horário"
                name="descricao"
                :value="$item->descricao ?? ''"
                placeholder="Ex.: Administrativo 08h às 17h"
                required
                autofocus
            />
        </div>
    </section>

    <section class="border-t border-slate-200 pt-6">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Jornada diária</h3>
            <p class="mt-1 text-sm text-slate-500">Informe os horários previstos de entrada, saída e intervalo.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <x-pw.input
                label="Entrada"
                name="entrada"
                type="time"
                :value="isset($item) && $item->entrada ? substr($item->entrada, 0, 5) : ''"
                required
            />

            <x-pw.input
                label="Início do intervalo"
                name="inicio_intervalo"
                type="time"
                :value="isset($item) && $item->inicio_intervalo ? substr($item->inicio_intervalo, 0, 5) : ''"
            />

            <x-pw.input
                label="Fim do intervalo"
                name="fim_intervalo"
                type="time"
                :value="isset($item) && $item->fim_intervalo ? substr($item->fim_intervalo, 0, 5) : ''"
            />

            <x-pw.input
                label="Saída"
                name="saida"
                type="time"
                :value="isset($item) && $item->saida ? substr($item->saida, 0, 5) : ''"
                required
            />
        </div>

        <p class="mt-3 text-xs text-slate-500">
            Para jornadas sem intervalo, deixe os dois campos de intervalo vazios.
        </p>
    </section>

    <section class="border-t border-slate-200 pt-6">
        <div class="mb-4">
            <h3 class="text-base font-semibold text-slate-900">Tolerâncias</h3>
            <p class="mt-1 text-sm text-slate-500">Defina a margem, em minutos, utilizada na futura apuração da jornada.</p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-pw.input
                label="Tolerância na entrada"
                name="tolerancia_entrada"
                type="number"
                min="0"
                max="180"
                :value="$item->tolerancia_entrada ?? 0"
                help="Quantidade máxima de minutos permitidos na entrada."
            />

            <x-pw.input
                label="Tolerância na saída"
                name="tolerancia_saida"
                type="number"
                min="0"
                max="180"
                :value="$item->tolerancia_saida ?? 0"
                help="Quantidade máxima de minutos permitidos na saída."
            />
        </div>
    </section>
</div>
