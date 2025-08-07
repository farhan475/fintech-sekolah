<?php

namespace App\Services;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementService
{
    public function createUser(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($user->role == 'siswa') {
                Siswa::create([
                    'id_user' => $user->id_user,
                    'nisn' => $data['nisn'],
                    'kelas' => $data['kelas'],
                    'saldo' => 0.00, // Saldo awal siswa
                ]);
            }
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

     public function updateUser(User $user, array $validatedData): User
    {
        // Gunakan DB Transaction untuk memastikan integritas data.
        // Jika ada satu query yang gagal, semua perubahan akan dibatalkan.
        DB::transaction(function () use ($user, $validatedData) {
            
            // 1. Update data dasar pada model User
            $user->nama = $validatedData['nama'];
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];

            // 2. Hanya update password jika field password diisi
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            
            $user->save();

            // 3. Kelola data Siswa berdasarkan role
            if ($validatedData['role'] === 'siswa') {
                // Jika rolenya adalah 'siswa', update atau buat data siswa baru.
                // 'updateOrCreate' sangat efisien untuk kasus ini.
                $user->siswa()->updateOrCreate(
                    ['user_id' => $user->id_user], // Kondisi untuk mencari data siswa
                    [                                // Data yang akan di-update atau dibuat
                        'nisn' => $validatedData['nisn'],
                        'kelas' => $validatedData['kelas'],
                    ]
                );
            } else {
                // Jika rolenya BUKAN siswa, pastikan tidak ada data siswa yang terkait.
                // Jika ada (misalnya, role diubah dari siswa ke admin), hapus data siswa tersebut.
                if ($user->siswa) {
                    $user->siswa->delete();
                }
            }
        });

        return $user;
    }



    public function deleteUser(User $user)
    {
        // Relasi onDelete('cascade') di migrasi akan menangani penghapusan siswa terkait
        return $user->delete();
    }

    public function getAllUsers()
    {
        return User::all();
    }
}