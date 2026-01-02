<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
            {{ __('Laporan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form method="GET" action="{{ route('reports.index') }}" class="grid gap-4 sm:grid-cols-4">
                            @if (auth()->user()->isSuperAdmin())
                                <div>
                                    <x-input-label for="branch_id" value="Cabang" />
                                    <select id="branch_id" name="branch_id" class="mt-1 block w-full rounded-md border-gray-300">
                                        <option value="">Semua cabang</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ request('branch_id') === $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div>
                                <x-input-label for="type" value="Tipe" />
                                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Semua tipe</option>
                                    <option value="buy" {{ request('type') === 'buy' ? 'selected' : '' }}>Beli</option>
                                    <option value="sell" {{ request('type') === 'sell' ? 'selected' : '' }}>Jual</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="from" value="Dari Tanggal" />
                                <x-text-input id="from" name="from" type="date" class="mt-1 block w-full" value="{{ request('from') }}" />
                            </div>

                            <div>
                                <x-input-label for="to" value="Sampai Tanggal" />
                                <x-text-input id="to" name="to" type="date" class="mt-1 block w-full" value="{{ request('to') }}" />
                            </div>

                            <div class="sm:col-span-4 flex items-center gap-3">
                                <x-primary-button>Terapkan Filter</x-primary-button>
                                <a href="{{ route('reports.index') }}" class="text-sm text-gray-600">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="text-sm text-gray-500">Total Transaksi</div>
                            <div class="text-2xl font-semibold">{{ $summary['count'] }}</div>
                        </div>
                    </div>
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="text-sm text-gray-500">Total Nilai</div>
                            <div class="text-2xl font-semibold">Rp {{ number_format($summary['total'], 2, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="border-b text-left">
                                        <th class="py-2">Tanggal</th>
                                        <th class="py-2">Cabang</th>
                                        <th class="py-2">Kasir</th>
                                        <th class="py-2">Customer</th>
                                        <th class="py-2">Tipe</th>
                                        <th class="py-2">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($transactions as $transaction)
                                        <tr>
                                            <td class="py-2">{{ $transaction->occurred_at->format('Y-m-d H:i') }}</td>
                                            <td class="py-2">{{ $transaction->branch?->name ?? '-' }}</td>
                                            <td class="py-2">{{ $transaction->user?->name ?? '-' }}</td>
                                            <td class="py-2">{{ $transaction->customer?->name ?? '-' }}</td>
                                            <td class="py-2">{{ $transaction->type === 'buy' ? 'Beli' : 'Jual' }}</td>
                                            <td class="py-2">Rp {{ number_format($transaction->total, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-6 text-center text-gray-500">Belum ada data laporan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
