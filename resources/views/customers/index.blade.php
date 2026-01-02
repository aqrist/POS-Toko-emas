<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
                {{ __('Customer') }}
            </h2>
            @can('create', \App\Models\Customer::class)
                <a href="{{ route('customers.create') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    Tambah Customer
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
                                        <th class="py-2">Nama</th>
                                        <th class="py-2">Telepon</th>
                                        <th class="py-2">Alamat</th>
                                        <th class="py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td class="py-2">{{ $customer->name }}</td>
                                            <td class="py-2">{{ $customer->phone ?? '-' }}</td>
                                            <td class="py-2">{{ $customer->address ?? '-' }}</td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-3">
                                                    @can('update', $customer)
                                                        <a href="{{ route('customers.edit', $customer) }}" class="text-sm text-blue-600">Edit</a>
                                                    @endcan
                                                    @can('delete', $customer)
                                                        <form method="POST" action="{{ route('customers.destroy', $customer) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600" onclick="return confirm('Hapus customer ini?')">Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada data customer.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $customers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
