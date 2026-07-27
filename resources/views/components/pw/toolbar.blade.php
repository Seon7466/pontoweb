<div {{ $attributes->merge([
    'class' => 'mb-5 flex flex-col gap-3 rounded-2xl border border-slate-200/90 bg-white p-3.5 shadow-sm lg:flex-row lg:items-center lg:justify-between',
]) }}>
    <div class="min-w-0 flex-1">
        {{ $slot }}
    </div>

    @if (trim((string) ($actions ?? '')) !== '')
        <div class="flex shrink-0 flex-wrap items-center gap-2 border-t border-slate-100 pt-3 lg:border-l lg:border-t-0 lg:pl-3 lg:pt-0">
            {{ $actions }}
        </div>
    @endif
</div>
