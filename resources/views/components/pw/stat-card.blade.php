@props([
    'label',
    'value',
    'hint' => null,
    'tone' => 'brand',
])

@php
    $tones = [
        'brand' => [
            'bar' => 'bg-blue-600',
            'soft' => 'bg-blue-50 text-blue-700 ring-blue-100',
            'dot' => 'bg-blue-500',
        ],
        'success' => [
            'bar' => 'bg-emerald-600',
            'soft' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'dot' => 'bg-emerald-500',
        ],
        'warning' => [
            'bar' => 'bg-amber-500',
            'soft' => 'bg-amber-50 text-amber-700 ring-amber-100',
            'dot' => 'bg-amber-500',
        ],
        'danger' => [
            'bar' => 'bg-red-600',
            'soft' => 'bg-red-50 text-red-700 ring-red-100',
            'dot' => 'bg-red-500',
        ],
        'neutral' => [
            'bar' => 'bg-slate-500',
            'soft' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'dot' => 'bg-slate-500',
        ],
    ];

    $style = $tones[$tone] ?? $tones['brand'];
@endphp

<x-pw.card class="group relative overflow-hidden hover:shadow-md">
    <span class="absolute inset-x-0 top-0 h-1 {{ $style['bar'] }}"></span>

    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full {{ $style['dot'] }}"></span>
                <p class="truncate text-xs font-bold uppercase tracking-[0.08em] text-slate-500">
                    {{ $label }}
                </p>
            </div>

            <p class="mt-3 text-3xl font-extrabold tracking-tight text-slate-950">
                {{ $value }}
            </p>

            @if ($hint)
                <p class="mt-1.5 text-xs font-medium leading-5 text-slate-500">
                    {{ $hint }}
                </p>
            @endif
        </div>

        @isset($icon)
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ring-1 {{ $style['soft'] }}">
                {{ $icon }}
            </div>
        @endisset
    </div>
</x-pw.card>
