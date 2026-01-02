<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @can('viewAny', \App\Models\Branch::class)
                    <a href="{{ route('branches.index') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Manajemen</div>
                        <div class="text-lg font-semibold text-gray-900">Cabang</div>
                    </a>
                @endcan
                @can('viewAny', \App\Models\GoldLevel::class)
                    <a href="{{ route('gold-levels.index') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Master</div>
                        <div class="text-lg font-semibold text-gray-900">Kadar Emas</div>
                    </a>
                @endcan
                @can('viewAny', \App\Models\GoldPrice::class)
                    <a href="{{ route('gold-prices.index') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Setup</div>
                        <div class="text-lg font-semibold text-gray-900">Harga Emas</div>
                    </a>
                @endcan
                @can('viewAny', \App\Models\Customer::class)
                    <a href="{{ route('customers.index') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Data</div>
                        <div class="text-lg font-semibold text-gray-900">Customer</div>
                    </a>
                @endcan
                @can('viewAny', \App\Models\Transaction::class)
                    <a href="{{ route('transactions.index') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Operasional</div>
                        <div class="text-lg font-semibold text-gray-900">Transaksi</div>
                    </a>
                @endcan
                @can('view-reports')
                    <a href="{{ route('reports.index') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="text-sm text-gray-500">Monitoring</div>
                        <div class="text-lg font-semibold text-gray-900">Laporan</div>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
