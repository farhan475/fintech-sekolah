<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan laporan transaksi gabungan (Top-up & Withdrawal)
     * untuk bank yang sedang login.
     */
    public function index(Request $request)
    {
        // 1. Tentukan tanggal dan ID bank
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
        $bankUserId = Auth::id();

        // 2. Ambil data top-up dan standarkan formatnya
        $topups = Topup::with('siswa.user')
            ->where('id_user_bank', $bankUserId)
            ->where('tanggal', $selectedDate)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'waktu' => $item->tanggal, // Untuk sorting
                    'nama_siswa' => $item->siswa->user->nama ?? 'Siswa Dihapus',
                    'jenis' => 'Top-up',
                    'jumlah' => $item->jumlah,
                    'status' => 'masuk',
                ];
            });

        // 3. Ambil data withdrawal dan standarkan formatnya
        $withdrawals = Withdrawal::with('siswa.user')
            ->where('id_user_bank', $bankUserId)
            ->where('tanggal', $selectedDate)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'waktu' => $item->tanggal, // Untuk sorting
                    'nama_siswa' => $item->siswa->user->nama ?? 'Siswa Dihapus',
                    'jenis' => 'Penarikan',
                    'jumlah' => $item->jumlah,
                    'status' => 'keluar',
                ];
            });

        // 4. Gabungkan kedua koleksi data dan urutkan
        $laporanGabungan = $topups->concat($withdrawals)->sortByDesc('waktu');

        // 5. Hitung ringkasan total dari data mentah (sebelum di-map)
        $totalTopup = $topups->sum('jumlah');
        $totalWithdrawal = $withdrawals->sum('jumlah');
        $jumlahTransaksi = $laporanGabungan->count();

        // 6. Kirim semua data yang dibutuhkan ke view
        return view('bank.laporan.index', compact(
            'laporanGabungan',
            'selectedDate',
            'totalTopup',
            'totalWithdrawal',
            'jumlahTransaksi'
        ));
    }
}