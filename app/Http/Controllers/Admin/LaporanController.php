<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Tentukan tanggal yang dipilih.
        // Jika ada input 'tanggal' dari user, gunakan itu. Jika tidak, gunakan tanggal hari ini.
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());

        // 2. Ambil semua data laporan untuk tanggal yang dipilih.
        // Eager load relasi 'user' untuk menghindari N+1 query problem di view.
        $laporans = Laporan::with('user')
            ->where('tanggal', $selectedDate)
            ->latest() // Urutkan berdasarkan waktu transaksi terbaru
            ->get();

        // 3. Hitung ringkasan/summary data untuk tanggal yang dipilih.
        $totalTopup = $laporans->where('jenis_transaksi', 'topup')->sum('jumlah');
        $totalWithdrawal = $laporans->where('jenis_transaksi', 'withdrawal')->sum('jumlah');
        $totalTransaksi = $laporans->where('jenis_transaksi', 'transaksi')->sum('jumlah');
        $jumlahTotalTransaksi = $laporans->count();

        // 4. Kirim semua data yang dibutuhkan ke view.
        return view('admin.laporan.index', compact(
            'selectedDate',
            'laporans',
            'totalTopup',
            'totalWithdrawal',
            'totalTransaksi',
            'jumlahTotalTransaksi'
        ));
    }
}
