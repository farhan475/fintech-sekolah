<?php

namespace App\Http\Controllers\Kantin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
        $idKantin = Auth::id();

        $transaksis = Transaksi::with(['siswa.user', 'barang'])
            ->where('id_user_kantin', $idKantin)
            ->where('tanggal', $selectedDate)
            ->latest() 
            ->get(); 
        $totalPendapatan = $transaksis->sum('total_harga');
        $totalBarangTerjual = $transaksis->sum('jumlah_barang');
        $jumlahTransaksi = $transaksis->count();

        return view('kantin.laporan.index', compact(
            'transaksis',
            'selectedDate',
            'totalPendapatan',
            'totalBarangTerjual',
            'jumlahTransaksi'
        ));
    }
}
