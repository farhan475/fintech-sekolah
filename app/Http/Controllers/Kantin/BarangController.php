<?php

namespace App\Http\Controllers\Kantin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    // Menampilkan daftar barang milik kantin yang sedang login
    public function index()
    {
        $barangs = Barang::where('id_user_kantin', Auth::id())
            ->latest()
            ->paginate(10);
        return view('kantin.barang.index', compact('barangs'));
    }

    // Menampilkan form untuk menambah barang baru
    public function create()
    {
        return view('kantin.barang.create');
    }

    // Menyimpan barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            // Validasi untuk gambar: opsional, harus file gambar, maks 2MB
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nama_barang', 'harga', 'stok']);
        $data['id_user_kantin'] = Auth::id();

        // Cek jika ada file gambar yang diupload
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke folder 'public/barangs' dan dapatkan path-nya
            $path = $request->file('gambar')->store('barangs', 'public');
            $data['gambar'] = $path;
        }

        Barang::create($data);

        return redirect()->route('kantin.barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }


    // Menampilkan form untuk mengedit barang
    public function edit(Barang $barang)
    {
        // Pastikan kantin hanya bisa mengedit barang miliknya sendiri
        if ($barang->id_user_kantin !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }
        return view('kantin.barang.edit', compact('barang'));
    }

    // Memperbarui data barang di database
    public function update(Request $request, Barang $barang)
    {
        if ($barang->id_user_kantin !== Auth::id()) abort(403);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nama_barang', 'harga', 'stok']);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            // Upload gambar baru
            $path = $request->file('gambar')->store('barangs', 'public');
            $data['gambar'] = $path;
        }

        $barang->update($data);

        return redirect()->route('kantin.barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }


    // Menghapus barang dari database
    public function destroy(Barang $barang)
    {
        if ($barang->id_user_kantin !== Auth::id()) abort(403);

        // Hapus gambar dari storage sebelum menghapus data dari database
        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('kantin.barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
