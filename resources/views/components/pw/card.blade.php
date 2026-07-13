@props(['padding' => true])
<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-card '.($padding ? 'p-5 sm:p-6' : '')]) }}>{{ $slot }}</div>
