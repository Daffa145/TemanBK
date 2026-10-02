<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Belajar',
                'badge_color' => 'indigo',
                'description' => 'Kesulitan memahami materi pelajaran, manajemen waktu belajar, motivasi belajar, dan persiapan ujian.',
            ],
            [
                'name' => 'Karier',
                'badge_color' => 'emerald',
                'description' => 'Eksplorasi minat kejuruan, persiapan magang/PKL, sertifikasi keahlian, dan kesiapan dunia kerja.',
            ],
            [
                'name' => 'Masa Depan',
                'badge_color' => 'purple',
                'description' => 'Perencanaan perguruan tinggi/kuliah, pemilihan jurusan, beasiswa, gap year, dan cita-cita jangka panjang.',
            ],
            [
                'name' => 'Permasalahan Sosial',
                'badge_color' => 'rose',
                'description' => 'Hubungan pertemanan, konflik sosial, bullying, kecemasan, emosi, dan keharmonisan keluarga.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                [
                    'slug' => Str::slug($cat['name']),
                    'badge_color' => $cat['badge_color'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
