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
    public function index(Request $request)
    {
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
        $bankUserId = Auth::id();

        $topups = Topup::with('siswa.user')
            ->where('id_user_bank', $bankUserId)
            ->where('tanggal', $selectedDate)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'waktu' => $item->tanggal, 
                    'nama_siswa' => $item->siswa->user->nama ?? 'Siswa Dihapus',
                    'jenis' => 'Top-up',
                    'jumlah' => $item->jumlah,
                    'status' => 'masuk',
                ];
            });

        $withdrawals = Withdrawal::with('siswa.user')
            ->where('id_user_bank', $bankUserId)
            ->where('tanggal', $selectedDate)
            ->get()
            ->map(function ($item) {
                return (object) [
                    'waktu' => $item->tanggal, 
                    'nama_siswa' => $item->siswa->user->nama ?? 'Siswa Dihapus',
                    'jenis' => 'Penarikan',
                    'jumlah' => $item->jumlah,
                    'status' => 'keluar',
                ];
            });

        $laporanGabungan = $topups->concat($withdrawals)->sortByDesc('waktu');

        $totalTopup = $topups->sum('jumlah');
        $totalWithdrawal = $withdrawals->sum('jumlah');
        $jumlahTransaksi = $laporanGabungan->count();

        return view('bank.laporan.index', compact(
            'laporanGabungan',
            'selectedDate',
            'totalTopup',
            'totalWithdrawal',
            'jumlahTransaksi'
        ));
    }
}