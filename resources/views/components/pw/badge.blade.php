@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'brand' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'primary' => 'bg-blue-50 text-blue-700 ring-blue-200',
        'success' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'danger' => 'bg-red-50 text-red-700 ring-red-200',
        'info' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];

    $selectedVariant = $variants[$variant] ?? $variants['neutral'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold leading-none ring-1 ring-inset {$selectedVariant}",
]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
    {{ $slot }}
</span>
