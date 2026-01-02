<?php

namespace App\Http\Controllers;

use App\Models\GoldLevel;
use App\Models\GoldPrice;
use Illuminate\Contracts\View\View;

class CashierController extends Controller
{
    public function offline(): View
    {
        $user = request()->user();

        if (! $user || (! $user->isSuperAdmin() && ! $user->isBranchAdmin() && ! $user->isCashier())) {
            abort(403);
        }

        $goldLevels = GoldLevel::query()
            ->where('is_active', true)
            ->orderBy('percentage', 'desc')
            ->get();

        $branchPrice = GoldPrice::query()
            ->where('branch_id', $user->branch_id)
            ->latest('effective_date')
            ->first();

        $globalPrice = GoldPrice::query()
            ->whereNull('branch_id')
            ->latest('effective_date')
            ->first();

        $marketPrice = $branchPrice?->market_price ?? $globalPrice?->market_price ?? 0;

        return view('cashier.offline', compact('goldLevels', 'marketPrice'));
    }
}
