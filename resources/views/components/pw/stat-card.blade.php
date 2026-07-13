@props([
    'label',
    'value',
    'hint' => null,
    'tone' => 'brand',
])

@php
    $toneClasses = [
        'brand' => 'bg-blue-50 text-blue-700',
        'success' => 'bg-emerald-50 text-emerald-700',
        'warning' => 'bg-amber-50 text-amber-700',
        'danger' => 'bg-red-50 text-red-700',
        'neutral' => 'bg-slate-100 text-slate-700',
    ];

    $selectedTone = $toneClasses[$tone] ?? $toneClasses['brand'];
@endphp

<x-pw.card>
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">
                {{ $label }}
            </p>

            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                {{ $value }}
            </p>

            @if ($hint)
                <p class="mt-1 text-xs text-slate-500">
                    {{ $hint }}
                </p>
            @endif
        </div>

        <div class="rounded-lg p-2.5 {{ $selectedTone }}">
            {{ $icon ?? '' }}
        </div>
    </div>
</x-pw.card>