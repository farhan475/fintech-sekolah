<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Topup;
use App\Models\Withdrawal;
use App\Models\Transaksi;
use App\Models\Barang;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if (!$siswa) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        // --- Menggabungkan Riwayat Transaksi (Versi Aman) ---

        $topups = Topup::where('id_siswa', $siswa->id_siswa)->get()->map(function ($item) {
            $item->jenis = 'Top-up';
            $item->deskripsi = 'Isi saldo via Bank Mini';
            $item->waktu_transaksi = $item->tanggal;
            return $item;
        });

        $withdrawals = Withdrawal::where('id_siswa', $siswa->id_siswa)->get()->map(function ($item) {
            $item->jenis = 'Penarikan';
            $item->deskripsi = 'Tarik tunai via Bank Mini';
            $item->waktu_transaksi = $item->tanggal;
            return $item;
        });

        $transaksis = Transaksi::with('barang')->where('id_siswa', $siswa->id_siswa)->get()->map(function ($item) {
            $item->jenis = 'Pembelian';

            $item->deskripsi = optional($item->barang)->nama_barang ?? 'Barang Dihapus';

            $item->waktu_transaksi = $item->created_at;
            return $item;
        });

        // Gabungkan dan urutkan
        $riwayat = $topups->concat($withdrawals)->concat($transaksis)->sortByDesc('waktu_transaksi');

        // --- Ambil Data Barang ---
        $barangs = Barang::with('kantin')
            ->where('stok', '>', 0)
            ->latest('id_barang')
            ->get();

        return view('siswa.dashboard', compact('siswa', 'riwayat', 'barangs'));
    }
}
