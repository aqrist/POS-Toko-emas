<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
                {{ __('Kasir Offline') }}
            </h2>
            <div class="flex items-center gap-3">
                <span id="offline-status" class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">Checking...</span>
                <button type="button" id="sync-now" class="inline-flex items-center rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">
                    Sync Sekarang
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6" id="offline-cashier" data-market-price="{{ $marketPrice }}">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div id="offline-message" class="hidden rounded-md border px-4 py-3 text-sm"></div>
                            <form id="offline-transaction-form" class="flex flex-col gap-5">
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <x-input-label for="offline_type" value="Tipe Transaksi" />
                                        <select id="offline_type" name="type" class="mt-1 block w-full rounded-md border-gray-300" required>
                                            <option value="">Pilih tipe</option>
                                            <option value="sell">Jual</option>
                                            <option value="buy">Beli</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="offline_payment" value="Metode Pembayaran" />
                                        <select id="offline_payment" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300" required>
                                            <option value="">Pilih metode</option>
                                            <option value="cash">Cash</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="offline_occurred_at" value="Waktu Transaksi" />
                                        <x-text-input id="offline_occurred_at" name="occurred_at" type="datetime-local" class="mt-1 block w-full" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="offline_additional_fee" value="Biaya Tambahan" />
                                        <x-text-input id="offline_additional_fee" name="additional_fee" type="number" step="0.01" class="mt-1 block w-full" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="offline_notes" value="Catatan" />
                                    <x-text-input id="offline_notes" name="notes" type="text" class="mt-1 block w-full" />
                                </div>

                                <div class="flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold">Item</h3>
                                        <button type="button" id="offline-add-item" class="text-sm text-blue-600">Tambah Item</button>
                                    </div>
                                    <div class="flex flex-col gap-4" id="offline-items">
                                        <div class="grid gap-3 rounded-md border border-gray-200 p-4 sm:grid-cols-6" data-offline-item>
                                            <div class="sm:col-span-2">
                                                <x-input-label value="Kadar" />
                                                <select name="gold_level_id" class="mt-1 block w-full rounded-md border-gray-300" required>
                                                    <option value="">Pilih kadar</option>
                                                    @foreach ($goldLevels as $goldLevel)
                                                        <option value="{{ $goldLevel->id }}" data-percentage="{{ $goldLevel->percentage }}">{{ $goldLevel->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label value="Produk" />
                                                <select name="product_type" class="mt-1 block w-full rounded-md border-gray-300" required>
                                                    <option value="jewelry">Perhiasan</option>
                                                    <option value="bullion">Batangan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label value="Berat (gr)" />
                                                <x-text-input name="weight" type="number" step="0.001" class="mt-1 block w-full" required />
                                            </div>
                                            <div>
                                                <x-input-label value="Harga/gram" />
                                                <x-text-input name="price_per_gram" type="number" step="0.01" class="mt-1 block w-full" required />
                                            </div>
                                            <div>
                                                <x-input-label value="Total" />
                                                <div class="mt-3 text-sm font-semibold text-gray-900" data-line-total>Rp 0,00</div>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" class="text-sm text-red-600" data-remove-item>Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-primary-button type="submit">Simpan Offline</x-primary-button>
                                    <span class="text-sm text-gray-500">Transaksi akan disinkronkan saat online.</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex flex-col gap-4">
                        <div class="bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-semibold">Ringkasan</h3>
                                </div>
                                <div class="mt-4 flex flex-col gap-3 text-sm text-gray-600">
                                    <div class="flex items-center justify-between">
                                        <span>Subtotal</span>
                                        <span id="offline-subtotal">Rp 0,00</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span>Biaya Tambahan</span>
                                        <span id="offline-additional-fee">Rp 0,00</span>
                                    </div>
                                    <div class="flex items-center justify-between border-t border-gray-200 pt-3 text-base font-semibold text-gray-900">
                                        <span>Total</span>
                                        <span id="offline-total">Rp 0,00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-semibold">Antrian Sync</h3>
                                    <span id="queue-count" class="text-xs text-gray-500">0 transaksi</span>
                                </div>
                                <div id="offline-queue" class="mt-4 flex flex-col gap-3 text-sm text-gray-600">
                                    <div class="rounded-md border border-dashed border-gray-200 p-3 text-center text-gray-500">
                                        Belum ada transaksi offline.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <template id="offline-item-template">
        <div class="grid gap-3 rounded-md border border-gray-200 p-4 sm:grid-cols-6" data-offline-item>
            <div class="sm:col-span-2">
                <x-input-label value="Kadar" />
                <select name="gold_level_id" class="mt-1 block w-full rounded-md border-gray-300" required>
                    <option value="">Pilih kadar</option>
                    @foreach ($goldLevels as $goldLevel)
                        <option value="{{ $goldLevel->id }}" data-percentage="{{ $goldLevel->percentage }}">{{ $goldLevel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label value="Produk" />
                <select name="product_type" class="mt-1 block w-full rounded-md border-gray-300" required>
                    <option value="jewelry">Perhiasan</option>
                    <option value="bullion">Batangan</option>
                </select>
            </div>
            <div>
                <x-input-label value="Berat (gr)" />
                <x-text-input name="weight" type="number" step="0.001" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label value="Harga/gram" />
                <x-text-input name="price_per_gram" type="number" step="0.01" class="mt-1 block w-full" required />
            </div>
            <div>
                <x-input-label value="Total" />
                <div class="mt-3 text-sm font-semibold text-gray-900" data-line-total>Rp 0,00</div>
            </div>
            <div class="flex items-end">
                <button type="button" class="text-sm text-red-600" data-remove-item>Hapus</button>
            </div>
        </div>
    </template>
</x-app-layout>
