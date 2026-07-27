@props([
    'label',
    'value',
    'hint' => null,
    'tone' => 'brand',
])

@php
    $tones = [
        'brand' => ['bar' => 'bg-blue-600', 'icon' => 'bg-blue-50 text-blue-700'],
        'success' => ['bar' => 'bg-emerald-600', 'icon' => 'bg-emerald-50 text-emerald-700'],
        'warning' => ['bar' => 'bg-amber-500', 'icon' => 'bg-amber-50 text-amber-700'],
        'danger' => ['bar' => 'bg-red-600', 'icon' => 'bg-red-50 text-red-700'],
        'neutral' => ['bar' => 'bg-slate-500', 'icon' => 'bg-slate-100 text-slate-700'],
    ];

    $style = $tones[$tone] ?? $tones['brand'];
@endphp

<x-pw.card class="relative overflow-hidden">
    <span class="absolute inset-y-0 left-0 w-1 {{ $style['bar'] }}"></span>

    <div class="flex items-start justify-between gap-4 pl-1">
        <div class="min-w-0">
            <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $value }}</p>

            @if ($hint)
                <p class="mt-1 text-xs leading-5 text-slate-500">{{ $hint }}</p>
            @endif
        </div>

        @isset($icon)
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $style['icon'] }}">
                {{ $icon }}
            </div>
        @endisset
    </div>
</x-pw.card>
