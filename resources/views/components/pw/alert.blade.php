@props([
    'type' => null,
    'variant' => null,
    'title' => null,
])

@php
    $selectedType = $variant ?? $type ?? 'info';

    $styles = [
        'success' => [
            'box' => 'border-emerald-200 bg-emerald-50/90 text-emerald-900',
            'icon' => 'bg-emerald-100 text-emerald-700',
            'path' => 'M9 12.75 11.25 15 15 9.75',
        ],
        'error' => [
            'box' => 'border-red-200 bg-red-50/90 text-red-900',
            'icon' => 'bg-red-100 text-red-700',
            'path' => 'M9.75 9.75 14.25 14.25M14.25 9.75 9.75 14.25',
        ],
        'danger' => [
            'box' => 'border-red-200 bg-red-50/90 text-red-900',
            'icon' => 'bg-red-100 text-red-700',
            'path' => 'M9.75 9.75 14.25 14.25M14.25 9.75 9.75 14.25',
        ],
        'warning' => [
            'box' => 'border-amber-200 bg-amber-50/90 text-amber-900',
            'icon' => 'bg-amber-100 text-amber-700',
            'path' => 'M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.052 3.376c.866-1.5 3.03-1.5 3.896 0l7.355 12.75ZM12 15.75h.008v.008H12v-.008Z',
        ],
        'info' => [
            'box' => 'border-blue-200 bg-blue-50/90 text-blue-900',
            'icon' => 'bg-blue-100 text-blue-700',
            'path' => 'M11.25 11.25 12 10.5m0 0 .75.75M12 10.5v4.125m9-2.625a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        ],
    ];

    $style = $styles[$selectedType] ?? $styles['info'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-xl border px-4 py-3.5 text-sm shadow-sm {$style['box']}"]) }}>
    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $style['icon'] }}">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $style['path'] }}" />
        </svg>
    </span>

    <div class="min-w-0 flex-1 leading-6">
        @if ($title)
            <p class="font-bold">{{ $title }}</p>
        @endif

        <div @class(['mt-0.5' => $title])>
            {{ $slot }}
        </div>
    </div>
</div>
