<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\JadwalKaryawanController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\PSUnitController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| REDIRECT DEFAULT
|--------------------------------------------------------------------------
| Ketika user membuka "/", arahkan ke login jika belum login.
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
})->middleware('guest');

/*
|--------------------------------------------------------------------------
| GUEST (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| SEMUA USER LOGIN (boss & karyawan)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produk
    Route::resource('products', ProductController::class);

    // PRESENSI
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi/check-in', [PresensiController::class, 'checkIn'])->name('presensi.checkin');
    Route::post('/presensi/check-out', [PresensiController::class, 'checkOut'])->name('presensi.checkout');
    Route::get('/presensi/riwayat', [PresensiController::class, 'riwayat'])->name('presensi.riwayat');
    Route::get('/presensi/{presensi}/edit', [PresensiController::class, 'edit'])->name('presensi.edit');
    Route::put('/presensi/{presensi}', [PresensiController::class, 'update'])->name('presensi.update');
    Route::delete('/presensi/{presensi}', [PresensiController::class, 'destroy'])->name('presensi.destroy');

    // Jadwal
    Route::get('/jadwal', [JadwalKaryawanController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalKaryawanController::class, 'store'])->name('jadwal.store');

    // KHUSUS BOSS
    Route::middleware('role:boss')->group(function () {
        Route::resource('karyawan', KaryawanController::class);

        Route::get('/presensi/report', [PresensiController::class, 'report'])->name('presensi.report');
        Route::get('/presensi/report/export-excel', [PresensiController::class, 'exportExcel'])->name('presensi.report.excel');
        Route::get('/presensi/report/export-pdf', [PresensiController::class, 'exportPdf'])->name('presensi.report.pdf');
    });

    // Sessions (rental)
    Route::get('/sessions', [SessionsController::class, 'index'])->name('sessions.index');
    Route::post('/sessions/fixed', [SessionsController::class, 'storeFixed'])->name('sessions.fixed');
    Route::delete('/sessions/{sid}', [SessionsController::class, 'destroy'])->name('sessions.delete');

    // PS Units
    Route::get('/ps-units', [PSUnitController::class, 'index'])->name('ps_units.index');
    Route::post('/ps-units', [PSUnitController::class, 'store'])->name('ps_units.store');
    Route::put('/ps-units/{id}', [PSUnitController::class, 'update'])->name('ps_units.update');
    Route::post('/ps-units/{id}/toggle', [PSUnitController::class, 'toggle'])->name('ps_units.toggle');
    Route::delete('/ps-units/{id}', [PSUnitController::class, 'destroy'])->name('ps_units.destroy');

    // POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');

    // Expenses
    Route::prefix('purchases')->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('purchases.expenses.index');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('purchases.expenses.store');
        Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('purchases.expenses.update');
        Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->name('purchases.expenses.destroy');
    });

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Sales
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/{id}', [SaleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SaleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SaleController::class, 'update'])->name('update');
        Route::delete('/{id}', [SaleController::class, 'destroy'])->name('destroy');
    });
});

// Tambahkan route sementara ini

Route::get('/buat-storage-link', function () {
    Artisan::call('storage:link');
    return 'Folder Storage berhasil di-link! Silakan kembali ke halaman karyawan.';
});

// --- ROUTE SEMENTARA UNTUK LINK STORAGE ---

Route::get('/buat-storage-link', function () {
    // Menjalankan perintah 'php artisan storage:link' lewat browser
    Artisan::call('storage:link');
    return 'Folder Storage berhasil di-link! Silakan kembali ke halaman karyawan.';
});

// ...
// Sessions (rental)
Route::get('/sessions', [SessionsController::class, 'index'])->name('sessions.index');
Route::post('/sessions/fixed', [SessionsController::class, 'storeFixed'])->name('sessions.fixed');
Route::delete('/sessions/{sid}', [SessionsController::class, 'destroy'])->name('sessions.delete');

// --- TAMBAHAN ROUTE BARU ---
Route::post('/sessions/add-time', [SessionsController::class, 'addTime'])->name('sessions.add_time');
// ---------------------------


// ... route lainnya ...
Route::get('/jadwal/report/excel', [JadwalKaryawanController::class, 'exportExcel'])->name('jadwal.report.excel');
Route::get('/jadwal/report/pdf', [JadwalKaryawanController::class, 'exportPdf'])->name('jadwal.report.pdf');
// ...