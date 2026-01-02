<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
            {{ __('Offline') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col gap-4">
                        <div class="text-lg font-semibold">Koneksi internet tidak tersedia.</div>
                        <div class="text-sm text-gray-600">Aplikasi tetap bisa digunakan untuk transaksi kasir secara offline.</div>
                        <a href="{{ route('cashier.offline') }}" class="inline-flex w-fit items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                            Buka Mode Kasir Offline
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
