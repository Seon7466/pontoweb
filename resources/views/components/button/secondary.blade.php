<a
    {{ $attributes->merge([
        'class' => 'px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300'
    ]) }}
>
    {{ $slot }}
</a>
