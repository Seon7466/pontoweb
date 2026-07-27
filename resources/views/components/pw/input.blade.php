@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'help' => null,
])

<div>
    <label for="{{ $name }}" class="pw-label">
        {{ $label }}
        @if ($attributes->has('required'))
            <span class="text-red-500">*</span>
        @endif
    </label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => 'pw-field']) }}
    >

    @if ($help)
        <p class="mt-1.5 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif

    @error($name)
        <p class="pw-error">{{ $message }}</p>
    @enderror
</div>
