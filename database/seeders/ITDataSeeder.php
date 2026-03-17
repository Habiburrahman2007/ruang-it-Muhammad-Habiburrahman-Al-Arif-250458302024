<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

class ITDataSeeder extends Seeder
{
    public function run(): void
    {
        // Temukan user untuk dijadikan author artikel
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'System Admin',
                'email' => 'admin@system.local',
                'password' => bcrypt('password'),
                'profession' => 'Admin',
                'role' => 'admin'
            ]);
        }

        // Hapus semua artikel dan kategori (Karena diminta hapus yang tidak relevan dengan IT, asumsikan semua di tabel sekarang dihapus dan diganti yang IT saja)
        // Kita gunakan DB::statement untuk disable/enable foreign key checking
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('articles')->truncate(); // akan menghapus semua komentar dan likes juga jika cascade
        DB::table('categories')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $itCategories = [
            [
                'name' => 'Artificial Intelligence',
                'color' => '#FF5733',
                'articles' => [
                    'Mengenal Dasar-Dasar Machine Learning',
                    'Perkembangan AI di Tahun 2026',
                    'Penerapan Neural Network dalam Kehidupan Sehari-hari'
                ]
            ],
            [
                'name' => 'Cybersecurity',
                'color' => '#33FF57',
                'articles' => [
                    'Cara Melindungi Data Pribadi di Internet',
                    'Ancaman Malware Terbaru dan Cara Mengatasinya',
                    'Pentingnya Enkripsi dalam Komunikasi Digital'
                ]
            ],
            [
                'name' => 'Web Development',
                'color' => '#3357FF',
                'articles' => [
                    'Mengenal Laravel sebagai Framework PHP Masa Kini',
                    'Tips Membangun RESTful API yang Efisien',
                    'Panduan Belajar ReactJS untuk Pemula'
                ]
            ],
            [
                'name' => 'Networking',
                'color' => '#FF33A8',
                'articles' => [
                    'Pemahaman Dasar Tentang Protokol TCP/IP',
                    'Cara Konfigurasi Router MikroTik',
                    'Mengenal Arsitektur Jaringan Client-Server'
                ]
            ],
            [
                'name' => 'Cloud Computing',
                'color' => '#FFBD33',
                'articles' => [
                    'Keuntungan Menggunakan Layanan AWS untuk Startup',
                    'Perbedaan antara IaaS, PaaS, dan SaaS',
                    'Memahami Konsep Containerization dengan Docker'
                ]
            ]
        ];

        foreach ($itCategories as $catData) {
            $category = Category::create([
                'name' => $catData['name'],
                'color' => $catData['color'],
            ]);

            foreach ($catData['articles'] as $articleTitle) {
                Article::create([
                    'category_id' => $category->id,
                    'user_id' => $user->id,
                    'title' => $articleTitle,
                    'slug' => Str::slug($articleTitle),
                    'content' => 'Ini adalah konten dummy untuk artikel "' . $articleTitle . '". Konten ini dibuat sebagai bagian dari data dummy untuk keperluan pengembangan sistem informasi berbasis IT. Dalam artikel ini akan dibahas mengenai berbagai macam konsep dasar dan pemahaman yang mendalam mengenai topik yang sedang diutarakan. Membaca artikel ini diharapkan dapat menambah wawasan pembaca.',
                    'status' => 'active',
                    'image' => null, // tanpa gambar
                ]);
            }
        }
    }
}
