<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Harga Emas') }}
            </h2>
            @can('create', \App\Models\GoldPrice::class)
                <a href="{{ route('gold-prices.create') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    Tambah Harga
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4">
                <x-auth-session-status :status="session('status')" />

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left">
                                        <th class="py-2">Tanggal</th>
                                        <th class="py-2">Harga Pasar</th>
                                        <th class="py-2">Cabang</th>
                                        <th class="py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($goldPrices as $goldPrice)
                                        <tr>
                                            <td class="py-2">{{ $goldPrice->effective_date->format('Y-m-d') }}</td>
                                            <td class="py-2">Rp {{ number_format($goldPrice->market_price, 2, ',', '.') }}</td>
                                            <td class="py-2">{{ $goldPrice->branch?->name ?? 'Global' }}</td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-3">
                                                    @can('update', $goldPrice)
                                                        <a href="{{ route('gold-prices.edit', $goldPrice) }}" class="text-sm text-blue-600">Edit</a>
                                                    @endcan
                                                    @can('delete', $goldPrice)
                                                        <form method="POST" action="{{ route('gold-prices.destroy', $goldPrice) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600" onclick="return confirm('Hapus harga ini?')">Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada data harga emas.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $goldPrices->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
