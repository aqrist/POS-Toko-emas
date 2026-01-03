const offlineRoot = document.getElementById('offline-cashier');

if (offlineRoot) {
    const statusBadge = document.getElementById('offline-status');
    const syncButton = document.getElementById('sync-now');
    const queueContainer = document.getElementById('offline-queue');
    const queueCount = document.getElementById('queue-count');
    const subtotalEl = document.getElementById('offline-subtotal');
    const additionalFeeEl = document.getElementById('offline-additional-fee');
    const totalEl = document.getElementById('offline-total');
    const messageEl = document.getElementById('offline-message');
    const form = document.getElementById('offline-transaction-form');
    const confirmMessage = document.getElementById('offline-confirm-message');
    const confirmSubmit = document.getElementById('offline-confirm-submit');
    const installButton = document.getElementById('install-app');
    const typeInput = document.getElementById('offline_type');
    const typeButtons = document.querySelectorAll('[data-transaction-type]');
    const offlineTitle = document.getElementById('offline-title');
    const offlineModeLabel = document.getElementById('offline-mode-label');
    const itemsWrapper = document.getElementById('offline-items');
    const addItemButton = document.getElementById('offline-add-item');
    const itemTemplate = document.getElementById('offline-item-template');
    const isIosDevice = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
    const isStandaloneMode = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    let installPromptEvent = null;

    const marketPrice = Number(offlineRoot.dataset.marketPrice || 0);
    const dbPromise = new Promise((resolve, reject) => {
        const request = indexedDB.open('pos-offline', 1);

        request.onupgradeneeded = () => {
            const db = request.result;
            if (!db.objectStoreNames.contains('transactions')) {
                db.createObjectStore('transactions', { keyPath: 'id' });
            }
        };

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });

    const withStore = async (mode, callback) => {
        const db = await dbPromise;
        return new Promise((resolve, reject) => {
            const transaction = db.transaction('transactions', mode);
            const store = transaction.objectStore('transactions');
            const result = callback(store);

            transaction.oncomplete = () => resolve(result);
            transaction.onerror = () => reject(transaction.error);
        });
    };

    const getAllTransactions = async () => {
        return withStore('readonly', (store) => {
            return new Promise((resolve, reject) => {
                const request = store.getAll();
                request.onsuccess = () => resolve(request.result);
                request.onerror = () => reject(request.error);
            });
        });
    };

    const saveTransaction = async (payload) => {
        return withStore('readwrite', (store) => store.put(payload));
    };

    const clearTransactions = async () => {
        return withStore('readwrite', (store) => store.clear());
    };

    const updateStatus = () => {
        if (!statusBadge) {
            return;
        }

        if (navigator.onLine) {
            statusBadge.textContent = 'Online';
            statusBadge.className = 'rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700';
        } else {
            statusBadge.textContent = 'Offline';
            statusBadge.className = 'rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700';
        }
    };

    const renderQueue = async () => {
        const transactions = await getAllTransactions();

        if (queueCount) {
            queueCount.textContent = `${transactions.length} transaksi`;
        }

        if (!queueContainer) {
            return;
        }

        queueContainer.innerHTML = '';

        if (transactions.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'rounded-md border border-dashed border-gray-200 p-3 text-center text-gray-500';
            empty.textContent = 'Belum ada transaksi offline.';
            queueContainer.appendChild(empty);
            return;
        }

        transactions
            .sort((a, b) => new Date(b.occurred_at) - new Date(a.occurred_at))
            .forEach((transaction) => {
                const total = transaction.items.reduce((sum, item) => sum + item.weight * item.price_per_gram, 0) + (transaction.additional_fee || 0);
                const row = document.createElement('div');
                row.className = 'rounded-md border border-gray-200 p-3';
                row.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="font-semibold">${transaction.type === 'buy' ? 'Beli' : 'Jual'} - ${transaction.payment_method}</div>
                        <div class="text-xs text-gray-500">${transaction.occurred_at}</div>
                    </div>
                    <div class="text-sm text-gray-600">Total: Rp ${total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                `;
                queueContainer.appendChild(row);
            });
    };

    const getGoldLevelPercentage = (select) => {
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) {
            return 0;
        }

        return Number(selectedOption.dataset.percentage || 0);
    };

    const formatCurrency = (value) => {
        return `Rp ${value.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    };

    const showMessage = (type, text) => {
        if (!messageEl) {
            return;
        }

        const baseClass = 'rounded-md border px-4 py-3 text-sm';
        const styles = {
            success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
            error: 'border-red-200 bg-red-50 text-red-700',
            warning: 'border-amber-200 bg-amber-50 text-amber-700',
        };

        messageEl.className = `${baseClass} ${styles[type] ?? styles.success}`;
        messageEl.textContent = text;
        messageEl.classList.remove('hidden');

        window.clearTimeout(showMessage.timeoutId);
        showMessage.timeoutId = window.setTimeout(() => {
            messageEl.classList.add('hidden');
        }, 4000);
    };

    const revealInstallButton = () => {
        if (!installButton || isStandaloneMode) {
            return;
        }

        installButton.classList.remove('hidden');
    };

    const updateLineTotal = (row) => {
        const weight = Number(row.querySelector('input[name="weight"]')?.value || 0);
        const price = Number(row.querySelector('input[name="price_per_gram"]')?.value || 0);
        const total = weight * price;
        const lineTotalEl = row.querySelector('[data-line-total]');
        if (lineTotalEl) {
            lineTotalEl.textContent = formatCurrency(total);
        }
    };

    const updateTotals = () => {
        const rows = itemsWrapper?.querySelectorAll('[data-offline-item]') ?? [];
        const subtotal = Array.from(rows).reduce((sum, row) => {
            const weight = Number(row.querySelector('input[name="weight"]')?.value || 0);
            const price = Number(row.querySelector('input[name="price_per_gram"]')?.value || 0);
            return sum + weight * price;
        }, 0);

        const additionalFee = Number(form?.querySelector('input[name="additional_fee"]')?.value || 0);
        const total = subtotal + additionalFee;

        if (subtotalEl) {
            subtotalEl.textContent = formatCurrency(subtotal);
        }
        if (additionalFeeEl) {
            additionalFeeEl.textContent = formatCurrency(additionalFee);
        }
        if (totalEl) {
            totalEl.textContent = formatCurrency(total);
        }
    };

    const applyAutoPrice = (row) => {
        const select = row.querySelector('select[name="gold_level_id"]');
        const priceInput = row.querySelector('input[name="price_per_gram"]');

        if (!select || !priceInput || marketPrice === 0) {
            return;
        }

        const percentage = getGoldLevelPercentage(select);
        if (!percentage) {
            return;
        }

        const computed = (marketPrice * percentage) / 100;
        const hasManualValue = priceInput.dataset.auto === 'false';

        if (!priceInput.value || !hasManualValue) {
            priceInput.value = computed.toFixed(2);
            priceInput.dataset.auto = 'true';
            updateTotals();
        }
    };

    const bindRowEvents = (row) => {
        if (!row) {
            return;
        }

        const select = row.querySelector('select[name="gold_level_id"]');
        const priceInput = row.querySelector('input[name="price_per_gram"]');
        const weightInput = row.querySelector('input[name="weight"]');
        const removeButton = row.querySelector('[data-remove-item]');

        select?.addEventListener('change', () => applyAutoPrice(row));
        priceInput?.addEventListener('input', () => {
            priceInput.dataset.auto = 'false';
            updateLineTotal(row);
            updateTotals();
        });
        weightInput?.addEventListener('input', () => {
            updateLineTotal(row);
            updateTotals();
        });
        removeButton?.addEventListener('click', () => {
            const rows = itemsWrapper?.querySelectorAll('[data-offline-item]') ?? [];
            if (rows.length <= 1) {
                return;
            }
            row.remove();
            updateTotals();
        });

        applyAutoPrice(row);
        updateLineTotal(row);
    };

    const collectItems = () => {
        const rows = itemsWrapper?.querySelectorAll('[data-offline-item]') ?? [];
        return Array.from(rows).map((row) => {
            const goldLevelId = row.querySelector('select[name="gold_level_id"]').value;
            const productType = row.querySelector('select[name="product_type"]').value;
            const weight = Number(row.querySelector('input[name="weight"]').value || 0);
            const pricePerGram = Number(row.querySelector('input[name="price_per_gram"]').value || 0);

            return {
                id: crypto.randomUUID(),
                gold_level_id: goldLevelId,
                product_type: productType,
                weight,
                price_per_gram: pricePerGram,
            };
        });
    };

    const resetForm = () => {
        form?.reset();
        if (itemsWrapper) {
            itemsWrapper.innerHTML = '';
            const html = itemTemplate?.innerHTML?.trim();
            if (html) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;
                const firstRow = wrapper.firstElementChild;
                if (firstRow) {
                    itemsWrapper.appendChild(firstRow);
                    bindRowEvents(firstRow);
                    updateLineTotal(firstRow);
                }
            }
        }
        updateTotals();
    };

    const syncNow = async () => {
        if (!navigator.onLine) {
            updateStatus();
            showMessage('warning', 'Masih offline. Sync akan berjalan saat online.');
            return;
        }

        const transactions = await getAllTransactions();
        if (transactions.length === 0) {
            showMessage('warning', 'Tidak ada transaksi untuk disinkronkan.');
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        const response = await fetch('/sync/transactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ transactions }),
        });

        if (!response.ok) {
            showMessage('error', 'Sync gagal. Coba lagi saat koneksi stabil.');
            return;
        }

        const result = await response.json();
        if (result.failed === 0) {
            await clearTransactions();
            await renderQueue();
            showMessage('success', 'Semua transaksi berhasil disinkronkan.');
        } else {
            showMessage('warning', 'Sebagian transaksi gagal. Coba sync ulang.');
        }
    };

    const handleSubmit = async () => {
        const items = collectItems().filter((item) => item.gold_level_id);

        if (items.length === 0) {
            showMessage('error', 'Minimal harus ada 1 item transaksi.');
            return;
        }

        const payload = {
            id: crypto.randomUUID(),
            type: typeInput?.value || 'sell',
            payment_method: form.querySelector('select[name="payment_method"]').value,
            occurred_at: form.querySelector('input[name="occurred_at"]').value,
            additional_fee: Number(form.querySelector('input[name="additional_fee"]').value || 0),
            notes: form.querySelector('input[name="notes"]').value,
            items,
        };

        await saveTransaction(payload);
        await renderQueue();
        resetForm();
        showMessage('success', 'Transaksi tersimpan di perangkat (offline).');
    };

    const openConfirmModal = () => {
        if (confirmMessage) {
            confirmMessage.textContent = navigator.onLine
                ? 'Koneksi terdeteksi. Transaksi akan disimpan di perangkat dan masuk antrian sync.'
                : 'Anda sedang offline. Transaksi akan disimpan di perangkat dan disinkronkan saat online.';
        }

        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-offline-transaction' }));
    };

    form?.addEventListener('submit', (event) => {
        event.preventDefault();
        openConfirmModal();
    });

    confirmSubmit?.addEventListener('click', async () => {
        confirmSubmit.setAttribute('disabled', 'disabled');
        confirmSubmit.classList.add('opacity-70');

        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-offline-transaction' }));

        try {
            await handleSubmit();
        } finally {
            confirmSubmit.removeAttribute('disabled');
            confirmSubmit.classList.remove('opacity-70');
        }
    });

    installButton?.addEventListener('click', async () => {
        if (!installPromptEvent) {
            showMessage('warning', 'Buka menu browser lalu pilih "Add to Home Screen".');
            return;
        }

        installPromptEvent.prompt();
        await installPromptEvent.userChoice;
        installPromptEvent = null;
        installButton.classList.add('hidden');
    });

    addItemButton?.addEventListener('click', () => {
        if (!itemTemplate || !itemsWrapper) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.innerHTML = itemTemplate.innerHTML.trim();
        const row = wrapper.firstElementChild;
        if (!row) {
            return;
        }
        itemsWrapper.appendChild(row);
        bindRowEvents(row);
    });

    syncButton?.addEventListener('click', syncNow);
    window.addEventListener('online', () => {
        updateStatus();
        syncNow();
    });
    window.addEventListener('offline', updateStatus);
    form?.querySelector('input[name="additional_fee"]')?.addEventListener('input', updateTotals);
    window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault();
        installPromptEvent = event;
        revealInstallButton();
    });

    typeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const nextType = button.dataset.transactionType;
            if (!nextType || !typeInput) {
                return;
            }

            typeInput.value = nextType;
            typeButtons.forEach((btn) => {
                btn.classList.remove('text-amber-700', 'bg-amber-50', 'ring-1', 'ring-amber-200');
                btn.classList.remove('text-blue-700', 'bg-blue-50', 'ring-1', 'ring-blue-200');
                btn.classList.add('text-slate-500');
            });

            if (nextType === 'buy') {
                button.classList.add('text-blue-700', 'bg-blue-50', 'ring-1', 'ring-blue-200');
            } else {
                button.classList.add('text-amber-700', 'bg-amber-50', 'ring-1', 'ring-amber-200');
            }
            button.classList.remove('text-slate-500');

            if (offlineTitle) {
                offlineTitle.textContent = nextType === 'buy' ? 'Buy Gold Transaction' : 'Sell Gold Transaction';
            }
            if (offlineModeLabel) {
                offlineModeLabel.textContent = nextType === 'buy' ? 'Beli Emas' : 'Jual Emas';
            }
        });
    });

    updateStatus();
    renderQueue();
    if (isIosDevice) {
        revealInstallButton();
    }

    itemsWrapper?.querySelectorAll('[data-offline-item]').forEach((row) => {
        bindRowEvents(row);
    });
    updateTotals();
}
