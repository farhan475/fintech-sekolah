<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Kantin\LaporanController as KantinLaporanController;
use App\Http\Controllers\Bank\LaporanController as BankLaporanController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Bank\TopupController;
use App\Http\Controllers\Bank\WithdrawalController;
use App\Http\Controllers\Kantin\BarangController;
use App\Http\Controllers\Kantin\TransaksiController;
use App\Http\Controllers\Siswa\TransaksiSiswaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Routes Publik ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// --- Redirect Halaman Utama ---
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role;
        if ($role === 'administrator') {
            return redirect()->route('admin.dashboard');
        }
        
        if (in_array($role, ['bank', 'kantin', 'siswa'])) {
            // Redirect ke dashboard sesuai role yang ada
            return redirect()->route($role . '.dashboard');
        }
        // Fallback jika role tidak terdaftar
        return redirect()->route('dashboard');
    }
    // Arahkan ke login jika belum login
    return redirect()->route('login');
});

// --- Routes yang Membutuhkan Autentikasi ---
Route::middleware(['auth'])->group(function () {

    // --- Admin Routes ---
    Route::middleware(['role:administrator'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // PERBAIKAN: Gunakan Route::resource untuk CRUD yang lebih bersih.
        // Ini sudah mencakup index, create, store, edit, update, dan destroy.
        Route::resource('users', UserController::class);
        Route::get('/report', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // --- Bank Routes ---
    Route::middleware(['role:bank'])->prefix('bank')->name('bank.')->group(function () {
        Route::get('/dashboard', function () {
            return view('bank.dashboard');
        })->name('dashboard');
        // Topup Routes
        Route::get('/topup', [TopupController::class, 'create'])->name('topup.create');
        Route::post('/topup', [TopupController::class, 'store'])->name('topup.store');

        // Withdrawal Routes
        Route::get('/withdrawal', [WithdrawalController::class, 'create'])->name('withdrawal.create');
        Route::post('/withdrawal', [WithdrawalController::class, 'store'])->name('withdrawal.store');

        // Laporan Route
        Route::get('/laporan', [BankLaporanController::class, 'index'])->name('laporan.index');
    });

    // --- Kantin Routes ---
    Route::middleware(['role:kantin'])->prefix('kantin')->name('kantin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('kantin.dashboard');
        })->name('dashboard');

        Route::resource('barang', BarangController::class);
        // Routes untuk Transaksi
        Route::get('/transaksi', [TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');

        //Routes untuk Laporan
        Route::get('/laporan', [KantinLaporanController::class, 'index'])->name('laporan.index');
    });

    // --- Siswa Routes ---
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/beli/{barang:id_barang}', [TransaksiSiswaController::class, 'store'])->name('transaksi.store');
    // Nanti bisa ditambahkan route lain di sini jika perlu
});

    // --- Route Umum (di luar grup role-specific) ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
