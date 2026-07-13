@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'button'])
@php
$base = 'inline-flex items-center justify-center gap-2 rounded-lg font-semibold transition pw-focus disabled:cursor-not-allowed disabled:opacity-50';
$sizes = ['sm'=>'px-3 py-2 text-xs','md'=>'px-4 py-2.5 text-sm','lg'=>'px-5 py-3 text-base'];
$variants = [
 'primary'=>'bg-brand-600 text-white shadow-sm hover:bg-brand-700',
 'secondary'=>'border border-slate-300 bg-white text-slate-700 shadow-sm hover:bg-slate-50',
 'success'=>'bg-emerald-600 text-white shadow-sm hover:bg-emerald-700',
 'danger'=>'bg-red-600 text-white shadow-sm hover:bg-red-700',
 'ghost'=>'text-slate-600 hover:bg-slate-100 hover:text-slate-900',
];
$classes = "$base {$sizes[$size]} {$variants[$variant]}";
@endphp
@if($href)<a href="{{ $href }}" {{ $attributes->merge(['class'=>$classes]) }}>{{ $slot }}</a>
@else<button type="{{ $type }}" {{ $attributes->merge(['class'=>$classes]) }}>{{ $slot }}</button>@endif
