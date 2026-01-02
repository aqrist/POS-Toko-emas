<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#f8fafc">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased" style="font-family: 'Manrope', sans-serif;">
        <div class="min-h-screen">
            <div class="flex min-h-screen">
                @auth
                    <aside id="gold-sidebar" class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-out lg:static lg:translate-x-0">
                        <div class="flex h-full flex-col justify-between p-5">
                            <div class="flex flex-col gap-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6">
                                            <path d="M7 3h10l4 6-9 12-9-12 4-6Z" stroke-linejoin="round" />
                                            <path d="M7 3l5 6 5-6" stroke-linejoin="round" />
                                            <path d="M3 9h18" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h1 class="text-base font-semibold leading-tight">POS Toko Emas</h1>
                                        <p class="text-xs text-slate-500">
                                            {{ Auth::user()->branch?->name ?? 'Semua Cabang' }}
                                        </p>
                                    </div>
                                </div>

                                <nav class="flex flex-col gap-1 text-sm font-medium text-slate-600">
                                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                        Dashboard
                                    </a>
                                    @can('viewAny', \App\Models\Branch::class)
                                        <a href="{{ route('branches.index') }}" class="{{ request()->routeIs('branches.*') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Cabang
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\GoldLevel::class)
                                        <a href="{{ route('gold-levels.index') }}" class="{{ request()->routeIs('gold-levels.*') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Kadar
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\GoldPrice::class)
                                        <a href="{{ route('gold-prices.index') }}" class="{{ request()->routeIs('gold-prices.*') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Harga
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\Customer::class)
                                        <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.*') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Customer
                                        </a>
                                    @endcan
                                    @can('viewAny', \App\Models\Transaction::class)
                                        <a href="{{ route('transactions.index') }}" class="{{ request()->routeIs('transactions.*') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Transaksi
                                        </a>
                                        <a href="{{ route('cashier.offline') }}" class="{{ request()->routeIs('cashier.offline') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Kasir Offline
                                        </a>
                                    @endcan
                                    @can('view-reports')
                                        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') ? 'bg-amber-50 text-amber-700' : 'hover:bg-slate-100' }} rounded-lg px-3 py-2">
                                            Laporan
                                        </a>
                                    @endcan
                                </nav>
                            </div>

                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-500/10 text-xs font-semibold text-amber-600">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</span>
                                    <span class="text-xs text-slate-500">{{ Auth::user()->role?->name ?? 'User' }}</span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </aside>

                    <div class="flex min-w-0 flex-1 flex-col">
                        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
                            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                                <div class="flex flex-col gap-1">
                                    {{ $header ?? '' }}
                                    <p class="text-sm text-slate-500">Operasional harian kasir dan manajemen data.</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" id="gold-sidebar-toggle" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 lg:hidden" aria-expanded="false">
                                        Menu
                                    </button>
                                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        <span class="relative flex h-2 w-2">
                                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                        </span>
                                        Offline Ready
                                    </span>
                                </div>
                            </div>
                        </header>

                        <main class="relative flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                            <div class="pointer-events-none absolute inset-0 -z-10">
                                <div class="absolute -top-24 right-0 h-72 w-72 rounded-full bg-amber-200/40 blur-[120px]"></div>
                                <div class="absolute -bottom-24 left-0 h-80 w-80 rounded-full bg-sky-200/40 blur-[120px]"></div>
                            </div>
                            {{ $slot }}
                        </main>
                    </div>
                @endauth

                @guest
                    <div class="flex w-full flex-col">
                        <header class="w-full border-b border-slate-200 bg-white/90 backdrop-blur">
                            <div class="mx-auto flex max-w-6xl items-center gap-3 px-4 py-4 sm:px-6">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6">
                                        <path d="M7 3h10l4 6-9 12-9-12 4-6Z" stroke-linejoin="round" />
                                        <path d="M7 3l5 6 5-6" stroke-linejoin="round" />
                                        <path d="M3 9h18" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-base font-semibold leading-tight">POS Toko Emas</h1>
                                    <p class="text-xs text-slate-500">Operasional kasir modern.</p>
                                </div>
                            </div>
                        </header>

                        <main class="relative flex flex-1 items-center justify-center px-4 py-10 sm:px-6">
                            <div class="pointer-events-none absolute inset-0 -z-10">
                                <div class="absolute -top-24 right-0 h-72 w-72 rounded-full bg-amber-200/40 blur-[120px]"></div>
                                <div class="absolute -bottom-24 left-0 h-80 w-80 rounded-full bg-sky-200/40 blur-[120px]"></div>
                            </div>
                            {{ $slot }}
                        </main>
                    </div>
                @endguest
            </div>
        </div>

        @auth
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const toggle = document.getElementById('gold-sidebar-toggle');
                    const sidebar = document.getElementById('gold-sidebar');
                    if (!toggle || !sidebar) {
                        return;
                    }

                    toggle.addEventListener('click', () => {
                        sidebar.classList.toggle('-translate-x-full');
                        toggle.setAttribute(
                            'aria-expanded',
                            String(!sidebar.classList.contains('-translate-x-full'))
                        );
                    });
                });
            </script>
        @endauth
    </body>
</html>
