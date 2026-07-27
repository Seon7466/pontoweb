<div class="mb-4 space-y-3">
    @if (session('success'))
        <x-pw.alert type="success">{{ session('success') }}</x-pw.alert>
    @endif

    @if (session('error'))
        <x-pw.alert type="error">{{ session('error') }}</x-pw.alert>
    @endif

    @if (session('warning'))
        <x-pw.alert type="warning">{{ session('warning') }}</x-pw.alert>
    @endif

    @if ($errors->any())
        <x-pw.alert type="error" title="Revise os campos informados">
            <ul class="mt-1 list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-pw.alert>
    @endif
</div>
