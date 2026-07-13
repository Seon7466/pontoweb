<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PontoWeb') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="flex min-h-screen">
        @include('layouts.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="border-b border-gray-200 bg-white shadow-sm">
                <div class="flex min-h-16 items-center justify-between gap-4 px-6 py-4">
                    <div class="min-w-0 text-xl font-semibold text-gray-900">
                        @isset($header)
                            {{ $header }}
                        @else
                            Painel PontoWeb
                        @endisset
                    </div>

                    <div class="flex flex-shrink-0 items-center gap-4">
                        <span class="hidden text-sm text-gray-600 sm:inline">
                            {{ auth()->user()->name ?? 'Usuário' }}
                        </span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm font-medium text-red-600 transition hover:text-red-800">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
