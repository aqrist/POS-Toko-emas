<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Customer::class, 'customer');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = request()->user();

        $customers = Customer::query()
            ->when(! $user->isSuperAdmin(), function ($query) use ($user) {
                $query->where('branch_id', $user->branch_id);
            })
            ->orderBy('name')
            ->paginate(10);

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $branches = Branch::query()->orderBy('name')->get();

        return view('customers.create', compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! $user->isSuperAdmin()) {
            $data['branch_id'] = $user->branch_id;
        }

        $data['is_synced'] = true;
        $data['synced_at'] = now();

        Customer::query()->create($data);

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(Customer $customer): View
    {
        $branches = Branch::query()->orderBy('name')->get();

        return view('customers.edit', compact('customer', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! $user->isSuperAdmin()) {
            $data['branch_id'] = $user->branch_id;
        }

        $customer->update($data);

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('status', 'Customer berhasil dihapus.');
    }
}
