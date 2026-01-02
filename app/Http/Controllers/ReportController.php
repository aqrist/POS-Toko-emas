<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $user = request()->user();
        $branchId = request()->string('branch_id')->toString();
        $type = request()->string('type')->toString();
        $from = request()->string('from')->toString();
        $to = request()->string('to')->toString();

        $query = Transaction::query()
            ->with(['branch', 'user', 'customer'])
            ->when(! $user->isSuperAdmin(), function ($builder) use ($user) {
                $builder->where('branch_id', $user->branch_id);
            })
            ->when($branchId && $user->isSuperAdmin(), function ($builder) use ($branchId) {
                $builder->where('branch_id', $branchId);
            })
            ->when($type, function ($builder) use ($type) {
                $builder->where('type', $type);
            })
            ->when($from, function ($builder) use ($from) {
                $builder->whereDate('occurred_at', '>=', $from);
            })
            ->when($to, function ($builder) use ($to) {
                $builder->whereDate('occurred_at', '<=', $to);
            })
            ->latest('occurred_at');

        $summaryQuery = (clone $query);
        $summary = [
            'count' => $summaryQuery->count(),
            'total' => $summaryQuery->sum('total'),
        ];

        $transactions = $query->paginate(10)->withQueryString();
        $branches = Branch::query()->orderBy('name')->get();

        return view('reports.index', compact('transactions', 'branches', 'summary'));
    }
}
