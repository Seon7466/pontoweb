<!DOCTYPE html><html lang="{{ str_replace('_','-',app()->getLocale()) }}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>{{ config('app.name','PontoWeb') }}</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="bg-slate-50" x-data="{ sidebarOpen:false, sidebarCollapsed:false }">
<div class="min-h-screen lg:flex">
 <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden" @click="sidebarOpen=false"></div>
 @include('layouts.sidebar')
 <div class="min-w-0 flex-1 lg:pl-72" :class="sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-72'">
  @include('layouts.app.topbar')
  <main class="px-4 py-6 sm:px-6 lg:px-8"><div class="mx-auto max-w-screen-2xl"><x-pw.flash /><div class="mt-4">{{ $slot }}</div></div></main>
  @include('layouts.app.footer')
 </div>
</div></body></html>
