<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ReceiptController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Public: Root redirect ────────────────────────────────────────────────
Route::get('/', fn () => redirect()->route('tracking.index'));

// ── Public: Customer Tracking (NO LOGIN REQUIRED) ────────────────────────
Route::get('/tracking',         [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/tracking',        [TrackingController::class, 'track'])->name('tracking.track');
Route::get('/pickup',           [TrackingController::class, 'showPickupForm'])->name('tracking.pickup.form');
Route::post('/pickup',          [TrackingController::class, 'storePickup'])->name('tracking.pickup.store');

// ── Public: Pricing & Support ────────────────────────────────────────────────
Route::get('/pricing', function () {
    return view('pricing.index', [
        'services' => \App\Models\LaundryService::active()->get()
    ]);
})->name('pricing.index');

Route::get('/support', function () {
    return view('support.index');
})->name('support.index');


// ── Public: Download receipt (by tracking code, no auth) ─────────────────
Route::get('/receipt/{transaction}', [ReceiptController::class, 'download'])->name('receipt.download');

// ── Auth: Login / Logout ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Panel (requires auth) ──────────────────────────────────────────
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions
    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])
         ->name('transactions.updateStatus');
    Route::get('/api/service-price', [TransactionController::class, 'getServicePrice'])
         ->name('api.servicePrice');

    // Services / Settings
    Route::resource('services', ServiceController::class)->except(['show']);

    // Reports
    Route::get('/reports',            [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.exportPdf');
});
