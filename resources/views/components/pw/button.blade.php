@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'pw-focus inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl font-bold transition duration-150 disabled:cursor-not-allowed disabled:opacity-50';

    $sizes = [
        'sm' => 'min-h-9 px-3 py-2 text-xs',
        'md' => 'min-h-10 px-4 py-2.5 text-sm',
        'lg' => 'min-h-12 px-5 py-3 text-base',
    ];

    $variants = [
        'primary' => 'bg-blue-600 text-white shadow-sm shadow-blue-600/20 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md active:translate-y-0',
        'secondary' => 'border border-slate-300 bg-white text-slate-700 shadow-sm hover:-translate-y-0.5 hover:border-slate-400 hover:bg-slate-50 hover:text-slate-900 active:translate-y-0',
        'success' => 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/20 hover:-translate-y-0.5 hover:bg-emerald-700 hover:shadow-md active:translate-y-0',
        'danger' => 'bg-red-600 text-white shadow-sm shadow-red-600/20 hover:-translate-y-0.5 hover:bg-red-700 hover:shadow-md active:translate-y-0',
        'ghost' => 'text-slate-600 hover:bg-slate-100 hover:text-slate-950',
    ];

    $classes = $base.' '.($sizes[$size] ?? $sizes['md']).' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
