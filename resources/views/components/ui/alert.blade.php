@if (session('success'))
    <div class="mb-4 p-4 rounded bg-green-100 text-green-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-4 rounded bg-red-100 text-red-700">
        {{ session('error') }}
    </div>
@endif
