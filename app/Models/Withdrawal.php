<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_withdrawal';
    protected $fillable = [
        'jumlah',
        'tanggal',
        'id_siswa',
        'id_user_bank',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
    public function bank()
    {
        return $this->belongsTo(User::class, 'id_user_bank', 'id_user');
    }
}
