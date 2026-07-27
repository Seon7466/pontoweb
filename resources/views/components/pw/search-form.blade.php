@props([
    'placeholder' => 'Buscar...',
    'name' => 'search',
    'value' => null,
])

<form method="GET" {{ $attributes->merge(['class' => 'flex w-full flex-col gap-2 sm:w-auto sm:flex-row']) }}>
    <div class="relative min-w-0 flex-1 sm:min-w-80">
        <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
        </svg>

        <input
            type="search"
            name="{{ $name }}"
            value="{{ $value ?? request($name) }}"
            placeholder="{{ $placeholder }}"
            class="pw-field !mt-0 w-full !rounded-xl pl-10"
        >
    </div>

    <div class="flex gap-2">
        <x-pw.button type="submit" variant="secondary">
            Buscar
        </x-pw.button>

        @if (filled($value ?? request($name)))
            <x-pw.button :href="url()->current()" variant="ghost">
                Limpar
            </x-pw.button>
        @endif
    </div>
</form>
