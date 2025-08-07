<?php

namespace App\Http\Controllers\Kantin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Barang;
use App\Models\Laporan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    // Menampilkan halaman utama untuk transaksi
    public function create(Request $request)
    {
        $siswa = null;
        if ($request->filled('nisn')) {
            $siswa = Siswa::with('user')->where('nisn', $request->nisn)->first();
        }

        // Ambil semua barang milik kantin yang sedang login
        $barangs = Barang::where('id_user_kantin', Auth::id())
                         ->where('stok', '>', 0) // Hanya tampilkan barang yang ada stok
                         ->get();

        return view('kantin.transaksi.create', compact('siswa', 'barangs'));
    }

    // Memproses transaksi penjualan
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'items' => 'required|array|min:1',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        $siswa = Siswa::find($request->id_siswa);
        $items = $request->items;
        $totalBelanja = 0;
        $idKantin = Auth::id();

        try {
            DB::transaction(function () use ($items, $siswa, &$totalBelanja, $idKantin) {
                // Loop untuk setiap barang yang dibeli
                foreach ($items as $itemData) {
                    $barang = Barang::find($itemData['id_barang']);
                    $jumlahBeli = $itemData['jumlah'];
                    
                    // Validasi Keamanan: Pastikan barang milik kantin yang benar
                    if ($barang->id_user_kantin !== $idKantin) {
                        throw new \Exception("Produk tidak valid.");
                    }
                    // Validasi Stok
                    if ($barang->stok < $jumlahBeli) {
                        throw new \Exception("Stok untuk barang '{$barang->nama_barang}' tidak mencukupi.");
                    }

                    // Hitung total belanja
                    $subtotal = $barang->harga * $jumlahBeli;
                    $totalBelanja += $subtotal;

                    // Kurangi stok barang
                    $barang->stok -= $jumlahBeli;
                    $barang->save();

                    // Catat di tabel 'transaksis' (sesuai ERD: 1 baris per item)
                    Transaksi::create([
                        'jumlah_barang' => $jumlahBeli,
                        'total_harga' => $subtotal,
                        'tanggal' => Carbon::today(),
                        'id_siswa' => $siswa->id_siswa,
                        'id_barang' => $barang->id_barang,
                        'id_user_kantin' => $idKantin,
                    ]);
                }

                // Validasi Saldo Siswa
                if ($siswa->saldo < $totalBelanja) {
                    throw new \Exception("Saldo siswa tidak mencukupi. Total belanja: Rp " . number_format($totalBelanja));
                }

                // Kurangi saldo siswa
                $siswa->saldo -= $totalBelanja;
                $siswa->save();

                // Catat di tabel 'laporans' (satu kali untuk seluruh total belanja)
                Laporan::create([
                    'tanggal' => Carbon::today(),
                    'jenis_transaksi' => 'transaksi',
                    'jumlah' => $totalBelanja,
                    'id_user' => $siswa->id_user,
                ]);
            });
        } catch (\Throwable $e) {
            // Jika ada error di dalam transaksi, kembalikan dengan pesan error
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('kantin.transaksi.create')->with('success', 'Transaksi berhasil!');
    }
}