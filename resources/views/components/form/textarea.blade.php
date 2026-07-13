@props([
    'label',
    'name',
    'value' => '',
    'rows' => 3,
    'required' => false,
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @class([
            'form-control',
            'is-invalid' => $errors->has($name)
        ])
        @required($required)
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
