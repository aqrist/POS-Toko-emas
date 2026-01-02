@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <div class="mx-auto w-full max-w-md">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white/90 shadow-2xl shadow-slate-900/10 backdrop-blur">
            <div class="px-8 pt-8 pb-6">
                <div class="flex flex-col gap-2">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">POS Terminal Login</h1>
                    <p class="text-sm text-slate-500">
                        Masuk untuk memulai shift kasir dan akses transaksi.
                    </p>
                </div>
            </div>

            <div class="px-8 pb-8">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
                    @csrf

                    <div class="flex flex-col gap-2">
                        <x-input-label for="email" :value="__('Email')" class="text-sm text-slate-700" />
                        <div class="relative">
                            <x-text-input
                                id="email"
                                class="mt-1 block w-full rounded-lg border-slate-200 bg-white/70 px-10 py-3 text-sm text-slate-900 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="admin@tokoemas.com"
                            />
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" />
                                    <path d="M4 20a8 8 0 0 1 16 0" stroke-linecap="round" />
                                </svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <x-input-label for="password" :value="__('Password')" class="text-sm text-slate-700" />
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-amber-600 hover:text-amber-500" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <x-text-input
                                id="password"
                                class="mt-1 block w-full rounded-lg border-slate-200 bg-white/70 px-10 py-3 text-sm text-slate-900 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="********"
                            />
                            <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M6 10h12v10H6z" />
                                    <path d="M8 10V7a4 4 0 1 1 8 0v3" />
                                </svg>
                            </span>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-amber-500 focus:ring-amber-500" name="remember">
                            <span>{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <x-primary-button class="mt-2 w-full justify-center rounded-lg bg-amber-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/20 hover:bg-amber-400 focus:ring-amber-500">
                        {{ __('Log in') }}
                    </x-primary-button>
                </form>
            </div>

            <div class="border-t border-slate-100 bg-slate-50 px-8 py-4 text-center text-xs text-slate-500">
                Butuh bantuan? Hubungi <span class="font-semibold text-amber-600">Support</span>
            </div>
        </div>

        <div class="mt-6 flex justify-center sm:hidden">
            <button type="button" class="flex items-center gap-2 text-sm text-slate-400">
                <span>Tambah ke Home Screen</span>
            </button>
        </div>
    </div>
@endsection
