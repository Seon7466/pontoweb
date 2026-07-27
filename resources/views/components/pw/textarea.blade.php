@props([
    'label',
    'name',
    'value' => '',
    'rows' => 4,
    'help' => null,
])

<div>
    <label for="{{ $name }}" class="pw-label">{{ $label }}</label>

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        {{ $attributes->merge(['class' => 'pw-field']) }}
    >{{ old($name, $value) }}</textarea>

    @if ($help)
        <p class="mt-1.5 text-xs leading-5 text-slate-500">{{ $help }}</p>
    @endif

    @error($name)
        <p class="pw-error">{{ $message }}</p>
    @enderror
</div>
