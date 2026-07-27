@props(['title' => null, 'description' => null])

<x-pw.card :padding="false" {{ $attributes }}>
    @if ($title || $description || trim((string) ($actions ?? '')) !== '')
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                @if ($title)
                    <h2 class="font-semibold text-slate-900">{{ $title }}</h2>
                @endif

                @if ($description)
                    <p class="mt-1 text-sm text-slate-500">{{ $description }}</p>
                @endif
            </div>

            @if (trim((string) ($actions ?? '')) !== '')
                <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif

    <div class="overflow-x-auto">{{ $slot }}</div>

    @if (trim((string) ($footer ?? '')) !== '')
        <div class="border-t border-slate-200 bg-slate-50/60 px-5 py-4">{{ $footer }}</div>
    @endif
</x-pw.card>
