<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswas'; 
    protected $primaryKey = 'id_siswa';
    protected $fillable = [
        'nisn',
        'kelas',
        'saldo',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function topups()
    {
        return $this->hasMany(Topup::class, 'id_siswa', 'id_siswa');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'id_siswa', 'id_siswa');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_siswa', 'id_siswa');
    }
}
