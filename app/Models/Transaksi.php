<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = [
        'jumlah_barang',
        'total_harga',
        'tanggal',
        'id_siswa',
        'id_barang',
        'id_user_kantin',
    ];

    // Relasi dengan Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Relasi dengan Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Relasi dengan User (Kantin)
    public function kantin()
    {
        return $this->belongsTo(User::class, 'id_user_kantin', 'id_user');
}
}
