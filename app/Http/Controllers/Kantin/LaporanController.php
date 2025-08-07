<?php

namespace App\Http\Controllers\Kantin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan harian untuk kantin yang sedang login.
     */
    public function index(Request $request)
    {
        // Tentukan tanggal yang dipilih, defaultnya adalah hari ini.
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
        $idKantin = Auth::id();

        // Ambil semua data transaksi untuk kantin ini pada tanggal yang dipilih.
        // Gunakan eager loading untuk efisiensi query.
        $transaksis = Transaksi::with(['siswa.user', 'barang'])
            ->where('id_user_kantin', $idKantin)
            ->where('tanggal', $selectedDate)
            ->latest() // Urutkan dari yang terbaru
            ->get(); // Gunakan get() karena kita perlu menghitung total dari semua data harian

        // Hitung ringkasan/summary dari data yang sudah diambil
        $totalPendapatan = $transaksis->sum('total_harga');
        $totalBarangTerjual = $transaksis->sum('jumlah_barang');
        $jumlahTransaksi = $transaksis->count();

        // Kirim semua data yang dibutuhkan ke view
        return view('kantin.laporan.index', compact(
            'transaksis',
            'selectedDate',
            'totalPendapatan',
            'totalBarangTerjual',
            'jumlahTransaksi'
        ));
    }
}
