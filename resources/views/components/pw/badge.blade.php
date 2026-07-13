@props(['variant'=>'neutral'])
@php $v=['neutral'=>'bg-slate-100 text-slate-700','success'=>'bg-emerald-100 text-emerald-700','warning'=>'bg-amber-100 text-amber-800','danger'=>'bg-red-100 text-red-700','info'=>'bg-brand-100 text-brand-700']; @endphp
<span {{ $attributes->merge(['class'=>'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold '.$v[$variant]]) }}>{{ $slot }}</span>
