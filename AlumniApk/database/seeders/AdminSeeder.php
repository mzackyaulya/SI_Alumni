<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek kalau belum ada admin
        if (!User::where('email', 'admin@sekolahku.test')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@sekolahku.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin', // kolom role dari migration yang kamu buat
                'email_verified_at' => now(),
            ]);

            echo "✅ Akun admin berhasil dibuat: admin@sekolahku.test / admin123\n";
        } else {
            echo "Akun admin sudah ada.\n";
        }
    }
}
