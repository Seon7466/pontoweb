@props([
    'title',
    'description' => null,
])

<div class="mb-6 flex flex-col gap-4 border-b border-slate-200/80 pb-5 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        <div class="mb-2 flex items-center gap-2">
            <span class="h-1.5 w-8 rounded-full bg-blue-600"></span>
            <span class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-blue-600">PontoWeb</span>
        </div>

        <h1 class="text-2xl font-extrabold tracking-tight text-slate-950 sm:text-[1.75rem]">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="mt-1.5 max-w-3xl text-sm leading-6 text-slate-500">
                {{ $description }}
            </p>
        @endif
    </div>

    @if (trim((string) ($actions ?? '')) !== '')
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
