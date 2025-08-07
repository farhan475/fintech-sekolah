<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Total Pengguna
        $totalPengguna = User::count();

        // 2. Ambil Total Saldo (hanya dari siswa)
        // Jika model Siswa tidak ada, akan diatur ke 0
        $totalSaldo = class_exists(Siswa::class) ? Siswa::sum('saldo') : 0;
        
        // 3. Ambil Jumlah Transaksi Hari Ini
        // whereDate() akan mengambil transaksi dengan created_at hari ini
        $transaksiHariIni = class_exists(Transaksi::class) ? Transaksi::whereDate('created_at', Carbon::today())->count() : 0;

        // 4. Ambil 5 Aktivitas Terbaru
        // Eager load 'user' untuk menampilkan nama pengguna tanpa query tambahan di loop
        $aktivitasTerbaru = class_exists(Transaksi::class) ? Transaksi::with('user')->latest()->take(5)->get() : collect();

        // Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalSaldo',
            'transaksiHariIni',
            'aktivitasTerbaru'
        ));
    }
}
