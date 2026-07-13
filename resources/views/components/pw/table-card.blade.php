@props(['title'=>null,'description'=>null])
<x-pw.card :padding="false">
 @if($title || $description || trim($actions ?? ''))<div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><div>@if($title)<h2 class="font-semibold text-slate-900">{{ $title }}</h2>@endif @if($description)<p class="mt-1 text-sm text-slate-500">{{ $description }}</p>@endif</div><div>{{ $actions ?? '' }}</div></div>@endif
 <div class="overflow-x-auto">{{ $slot }}</div>
 @if(trim($footer ?? ''))<div class="border-t border-slate-200 px-5 py-4">{{ $footer }}</div>@endif
</x-pw.card>
