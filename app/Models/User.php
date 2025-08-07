<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // app/Models/User.php
    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id_user');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_user_kantin', 'id_user');
    }

    public function bank()
    {
        return $this->hasOne(Bank::class, 'id_user', 'id_user');
    }

    public function topups()
    {
        return $this->hasMany(Topup::class, 'id_user_bank', 'id_user');
    }

    public function kantin()
    {
        return $this->hasOne(Kantin::class, 'id_user_kantin', 'id_user');
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'id_user', 'id_user');
    }

    public function withdrawal()
    {
        return $this->hasMany(Withdrawal::class, 'id_user_bank', 'id_user');
    }
}
