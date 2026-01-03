<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold leading-tight tracking-tight text-slate-900">
            {{ __('Transaksi Offline') }}
        </h2>
    </x-slot>

    <div class="py-6" id="offline-cashier" data-market-price="{{ $marketPrice }}">
        <div class="mx-auto flex max-w-6xl flex-col gap-6 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-5 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kasir Offline</p>
                        <h1 id="offline-title" class="text-2xl font-bold text-slate-900">Sell Gold Transaction</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="offline-status" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Checking...</span>
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        </span>
                        Offline Ready
                        </span>
                        <button type="button" id="install-app" class="hidden inline-flex items-center rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-700">
                            Tambah ke Home
                        </button>
                        <button type="button" id="sync-now" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white">
                            Sync Sekarang
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                    <span>Mode offline aktif</span>
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span id="offline-mode-label">Jual Emas</span>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-12">
                <div class="lg:col-span-8 flex flex-col gap-6">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="rounded-xl bg-amber-500/10 p-2 text-amber-600">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6">
                                        <path d="M3 12h18" stroke-linecap="round" />
                                        <path d="M6 6h12M6 18h12" stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Gold Market Rate (24K)</p>
                                    <p class="text-lg font-bold text-slate-900 tabular-nums">Rp {{ number_format($marketPrice, 2, ',', '.') }} <span class="text-xs font-normal text-slate-500">/ gram</span></p>
                                </div>
                            </div>
                            <div class="text-xs text-slate-500">Terakhir diperbarui saat online</div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                            <div class="flex items-center gap-2 text-base font-semibold text-slate-900">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-amber-500/10 text-amber-600">+</span>
                                Detail Transaksi
                            </div>
                        </div>
                        <div class="p-6 text-slate-900">
                            <div id="offline-message" class="hidden rounded-md border px-4 py-3 text-sm"></div>
                            <form id="offline-transaction-form" class="flex flex-col gap-6">
                                <input type="hidden" name="type" id="offline_type" value="sell">

                                <div class="flex flex-col gap-3">
                                    <label class="text-sm font-medium text-slate-700">Mode Transaksi</label>
                                    <div class="grid grid-cols-2 gap-2 rounded-xl border border-slate-200 bg-white p-1 text-sm font-semibold">
                                        <button type="button" class="rounded-lg px-3 py-2 text-amber-700 bg-amber-50 shadow-sm ring-1 ring-amber-200" data-transaction-type="sell">Jual Emas</button>
                                        <button type="button" class="rounded-lg px-3 py-2 text-slate-500 hover:text-slate-700" data-transaction-type="buy">Beli Emas</button>
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <x-input-label for="offline_payment" value="Metode Pembayaran" class="text-slate-600" />
                                        <select id="offline_payment" name="payment_method" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-3 py-3 text-sm focus:border-amber-500 focus:ring-amber-500" required>
                                            <option value="">Pilih metode</option>
                                            <option value="cash">Cash</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                    </div>
                                    <div>
                                        <x-input-label for="offline_occurred_at" value="Waktu Transaksi" class="text-slate-600" />
                                        <x-text-input id="offline_occurred_at" name="occurred_at" type="datetime-local" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-3 text-sm focus:border-amber-500 focus:ring-amber-500" value="{{ now()->format('Y-m-d\TH:i') }}" required />
                                    </div>
                                    <div>
                                        <x-input-label for="offline_additional_fee" value="Biaya Tambahan" class="text-slate-600" />
                                        <x-text-input id="offline_additional_fee" name="additional_fee" type="number" step="0.01" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-3 text-sm focus:border-amber-500 focus:ring-amber-500" />
                                    </div>
                                    <div>
                                        <x-input-label for="offline_notes" value="Catatan" class="text-slate-600" />
                                        <x-text-input id="offline_notes" name="notes" type="text" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-3 text-sm focus:border-amber-500 focus:ring-amber-500" />
                                    </div>
                                </div>

                                <div class="flex flex-col gap-3">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-base font-semibold">Item Emas</h3>
                                        <button type="button" id="offline-add-item" class="text-sm font-semibold text-amber-600">Tambah Item</button>
                                    </div>
                                    <div class="flex flex-col gap-4" id="offline-items">
                                        <div class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-6" data-offline-item>
                                            <div class="sm:col-span-2">
                                                <x-input-label value="Kadar" class="text-slate-600" />
                                                <select name="gold_level_id" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required>
                                                    <option value="">Pilih kadar</option>
                                                    @foreach ($goldLevels as $goldLevel)
                                                        <option value="{{ $goldLevel->id }}" data-percentage="{{ $goldLevel->percentage }}">{{ $goldLevel->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label value="Produk" class="text-slate-600" />
                                                <select name="product_type" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required>
                                                    <option value="jewelry">Perhiasan</option>
                                                    <option value="bullion">Batangan</option>
                                                </select>
                                            </div>
                                            <div>
                                                <x-input-label value="Berat (gr)" class="text-slate-600" />
                                                <x-text-input name="weight" type="number" step="0.001" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required />
                                            </div>
                                            <div>
                                                <x-input-label value="Harga/gram" class="text-slate-600" />
                                                <x-text-input name="price_per_gram" type="number" step="0.01" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required />
                                            </div>
                                            <div>
                                                <x-input-label value="Total" class="text-slate-600" />
                                                <div class="mt-3 text-sm font-semibold text-slate-900" data-line-total>Rp 0,00</div>
                                            </div>
                                            <div class="flex items-end">
                                                <button type="button" class="text-sm font-semibold text-red-600" data-remove-item>Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-primary-button type="submit" class="rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/20 hover:bg-amber-400">
                                        Simpan Offline
                                    </x-primary-button>
                                    <span class="text-sm text-slate-500">Transaksi akan disinkronkan saat online.</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col gap-6">
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Ringkasan</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col gap-3 text-sm text-slate-600">
                                <div class="flex items-center justify-between">
                                    <span>Subtotal</span>
                                    <span id="offline-subtotal">Rp 0,00</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span>Biaya Tambahan</span>
                                    <span id="offline-additional-fee">Rp 0,00</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base font-semibold text-slate-900">
                                    <span>Total</span>
                                    <span id="offline-total">Rp 0,00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-700">Antrian Sync</h3>
                                <span id="queue-count" class="text-xs text-slate-500">0 transaksi</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div id="offline-queue" class="flex flex-col gap-3 text-sm text-slate-600">
                                <div class="rounded-md border border-dashed border-slate-200 p-3 text-center text-slate-500">
                                    Belum ada transaksi offline.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="confirm-offline-transaction" focusable>
        <div class="p-6">
            <h2 class="text-lg font-semibold text-slate-900">Konfirmasi Transaksi</h2>
            <p id="offline-confirm-message" class="mt-2 text-sm text-slate-600">
                Pastikan data transaksi sudah benar sebelum disimpan.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
                <x-primary-button type="button" id="offline-confirm-submit">
                    Konfirmasi & Simpan
                </x-primary-button>
            </div>
        </div>
    </x-modal>

    <template id="offline-item-template">
        <div class="grid gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:grid-cols-6" data-offline-item>
            <div class="sm:col-span-2">
                <x-input-label value="Kadar" class="text-slate-600" />
                <select name="gold_level_id" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required>
                    <option value="">Pilih kadar</option>
                    @foreach ($goldLevels as $goldLevel)
                        <option value="{{ $goldLevel->id }}" data-percentage="{{ $goldLevel->percentage }}">{{ $goldLevel->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label value="Produk" class="text-slate-600" />
                <select name="product_type" class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required>
                    <option value="jewelry">Perhiasan</option>
                    <option value="bullion">Batangan</option>
                </select>
            </div>
            <div>
                <x-input-label value="Berat (gr)" class="text-slate-600" />
                <x-text-input name="weight" type="number" step="0.001" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required />
            </div>
            <div>
                <x-input-label value="Harga/gram" class="text-slate-600" />
                <x-text-input name="price_per_gram" type="number" step="0.01" class="mt-2 block w-full rounded-lg border-slate-200 px-3 py-2.5 text-sm focus:border-amber-500 focus:ring-amber-500" required />
            </div>
            <div>
                <x-input-label value="Total" class="text-slate-600" />
                <div class="mt-3 text-sm font-semibold text-slate-900" data-line-total>Rp 0,00</div>
            </div>
            <div class="flex items-end">
                <button type="button" class="text-sm font-semibold text-red-600" data-remove-item>Hapus</button>
            </div>
        </div>
    </template>
</x-app-layout>
