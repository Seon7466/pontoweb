<div {{ $attributes->merge([
    'class' => 'mb-5 flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm lg:flex-row lg:items-center lg:justify-between',
]) }}>
    <div class="min-w-0 flex-1">{{ $slot }}</div>

    @if (trim((string) ($actions ?? '')) !== '')
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endif
</div>
