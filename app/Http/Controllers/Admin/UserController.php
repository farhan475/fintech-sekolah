<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserManagementService $usermanagementService)
    {
        $this->userService = $usermanagementService;
    }

    public function index()
    {
        $users = User::paginate(10); // Menggunakan paginate untuk menghindari masalah memori jika data banyak
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        // Hanya menampilkan view dengan form kosong
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru ke dalam database.
     */
    public function store(Request $request)
    {
        // 1. Validasi (password wajib diisi saat membuat user baru)
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan mencocokkan dengan 'password_confirmation'
            'role' => ['required', Rule::in(['bank', 'kantin', 'siswa', 'administrator'])],
            'nisn' => 'required_if:role,siswa|nullable|string|max:20|unique:siswas,nisn',
            'kelas' => 'required_if:role,siswa|nullable|string|max:50',
        ]);

        // 2. Buat User baru (tanpa menggunakan service, karena logikanya sedikit berbeda)
        // Kita bisa pindahkan ini ke service nanti jika diperlukan
        $user = new User();
        $user->nama = $validatedData['nama'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->role = $validatedData['role'];
        $user->save();

        // 3. Jika rolenya siswa, buat data siswa terkait
        if ($validatedData['role'] === 'siswa') {
            $user->siswa()->create([
                'nisn' => $validatedData['nisn'],
                'kelas' => $validatedData['kelas'],
            ]);
        }
        
        // 4. Redirect ke halaman daftar pengguna dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }


    public function edit(User $user)
    {
            $user->load('siswa'); // Load relasi siswa jika ada 
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan email unik, tapi abaikan untuk user yang sedang diedit
                Rule::unique('users')->ignore($user->id_user, 'id_user'), 
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['bank', 'kantin', 'siswa', 'administrator'])],
            
            // NISN dan Kelas hanya wajib jika rolenya adalah 'siswa'
            'nisn' => 'required_if:role,siswa|nullable|string|max:20',
            'kelas' => 'required_if:role,siswa|nullable|string|max:50',
        ]);
        
        // Menggunakan transaction untuk memastikan semua query berhasil atau tidak sama sekali
        DB::transaction(function () use ($user, $validated, $request) {
            // 2. Update data User
            $user->nama = $validated['nama'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];

            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();

            // 3. Update atau Buat data Siswa jika rolenya 'siswa'
            if ($validated['role'] === 'siswa') {
                // updateOrCreate akan mengupdate siswa jika ada, atau membuat baru jika belum ada
                $user->siswa()->updateOrCreate(
                    ['user_id' => $user->id_user], // Kunci untuk mencari
                    [                                // Data untuk diupdate/dibuat
                        'nisn' => $validated['nisn'],
                        'kelas' => $validated['kelas'],
                    ]
                );
            } else {
                // Jika role diubah dari siswa ke role lain, hapus data siswa terkait
                if ($user->siswa) {
                    $user->siswa->delete();
                }
            }
        });

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('admin.users.index', $user->id_user)->with('success', 'Data pengguna berhasil diperbarui!');
    }



    public function destroy(User $user)
    {
         try {
            // Jika ada relasi siswa, akan otomatis terhapus jika Anda
            // mengatur onDelete('cascade') di migrasi. Jika tidak, hapus manual dulu.
            if ($user->siswa) {
                $user->siswa->delete();
            }
            
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus pengguna.');
        }
    }
}
