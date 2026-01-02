<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Cabang') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('branches.store') }}" class="flex flex-col gap-4">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Nama Cabang" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="code" value="Kode Cabang" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" value="{{ old('code') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('code')" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Nomor Telepon" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div>
                            <x-input-label for="address" value="Alamat" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" value="{{ old('address') }}" />
                            <x-input-error class="mt-2" :messages="$errors->get('address')" />
                        </div>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" class="rounded" checked>
                            <span>Aktif</span>
                        </label>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('branches.index') }}" class="text-sm text-gray-600">Batal</a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
