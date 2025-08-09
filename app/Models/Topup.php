<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{
    use HasFactory;

    protected $table = 'topups';
    public $timestamps = false;
    protected $primaryKey = 'id_topup';
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
