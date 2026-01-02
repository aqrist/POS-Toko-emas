<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoldLevelRequest;
use App\Http\Requests\UpdateGoldLevelRequest;
use App\Models\GoldLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GoldLevelController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(GoldLevel::class, 'gold_level');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $goldLevels = GoldLevel::query()
            ->orderBy('percentage', 'desc')
            ->paginate(10);

        return view('gold-levels.index', compact('goldLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('gold-levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGoldLevelRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $data['is_active'] ?? true;
        $data['is_synced'] = true;
        $data['synced_at'] = now();

        GoldLevel::query()->create($data);

        return redirect()
            ->route('gold-levels.index')
            ->with('status', 'Kadar emas berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function edit(GoldLevel $goldLevel): View
    {
        return view('gold-levels.edit', compact('goldLevel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGoldLevelRequest $request, GoldLevel $goldLevel): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $data['is_active'] ?? true;

        $goldLevel->update($data);

        return redirect()
            ->route('gold-levels.index')
            ->with('status', 'Kadar emas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoldLevel $goldLevel): RedirectResponse
    {
        $goldLevel->delete();

        return redirect()
            ->route('gold-levels.index')
            ->with('status', 'Kadar emas berhasil dihapus.');
    }
}
