<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
                {{ __('Transaksi') }}
            </h2>
            @can('create', \App\Models\Transaction::class)
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    Tambah Transaksi
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
                                        <th class="py-2">Tipe</th>
                                        <th class="py-2">Customer</th>
                                        <th class="py-2">Total</th>
                                        <th class="py-2">Cabang</th>
                                        <th class="py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($transactions as $transaction)
                                        <tr>
                                            <td class="py-2">{{ $transaction->occurred_at->format('Y-m-d H:i') }}</td>
                                            <td class="py-2">{{ $transaction->type === 'buy' ? 'Beli' : 'Jual' }}</td>
                                            <td class="py-2">{{ $transaction->customer?->name ?? '-' }}</td>
                                            <td class="py-2">Rp {{ number_format($transaction->total, 2, ',', '.') }}</td>
                                            <td class="py-2">{{ $transaction->branch?->name ?? '-' }}</td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-3">
                                                    @can('update', $transaction)
                                                        <a href="{{ route('transactions.edit', $transaction) }}" class="text-sm text-blue-600">Edit</a>
                                                    @endcan
                                                    @can('delete', $transaction)
                                                        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600" onclick="return confirm('Hapus transaksi ini?')">Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-6 text-center text-gray-500">Belum ada data transaksi.</td>
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
