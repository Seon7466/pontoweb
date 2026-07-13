@props([
    'label',
    'name',
    'options' => [],
    'value' => '',
    'required' => false,
    'placeholder' => 'Selecione',
])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @class([
            'form-select',
            'is-invalid' => $errors->has($name),
        ])
        @required($required)
    >
        <option value="">{{ $placeholder }}</option>

        @foreach ($options as $optionValue => $optionLabel)
            <option
                value="{{ $optionValue }}"
                @selected((string) old($name, $value) === (string) $optionValue)
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
