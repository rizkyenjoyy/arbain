<?php

use App\Http\Controllers\AdminJamaahController;
use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\AgenPaymentHistoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\JamaahController;
use App\Http\Controllers\ListJamaahController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommissionAgenController;
use App\Http\Controllers\AkunSayaController;
use App\Http\Controllers\HistoryPaymentController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Agent\AgentDashboardController;
use Illuminate\Support\Facades\Route;









/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Proteksi route untuk dashboard
Route::middleware('auth')->get('/dashboard', [SidebarController::class, 'test'])->name('test.index');



Route::middleware(['auth'])->group(function () {
    // Rute untuk pembayaran


    Route::resource('users', UserController::class);
    Route::post('/users/{id}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');




    Route::resource('packages', PackageController::class);


});


// Routes untuk agen (dengan prefix)
Route::prefix('dashboard/payments/agen')->middleware('auth')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('/history', [AgenPaymentHistoryController::class, 'index'])->name('agen.payments.history');
    Route::get('/history/data', [AgenPaymentHistoryController::class, 'getPayments'])->name('agen.payments.history.data');
});

// Route untuk pencarian jamaah (tanpa prefix)
Route::get('/payments/search-jamaah', [PaymentController::class, 'searchJamaah'])->middleware('auth')->name('payments.searchJamaah');

// Routes untuk admin (dengan prefix)
Route::prefix('dashboard/payments/admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::post('/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');
});

// Routes untuk agen (jamaah)
Route::prefix('dashboard/jamaah/agen')->middleware('auth')->group(function () {
    Route::get('/', [JamaahController::class, 'index'])->name('jamaah.agen.index');
    Route::get('/create', [JamaahController::class, 'create'])->name('jamaah.agen.create');
    Route::post('/', [JamaahController::class, 'store'])->name('jamaah.agen.store');
    Route::get('/{jamaah}/edit', [JamaahController::class, 'edit'])->name('jamaah.agen.edit');
    Route::put('/{jamaah}', [JamaahController::class, 'update'])->name('jamaah.agen.update');
    Route::delete('/{jamaah}', [JamaahController::class, 'destroy'])->name('jamaah.agen.destroy');
});

// Routes untuk admin (jamaah)
Route::prefix('dashboard/jamaah/admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminJamaahController::class, 'index'])->name('jamaah.admin.index');
    Route::post('/{jamaah}/approve', [AdminJamaahController::class, 'approve'])->name('jamaah.admin.approve');
    Route::delete('/{jamaah}/reject', [AdminJamaahController::class, 'reject'])->name('jamaah.admin.reject');
});

// Route untuk profil (tanpa prefix)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/profile', [ProfileController::class, 'index'])->name('profile.index');
});

// Routes untuk admin (CRUD komisi)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/commissions', [CommissionController::class, 'index'])->name('commissions.index');
    Route::get('/dashboard/commissions/create', [CommissionController::class, 'create'])->name('commissions.create');
    Route::post('/dashboard/commissions', [CommissionController::class, 'store'])->name('commissions.store');
    Route::delete('/dashboard/commissions/{commission}', [CommissionController::class, 'destroy'])->name('commissions.destroy');
});

// Routes untuk daftar jamaah (tanpa prefix)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/listjamaah', [ListJamaahController::class, 'index'])->name('listjamaah.index');
    Route::delete('/dashboard/listjamaah/{id}', [ListJamaahController::class, 'destroy'])->name('listjamaah.destroy');
    Route::get('/dashboard/listjamaah/{id}/edit', [ListJamaahController::class, 'edit'])->name('listjamaah.edit');
    Route::put('/dashboard/listjamaah/{id}', [ListJamaahController::class, 'update'])->name('listjamaah.update');
    Route::get('list-jamaah/export-csv', [ListJamaahController::class, 'exportCsv'])->name('listjamaah.exportCsv');
    Route::get('/dashboard/listjamaah/{id}/payments', [ListJamaahController::class, 'getPaymentHistory']);

});

// Routes untuk komisi agen (tanpa prefix)
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard/commissions/agen', [CommissionAgenController::class, 'index'])->name('commissions.agen.index');
    Route::get('/commissions/agen/export', [CommissionAgenController::class, 'exportExcel'])->name('commissions.agen.export');
    Route::get('/commissions/admin/export', [CommissionController::class, 'exportExcel'])->name('commissions.admin.export');
});

// Route untuk akun
Route::get('/akun-saya', [AkunSayaController::class, 'index'])->middleware('auth')->name('akun_saya.index');
Route::get('/akun-saya/agen', [AkunSayaController::class, 'agenIndex'])->middleware('auth')->name('akun.agen');

// Route untuk invoice jamaah (tanpa prefix)
Route::get('/jamaah/{id}/invoice', [PaymentController::class, 'generateInvoice'])->middleware('auth')->name('jamaah.invoice');

// Routes untuk riwayat pembayaran
Route::middleware(['auth'])->group(function () {
    Route::get('/jamaah-saya', [HistoryPaymentController::class, 'index'])->name('riwayat.pembayaran.index');
    Route::get('/riwayat-pembayaran/{id}', [HistoryPaymentController::class, 'show'])->name('riwayat.pembayaran.show');
    Route::get('/riwayat-pembayaran/export', [HistoryPaymentController::class, 'exportPDF'])->name('riwayat.pembayaran.export');
    Route::get('riwayat/pembayaran/export-excel', [HistoryPaymentController::class, 'exportExcel'])->name('riwayat.pembayaran.export.excel');
});

// Routes untuk laporan keuangan (dengan prefix)
Route::prefix('laporan-keuangan')->middleware('auth')->group(function () {
    Route::get('/', [LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
    Route::get('/download-csv', [LaporanKeuanganController::class, 'downloadCsv'])->name('laporan-keuangan.download-csv');
});

// Route untuk dashboard admin
Route::prefix('dashboard/admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Route untuk dashboard agen
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/agen', [AgentDashboardController::class, 'index'])->name('agen.dashboard');
});


