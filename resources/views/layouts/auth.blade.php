<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f172a">

        <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Auth')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased" style="font-family: 'Manrope', sans-serif;">
        <div class="min-h-screen flex flex-col">
            <header class="w-full border-b border-slate-200 bg-white/80 backdrop-blur">
                <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path d="M7 3h10l4 6-9 12-9-12 4-6Z" stroke-linejoin="round" />
                                <path d="M7 3l5 6 5-6" stroke-linejoin="round" />
                                <path d="M3 9h18" stroke-linecap="round" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-semibold leading-tight">POS Toko Emas</div>
                            <div class="text-xs text-slate-500">Offline Ready</div>
                        </div>
                    </div>
                    <div class="hidden items-center gap-3 sm:flex">
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            </span>
                            Offline Ready
                        </span>
                        <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                            <span>Install App</span>
                        </button>
                    </div>
                </div>
            </header>

            <main class="relative flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
                <div class="pointer-events-none absolute inset-0 overflow-hidden">
                    <div class="absolute -top-24 right-0 h-72 w-72 rounded-full bg-amber-200/40 blur-[120px]"></div>
                    <div class="absolute -bottom-20 left-0 h-80 w-80 rounded-full bg-sky-200/40 blur-[120px]"></div>
                </div>

                <div class="relative z-10 w-full">
                    @yield('content')
                </div>
            </main>

            <footer class="py-6 text-center text-xs text-slate-500">
                (c) {{ date('Y') }} POS Toko Emas. All rights reserved.
            </footer>
        </div>
    </body>
</html>
