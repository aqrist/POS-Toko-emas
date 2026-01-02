<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cabang') }}
            </h2>
            @can('create', \App\Models\Branch::class)
                <a href="{{ route('branches.create') }}" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    Tambah Cabang
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
                                        <th class="py-2">Kode</th>
                                        <th class="py-2">Status</th>
                                        <th class="py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @forelse ($branches as $branch)
                                        <tr>
                                            <td class="py-2">{{ $branch->name }}</td>
                                            <td class="py-2">{{ $branch->code }}</td>
                                            <td class="py-2">
                                                {{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </td>
                                            <td class="py-2">
                                                <div class="flex items-center gap-3">
                                                    @can('update', $branch)
                                                        <a href="{{ route('branches.edit', $branch) }}" class="text-sm text-blue-600">Edit</a>
                                                    @endcan
                                                    @can('delete', $branch)
                                                        <form method="POST" action="{{ route('branches.destroy', $branch) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-sm text-red-600" onclick="return confirm('Hapus cabang ini?')">Hapus</button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada data cabang.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $branches->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
