<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\GoldLevel;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Transaction::class, 'transaction');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = request()->user();

        $transactions = Transaction::query()
            ->with(['customer', 'user', 'branch'])
            ->when(! $user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            })
            ->latest('occurred_at')
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = request()->user();

        $goldLevels = GoldLevel::query()
            ->where('is_active', true)
            ->orderBy('percentage', 'desc')
            ->get();

        $customers = Customer::query()
            ->when(! $user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            })
            ->orderBy('name')
            ->get();

        $branches = Branch::query()->orderBy('name')->get();

        return view('transactions.create', compact('goldLevels', 'customers', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! $user->isSuperAdmin()) {
            $data['branch_id'] = $user->branch_id;
        }

        $items = $data['items'];
        $subtotal = collect($items)->sum(function (array $item): float {
            return $item['weight'] * $item['price_per_gram'];
        });

        $additionalFee = $data['additional_fee'] ?? 0;
        $total = $subtotal + $additionalFee;

        DB::transaction(function () use ($data, $items, $subtotal, $additionalFee, $total, $user): void {
            $transaction = Transaction::query()->create([
                'branch_id' => $data['branch_id'],
                'user_id' => $user->id,
                'customer_id' => $data['customer_id'] ?? null,
                'type' => $data['type'],
                'payment_method' => $data['payment_method'],
                'occurred_at' => $data['occurred_at'],
                'subtotal' => $subtotal,
                'additional_fee' => $additionalFee ?: null,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
                'is_synced' => true,
                'synced_at' => now(),
            ]);

            foreach ($items as $item) {
                $transaction->items()->create([
                    'gold_level_id' => $item['gold_level_id'],
                    'product_type' => $item['product_type'],
                    'weight' => $item['weight'],
                    'price_per_gram' => $item['price_per_gram'],
                    'total' => $item['weight'] * $item['price_per_gram'],
                    'is_synced' => true,
                    'synced_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('transactions.index')
            ->with('status', 'Transaksi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Transaction $transaction): View
    {
        $user = request()->user();

        $transaction->load(['items']);

        $goldLevels = GoldLevel::query()
            ->where('is_active', true)
            ->orderBy('percentage', 'desc')
            ->get();

        $customers = Customer::query()
            ->when(! $user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            })
            ->orderBy('name')
            ->get();

        $branches = Branch::query()->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'goldLevels', 'customers', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! $user->isSuperAdmin()) {
            $data['branch_id'] = $transaction->branch_id;
        }

        $items = $data['items'];
        $subtotal = collect($items)->sum(function (array $item): float {
            return $item['weight'] * $item['price_per_gram'];
        });

        $additionalFee = $data['additional_fee'] ?? 0;
        $total = $subtotal + $additionalFee;

        DB::transaction(function () use ($transaction, $data, $items, $subtotal, $additionalFee, $total): void {
            $transaction->update([
                'branch_id' => $data['branch_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'type' => $data['type'],
                'payment_method' => $data['payment_method'],
                'occurred_at' => $data['occurred_at'],
                'subtotal' => $subtotal,
                'additional_fee' => $additionalFee ?: null,
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            $transaction->items()->delete();

            foreach ($items as $item) {
                $transaction->items()->create([
                    'gold_level_id' => $item['gold_level_id'],
                    'product_type' => $item['product_type'],
                    'weight' => $item['weight'],
                    'price_per_gram' => $item['price_per_gram'],
                    'total' => $item['weight'] * $item['price_per_gram'],
                    'is_synced' => true,
                    'synced_at' => now(),
                ]);
            }
        });

        return redirect()
            ->route('transactions.index')
            ->with('status', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()
            ->route('transactions.index')
            ->with('status', 'Transaksi berhasil dihapus.');
    }
}
