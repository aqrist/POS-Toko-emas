<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Transaksi') }}
        </h2>
    </x-slot>

    @php
        $items = old('items', [
            ['gold_level_id' => '', 'product_type' => 'jewelry', 'weight' => '', 'price_per_gram' => ''],
        ]);
    @endphp

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('transactions.store') }}" class="flex flex-col gap-6" id="transaction-form">
                        @csrf

                        @if (auth()->user()->isSuperAdmin())
                            <div>
                                <x-input-label for="branch_id" value="Cabang" />
                                <select id="branch_id" name="branch_id" class="mt-1 block w-full rounded-md border-gray-300" required>
                                    <option value="">Pilih cabang</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') === $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('branch_id')" />
                            </div>
                        @endif

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="type" value="Tipe Transaksi" />
                                <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300" required>
                                    <option value="">Pilih tipe</option>
                                    <option value="buy" {{ old('type') === 'buy' ? 'selected' : '' }}>Beli</option>
                                    <option value="sell" {{ old('type') === 'sell' ? 'selected' : '' }}>Jual</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                            <div>
                                <x-input-label for="payment_method" value="Metode Pembayaran" />
                                <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300" required>
                                    <option value="">Pilih metode</option>
                                    <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="transfer" {{ old('payment_method') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
                            </div>

                            <div>
                                <x-input-label for="occurred_at" value="Waktu Transaksi" />
                                <x-text-input id="occurred_at" name="occurred_at" type="datetime-local" class="mt-1 block w-full" value="{{ old('occurred_at', now()->format('Y-m-d\TH:i')) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('occurred_at')" />
                            </div>

                            <div>
                                <x-input-label for="customer_id" value="Customer (opsional)" />
                                <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="">Tanpa customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') === $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
                            </div>

                            <div>
                                <x-input-label for="additional_fee" value="Biaya Tambahan (opsional)" />
                                <x-text-input id="additional_fee" name="additional_fee" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('additional_fee') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('additional_fee')" />
                            </div>

                            <div>
                                <x-input-label for="notes" value="Catatan" />
                                <x-text-input id="notes" name="notes" type="text" class="mt-1 block w-full" value="{{ old('notes') }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-semibold">Item</h3>
                                <button type="button" id="add-item" class="text-sm text-blue-600">Tambah Item</button>
                            </div>

                            <div class="flex flex-col gap-4" id="items-wrapper">
                                @foreach ($items as $index => $item)
                                    <div class="grid gap-3 rounded-md border border-gray-200 p-4 sm:grid-cols-5" data-item-row>
                                        <div class="sm:col-span-2">
                                            <x-input-label :for="'items_'.$index.'_gold_level_id'" value="Kadar" />
                                            <select id="items_{{ $index }}_gold_level_id" name="items[{{ $index }}][gold_level_id]" class="mt-1 block w-full rounded-md border-gray-300" required>
                                                <option value="">Pilih kadar</option>
                                                @foreach ($goldLevels as $goldLevel)
                                                    <option value="{{ $goldLevel->id }}" {{ ($item['gold_level_id'] ?? '') === $goldLevel->id ? 'selected' : '' }}>
                                                        {{ $goldLevel->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('items.'.$index.'.gold_level_id')" />
                                        </div>

                                        <div>
                                            <x-input-label :for="'items_'.$index.'_product_type'" value="Jenis Produk" />
                                            <select id="items_{{ $index }}_product_type" name="items[{{ $index }}][product_type]" class="mt-1 block w-full rounded-md border-gray-300" required>
                                                <option value="bullion" {{ ($item['product_type'] ?? '') === 'bullion' ? 'selected' : '' }}>Batangan</option>
                                                <option value="jewelry" {{ ($item['product_type'] ?? '') === 'jewelry' ? 'selected' : '' }}>Perhiasan</option>
                                            </select>
                                            <x-input-error class="mt-2" :messages="$errors->get('items.'.$index.'.product_type')" />
                                        </div>

                                        <div>
                                            <x-input-label :for="'items_'.$index.'_weight'" value="Berat (gr)" />
                                            <x-text-input id="items_{{ $index }}_weight" name="items[{{ $index }}][weight]" type="number" step="0.001" class="mt-1 block w-full" value="{{ $item['weight'] ?? '' }}" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('items.'.$index.'.weight')" />
                                        </div>

                                        <div>
                                            <x-input-label :for="'items_'.$index.'_price_per_gram'" value="Harga/gram" />
                                            <x-text-input id="items_{{ $index }}_price_per_gram" name="items[{{ $index }}][price_per_gram]" type="number" step="0.01" class="mt-1 block w-full" value="{{ $item['price_per_gram'] ?? '' }}" required />
                                            <x-input-error class="mt-2" :messages="$errors->get('items.'.$index.'.price_per_gram')" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('transactions.index') }}" class="text-sm text-gray-600">Batal</a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <template id="item-template">
        <div class="grid gap-3 rounded-md border border-gray-200 p-4 sm:grid-cols-5" data-item-row>
            <div class="sm:col-span-2">
                <x-input-label value="Kadar" />
                <select name="items[__INDEX__][gold_level_id]" class="mt-1 block w-full rounded-md border-gray-300" required>
                    <option value="">Pilih kadar</option>
                    @foreach ($goldLevels as $goldLevel)
                        <option value="{{ $goldLevel->id }}">{{ $goldLevel->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label value="Jenis Produk" />
                <select name="items[__INDEX__][product_type]" class="mt-1 block w-full rounded-md border-gray-300" required>
                    <option value="bullion">Batangan</option>
                    <option value="jewelry">Perhiasan</option>
                </select>
            </div>

            <div>
                <x-input-label value="Berat (gr)" />
                <x-text-input name="items[__INDEX__][weight]" type="number" step="0.001" class="mt-1 block w-full" required />
            </div>

            <div>
                <x-input-label value="Harga/gram" />
                <x-text-input name="items[__INDEX__][price_per_gram]" type="number" step="0.01" class="mt-1 block w-full" required />
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addButton = document.getElementById('add-item');
            const itemsWrapper = document.getElementById('items-wrapper');
            const template = document.getElementById('item-template');

            addButton?.addEventListener('click', () => {
                const index = itemsWrapper.querySelectorAll('[data-item-row]').length;
                const html = template.innerHTML.replaceAll('__INDEX__', index);
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html.trim();
                itemsWrapper.appendChild(wrapper.firstElementChild);
            });
        });
    </script>
</x-app-layout>
