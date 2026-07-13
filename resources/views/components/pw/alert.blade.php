@props([
    'type' => null,
    'variant' => null,
    'title' => null,
])

@php
    $selectedType = $variant ?? $type ?? 'info';
    $classes = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'error' => 'border-red-200 bg-red-50 text-red-800',
        'danger' => 'border-red-200 bg-red-50 text-red-800',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'info' => 'border-blue-200 bg-blue-50 text-blue-800',
    ];
    $selectedClass = $classes[$selectedType] ?? $classes['info'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg border px-4 py-3 text-sm {$selectedClass}"]) }}>
    @if ($title)
        <p class="font-semibold">{{ $title }}</p>
    @endif

    <div @class(['mt-1' => $title])>
        {{ $slot }}
    </div>
</div>
