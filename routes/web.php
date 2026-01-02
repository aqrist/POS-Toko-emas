<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\GoldLevelController;
use App\Http\Controllers\GoldPriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/offline', 'offline')->name('offline');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('branches', BranchController::class)->except(['show']);
    Route::resource('gold-levels', GoldLevelController::class)->except(['show']);
    Route::resource('gold-prices', GoldPriceController::class)->except(['show']);
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::resource('transactions', TransactionController::class)->except(['show']);
    Route::get('/cashier/offline', [CashierController::class, 'offline'])->name('cashier.offline');
    Route::post('/sync/transactions', [SyncController::class, 'store'])->name('sync.transactions.store');
    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('can:view-reports')
        ->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
