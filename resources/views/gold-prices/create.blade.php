<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Harga Emas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('gold-prices.store') }}" class="flex flex-col gap-4">
                        @csrf

                        @if (auth()->user()->isSuperAdmin())
                            <div>
                                <x-input-label for="branch_id" value="Cabang (opsional untuk global)" />
                                <select id="branch_id" name="branch_id" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Global</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') === $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('branch_id')" />
                            </div>
                        @endif

                        <div>
                            <x-input-label for="market_price" value="Harga Pasar (per gram)" />
                            <x-text-input id="market_price" name="market_price" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('market_price') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('market_price')" />
                        </div>

                        <div>
                            <x-input-label for="effective_date" value="Tanggal Berlaku" />
                            <x-text-input id="effective_date" name="effective_date" type="date" class="mt-1 block w-full" value="{{ old('effective_date', now()->toDateString()) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('effective_date')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('gold-prices.index') }}" class="text-sm text-gray-600">Batal</a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
