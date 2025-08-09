<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Laporan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiSiswaController extends Controller
{
    public function store(Request $request, Barang $barang)
    {
        $user = Auth::user();
        $siswa = $user->siswa;

        if ($barang->stok < 1) {
            return back()->with('error', 'Transaksi Gagal: Stok barang sudah habis!');
        }
        if ($siswa->saldo < $barang->harga) {
            return back()->with('error', 'Transaksi Gagal: Saldo Anda tidak mencukupi!');
        }

        try {
            DB::transaction(function () use ($siswa, $barang) {
                $siswa->saldo -= $barang->harga;
                $siswa->save();

                $barang->stok -= 1; 
                $barang->save();

                Transaksi::create([
                    'jumlah_barang' => 1,
                    'total_harga' => $barang->harga,
                    'tanggal' => Carbon::today(),
                    'id_siswa' => $siswa->id_siswa,
                    'id_barang' => $barang->id_barang,
                    'id_user_kantin' => $barang->id_user_kantin,
                ]);

                Laporan::create([
                    'tanggal' => Carbon::today(),
                    'jenis_transaksi' => 'transaksi',
                    'jumlah' => $barang->harga,
                    'id_user' => $siswa->id_user,
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memproses transaksi. Error: ' . $e->getMessage());
        }

        return redirect()->route('siswa.dashboard')->with('success', "Pembelian '{$barang->nama_barang}' berhasil!");
    }
}
