<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laporan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporans';

    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'tanggal',
        'jenis_transaksi',
        'jumlah',
        'id_user',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
