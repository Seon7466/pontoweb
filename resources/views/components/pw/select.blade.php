@props([
    'label',
    'name',
    'value' => '',
    'placeholder' => 'Selecione',
    'help' => null,
])

<div>
    <label for="{{ $name }}" class="pw-label">
        {{ $label }}
        @if ($attributes->has('required'))
            <span class="text-red-500">*</span>
        @endif
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => 'pw-field']) }}
    >
        <option value="">{{ $placeholder }}</option>
        {{ $slot }}
    </select>

    @if ($help)
        <p class="mt-1.5 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif

    @error($name)
        <p class="pw-error">{{ $message }}</p>
    @enderror
</div>
