<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
            {{ __('Edit Kadar Emas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('gold-levels.update', $goldLevel) }}" class="flex flex-col gap-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" value="Nama Kadar" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $goldLevel->name) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="percentage" value="Persentase" />
                            <x-text-input id="percentage" name="percentage" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('percentage', $goldLevel->percentage) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('percentage')" />
                        </div>

                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $goldLevel->is_active) ? 'checked' : '' }}>
                            <span>Aktif</span>
                        </label>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('gold-levels.index') }}" class="text-sm text-gray-600">Batal</a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
