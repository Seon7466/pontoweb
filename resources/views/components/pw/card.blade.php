@props(['padding' => true])

<div {{ $attributes->merge([
    'class' => 'rounded-2xl border border-slate-200/90 bg-white shadow-sm transition-shadow duration-200 '.($padding ? 'p-5 sm:p-6' : ''),
]) }}>
    {{ $slot }}
</div>
