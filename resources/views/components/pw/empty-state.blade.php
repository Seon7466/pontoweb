@props([
    'title' => 'Nenhum registro encontrado',
    'description' => null,
])

<div class="px-6 py-14 text-center">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 ring-1 ring-blue-100">
        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v7m14 0h2l-2 7H6l-2-7h2m12 0h-3a3 3 0 0 1-6 0H6" />
        </svg>
    </div>

    <h3 class="mt-4 text-base font-bold text-slate-900">{{ $title }}</h3>

    @if ($description)
        <p class="mx-auto mt-1.5 max-w-md text-sm leading-6 text-slate-500">{{ $description }}</p>
    @endif

    @if (trim((string) $slot) !== '' || trim((string) ($action ?? '')) !== '')
        <div class="mt-5 flex justify-center">
            {{ $action ?? $slot }}
        </div>
    @endif
</div>
