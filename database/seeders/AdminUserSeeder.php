<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@fintech.com',
            'password' => Hash::make('password'), 
            'role' => 'administrator',
        ]);

        User::create([
            'nama' => 'Bank Sekolah A',
            'email' => 'bank@fintech.com',
            'password' => Hash::make('password'),
            'role' => 'bank',
        ]);

        User::create([
            'nama' => 'Kantin Jaya',
            'email' => 'kantin@fintech.com',
            'password' => Hash::make('password'),
            'role' => 'kantin',
        ]);

        User::create([
            'nama' => 'farhan',
            'email' => 'farhan@fintech.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}
