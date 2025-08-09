<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barangs';
    protected $primaryKey = 'id_barang';
    protected $fillable = [
        'nama_barang',
        'harga',
        'stok',
        'gambar',
        'id_user_kantin',
    ];

    public function kantin()
    {
        return $this->belongsTo(User::class, 'id_user_kantin', 'id_user');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_barang', 'id_barang');
    }

    
}
