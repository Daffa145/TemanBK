<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $this->call(CategorySeeder::class);

        // 2. Demo Admin
        User::firstOrCreate(
            ['email' => 'admin@temanbk.sch.id'],
            [
                'name' => 'Administrator BK',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'no_hp' => '081234567890',
            ]
        );

        // 3. Demo Guru BK 1 (Ready)
        User::firstOrCreate(
            ['email' => 'budi.bk@temanbk.sch.id'],
            [
                'name' => 'Drs. Budi Santoso, M.Pd (Guru BK)',
                'password' => Hash::make('password'),
                'role' => 'guru_bk',
                'nip' => '198001012005011001',
                'spesialisasi_kategori' => 'Belajar, Karier',
                'is_ready' => true,
                'no_hp' => '081298765431',
            ]
        );

        // 4. Demo Guru BK 2 (Ready)
        User::firstOrCreate(
            ['email' => 'siti.bk@temanbk.sch.id'],
            [
                'name' => 'Siti Nurhaliza, S.Psi (Guru BK)',
                'password' => Hash::make('password'),
                'role' => 'guru_bk',
                'nip' => '198505122010012003',
                'spesialisasi_kategori' => 'Permasalahan Sosial, Belajar',
                'is_ready' => true,
                'no_hp' => '081298765432',
            ]
        );

        // 5. Demo Guru BK 3 (Not Ready)
        User::firstOrCreate(
            ['email' => 'ahmad.bk@temanbk.sch.id'],
            [
                'name' => 'Ahmad Fauzi, S.Pd., Kons. (Guru BK)',
                'password' => Hash::make('password'),
                'role' => 'guru_bk',
                'nip' => '199002142015021004',
                'spesialisasi_kategori' => 'Masa Depan, Karier',
                'is_ready' => false,
                'no_hp' => '081298765433',
            ]
        );

        // 6. Demo Siswa
        User::firstOrCreate(
            ['email' => 'siswa@temanbk.sch.id'],
            [
                'name' => 'Muhammad Rizky Pratama',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'nis' => '20261001',
                'kelas' => 'XII RPL 1',
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'no_hp' => '081345678901',
            ]
        );
    }
}
