@props([
    'label',
    'name',
    'checked' => false,
    'help' => null,
])

<label class="group flex cursor-pointer items-start gap-3 rounded-xl border border-transparent p-2 transition hover:border-slate-200 hover:bg-slate-50">
    <input type="hidden" name="{{ $name }}" value="0">

    <input
        type="checkbox"
        id="{{ $name }}"
        name="{{ $name }}"
        value="1"
        @checked(old($name, $checked))
        {{ $attributes->merge(['class' => 'mt-0.5 rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500']) }}
    >

    <span class="min-w-0">
        <span class="block text-sm font-semibold text-slate-700 group-hover:text-slate-900">
            {{ $label }}
        </span>

        @if ($help)
            <span class="mt-0.5 block text-xs leading-5 text-slate-500">
                {{ $help }}
            </span>
        @endif
    </span>
</label>
