@props([
    'cancelHref',
    'submitLabel' => 'Salvar',
])

<div {{ $attributes->merge(['class' => 'mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-end']) }}>
    <x-pw.button :href="$cancelHref" variant="secondary" class="w-full sm:w-auto">
        Cancelar
    </x-pw.button>

    <x-pw.button type="submit" variant="success" class="w-full sm:w-auto">
        {{ $submitLabel }}
    </x-pw.button>
</div>
