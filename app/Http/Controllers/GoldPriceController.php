<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoldPriceRequest;
use App\Http\Requests\UpdateGoldPriceRequest;
use App\Models\Branch;
use App\Models\GoldPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GoldPriceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(GoldPrice::class, 'gold_price');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = request()->user();

        $goldPrices = GoldPrice::query()
            ->when(! $user->isSuperAdmin(), function ($query) use ($user) {
                $query->where(function ($subQuery) use ($user) {
                    $subQuery->whereNull('branch_id')
                        ->orWhere('branch_id', $user->branch_id);
                });
            })
            ->latest('effective_date')
            ->paginate(10);

        return view('gold-prices.index', compact('goldPrices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $branches = Branch::query()->orderBy('name')->get();

        return view('gold-prices.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoldPriceRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! $user->isSuperAdmin()) {
            $data['branch_id'] = $user->branch_id;
        }

        $data['is_synced'] = true;
        $data['synced_at'] = now();

        GoldPrice::query()->create($data);

        return redirect()
            ->route('gold-prices.index')
            ->with('status', 'Harga emas berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(GoldPrice $goldPrice): View
    {
        $branches = Branch::query()->orderBy('name')->get();

        return view('gold-prices.edit', compact('goldPrice', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoldPriceRequest $request, GoldPrice $goldPrice): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! $user->isSuperAdmin()) {
            $data['branch_id'] = $user->branch_id;
        }

        $goldPrice->update($data);

        return redirect()
            ->route('gold-prices.index')
            ->with('status', 'Harga emas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoldPrice $goldPrice): RedirectResponse
    {
        $goldPrice->delete();

        return redirect()
            ->route('gold-prices.index')
            ->with('status', 'Harga emas berhasil dihapus.');
    }
}
