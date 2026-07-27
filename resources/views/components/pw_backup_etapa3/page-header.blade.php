@props(['title', 'description' => null])

<div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>

        @if ($description)
            <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500">{{ $description }}</p>
        @endif
    </div>

    @if (trim((string) ($actions ?? '')) !== '')
        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endif
</div>
