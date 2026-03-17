<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Like;

class ItDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Membuat user dummy...');

        // ---------------------------------------------------------------
        // 1. USERS  (role di-guarded, jadi pakai forceFill + save)
        // ---------------------------------------------------------------
        $usersData = [
            [
                'name'          => 'Budi Santoso',
                'email'         => 'budi@ruangit.id',
                'password'      => Hash::make('password123'),
                'profession'    => 'Backend Developer',
                'bio'           => 'Suka ngulik Laravel dan API sejak 2019.',
                'photo_profile' => null,
                'role'          => 'user',
            ],
            [
                'name'          => 'Dewi Rahayu',
                'email'         => 'dewi@ruangit.id',
                'password'      => Hash::make('password123'),
                'profession'    => 'Frontend Developer',
                'bio'           => 'Passionate dengan React dan Tailwind CSS.',
                'photo_profile' => null,
                'role'          => 'user',
            ],
            [
                'name'          => 'Rizky Pratama',
                'email'         => 'rizky@ruangit.id',
                'password'      => Hash::make('password123'),
                'profession'    => 'DevOps Engineer',
                'bio'           => 'Mengotomasi pipeline CI/CD sejak 2020.',
                'photo_profile' => null,
                'role'          => 'user',
            ],
            [
                'name'          => 'Siti Nurbaya',
                'email'         => 'siti@ruangit.id',
                'password'      => Hash::make('password123'),
                'profession'    => 'Data Scientist',
                'bio'           => 'Tertarik pada machine learning dan analitik data.',
                'photo_profile' => null,
                'role'          => 'user',
            ],
        ];

        $users = [];
        foreach ($usersData as $data) {
            // Cek apakah email sudah ada agar seeder bisa di-run ulang
            $existing = User::where('email', $data['email'])->first();
            if ($existing) {
                $users[] = $existing;
                continue;
            }

            // forceFill + save agar 'role' bisa terisi meski di-guarded
            $role = $data['role'];
            unset($data['role']);
            $user = new User();
            $user->fill($data);      // fill fieldable
            $user->role = $role;     // set guarded field langsung
            $user->save();
            $users[] = $user;
        }

        $this->command->info('User selesai. Membuat kategori...');

        // ---------------------------------------------------------------
        // 2. CATEGORIES
        // ---------------------------------------------------------------
        $categoriesData = [
            ['name' => 'Programming',           'color' => '#3B82F6'],
            ['name' => 'Networking',             'color' => '#10B981'],
            ['name' => 'Cyber Security',         'color' => '#EF4444'],
            ['name' => 'Artificial Intelligence','color' => '#8B5CF6'],
            ['name' => 'Cloud Computing',        'color' => '#F59E0B'],
            ['name' => 'Data Science',           'color' => '#14B8A6'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['name']] = Category::firstOrCreate(
                ['name' => $cat['name']],
                ['color' => $cat['color']]
            );
        }

        $this->command->info('Kategori selesai. Membuat artikel...');

        // ---------------------------------------------------------------
        // 3. ARTICLES
        // ---------------------------------------------------------------
        $articlesData = [
            [
                'title'    => 'Mengenal Dasar-dasar React JS untuk Pemula',
                'category' => 'Programming',
                'user'     => 'dewi@ruangit.id',
                'content'  => 'React JS adalah library JavaScript populer yang dikembangkan oleh Meta (Facebook) untuk membangun antarmuka pengguna yang interaktif dan reaktif. Konsep utama React meliputi komponen (component), props, dan state. Komponen adalah blok bangunan utama sebuah aplikasi React—setiap bagian UI bisa dibagi menjadi komponen kecil yang dapat digunakan ulang.

Untuk memulai, install Node.js lalu jalankan perintah `npx create-react-app nama-proyek`. React menggunakan JSX, yaitu sintaks yang mirip HTML namun ditulis di dalam JavaScript. Dengan Virtual DOM, React hanya me-render ulang bagian yang berubah sehingga performa aplikasi tetap optimal.

Pelajari hooks seperti useState untuk mengelola state lokal dan useEffect untuk side-effects seperti fetching data dari API. Setelah menguasai dasar ini, Anda sudah siap membangun aplikasi web modern yang dinamis!',
            ],
            [
                'title'    => 'Membangun REST API dengan Laravel 11 dan Sanctum',
                'category' => 'Programming',
                'user'     => 'budi@ruangit.id',
                'content'  => 'Laravel 11 hadir dengan struktur direktori yang lebih ramping. File `bootstrap/app.php` kini menjadi pusat konfigurasi middleware dan exception handler. Pada artikel ini kita akan membuat REST API lengkap dengan autentikasi token menggunakan Laravel Sanctum.

Pertama, install Sanctum: `composer require laravel/sanctum`. Tambahkan `HasApiTokens` pada model User. Buat routes di `routes/api.php` dengan grup middleware `auth:sanctum` untuk endpoint yang membutuhkan autentikasi.

Endpoint login mengembalikan token yang disimpan di client, lalu dikirim via header `Authorization: Bearer {token}` pada setiap request. Gunakan `$request->user()` untuk mendapatkan data user yang sedang login. Pastikan semua response menggunakan format JSON yang konsisten agar mudah dikonsumsi oleh client mobile maupun web.',
            ],
            [
                'title'    => 'Apa itu Topologi Jaringan dan Jenis-jenisnya?',
                'category' => 'Networking',
                'user'     => 'rizky@ruangit.id',
                'content'  => 'Topologi jaringan adalah desain fisik atau logis yang menggambarkan bagaimana perangkat dalam jaringan saling terhubung. Pilihan topologi yang tepat sangat memengaruhi performa, skalabilitas, dan biaya pemeliharaan jaringan.

**Topologi Star** menggunakan switch/hub sebagai pusat. Mudah di-troubleshoot namun menjadi single point of failure. **Topologi Bus** menghubungkan semua perangkat ke satu kabel utama—murah tapi rentan gangguan. **Topologi Ring** melewatkan data secara berurutan; jika satu node gagal, seluruh jaringan terdampak. **Topologi Mesh** menghubungkan setiap node ke semua node lain—sangat andal tapi mahal.

Untuk jaringan perusahaan modern, topologi **Hybrid** (gabungan Star dan Mesh) adalah pilihan populer karena menyeimbangkan keandalan dan biaya.',
            ],
            [
                'title'    => 'Ancaman Phishing di Era Digital dan Cara Mencegahnya',
                'category' => 'Cyber Security',
                'user'     => 'budi@ruangit.id',
                'content'  => 'Phishing adalah serangan rekayasa sosial di mana penyerang menyamar sebagai entitas terpercaya untuk mencuri informasi sensitif seperti password, nomor kartu kredit, atau data pribadi. Serangan ini umumnya dilakukan melalui email, SMS (smishing), atau telepon (vishing).

Ciri-ciri email phishing: alamat pengirim mencurigakan, terdapat tautan ke URL yang mirip tapi bukan situs asli, adanya rasa urgensi ("Akun Anda akan diblokir dalam 24 jam!"), dan permintaan informasi sensitif.

**Cara mencegahnya:** Aktifkan autentikasi dua faktor (2FA) di semua akun penting. Selalu periksa URL sebelum memasukkan kredensial. Gunakan password manager. Perbarui software secara rutin. Edukasi diri dan tim tentang taktik phishing terbaru. Jangan pernah klik tautan dari email yang tidak diharapkan.',
            ],
            [
                'title'    => 'Perkembangan Generative AI: dari ChatGPT hingga Gemini',
                'category' => 'Artificial Intelligence',
                'user'     => 'siti@ruangit.id',
                'content'  => 'Generative AI telah mengubah cara kita bekerja secara fundamental. Model bahasa besar (Large Language Model / LLM) seperti GPT-4 dari OpenAI, Claude dari Anthropic, dan Gemini dari Google mampu menghasilkan teks, kode, gambar, hingga audio yang sangat mendekati karya manusia.

Teknologi di baliknya adalah arsitektur **Transformer** yang diperkenalkan Google pada 2017 melalui makalah "Attention is All You Need". Dengan mekanisme self-attention, model dapat memahami konteks jangka panjang dalam teks secara efisien.

Pada 2026, LLM multimodal sudah menjadi standar—model dapat memproses teks, gambar, audio, dan video sekaligus. Di dunia kerja, Generative AI digunakan untuk menulis kode, membuat dokumen, menganalisis data, hingga menjadi asisten customer service. Penting bagi profesional IT untuk memahami cara kerja dan keterbatasan AI agar dapat memanfaatkannya secara bertanggung jawab.',
            ],
            [
                'title'    => 'Panduan Memilih Layanan Cloud: AWS vs Google Cloud vs Azure',
                'category' => 'Cloud Computing',
                'user'     => 'rizky@ruangit.id',
                'content'  => 'Memilih penyedia cloud yang tepat adalah keputusan strategis yang memengaruhi biaya, performa, dan skalabilitas aplikasi Anda.

**AWS (Amazon Web Services)** adalah market leader dengan ekosistem layanan terluas—lebih dari 200 layanan. Cocok untuk enterprise yang membutuhkan fleksibilitas tinggi. **Google Cloud Platform (GCP)** unggul di BigQuery (analitik data berskala besar) dan Kubernetes (GKE). Pilihan tepat untuk workload AI/ML dan data analytics. **Microsoft Azure** terintegrasi rapat dengan ekosistem Microsoft (Office 365, Active Directory) dan populer di kalangan enterprise Windows.

Faktor yang perlu dipertimbangkan: **harga** (pakai kalkulator pricing masing-masing provider), **ketersediaan region** (AWS memiliki region terbanyak), **SLA uptime**, **dukungan compliance** (ISO, SOC2, GDPR), dan **ekosistem partner**. Banyak perusahaan mengadopsi strategi **multi-cloud** untuk menghindari vendor lock-in.',
            ],
            [
                'title'    => 'Tips Memulai Karir sebagai Data Analyst di 2026',
                'category' => 'Data Science',
                'user'     => 'siti@ruangit.id',
                'content'  => 'Data Analyst masih menjadi salah satu profesi paling diminati di bidang teknologi. Perusahaan dari berbagai industri membutuhkan kemampuan mengolah data menjadi insight yang dapat mendorong keputusan bisnis.

**Skill teknis wajib:** SQL (untuk query database), Python atau R (untuk analisis dan visualisasi), serta tools BI seperti Tableau, Power BI, atau Looker Studio. Pahami juga konsep statistik dasar: mean, median, standar deviasi, distribusi, dan uji hipotesis.

**Skill non-teknis:** Kemampuan komunikasi sangat penting—kamu harus bisa menjelaskan temuan data kepada stakeholder non-teknis. Rasa ingin tahu (curiosity) dan kemampuan problem-solving juga krusial.

**Langkah memulai:** Buat akun di Kaggle dan latih skill dengan dataset publik. Ikuti kursus online (Coursera, Dicoding, RevoU). Bangun portofolio berisi minimal 3 proyek analisis data. Aktif di LinkedIn dan daftarkan diri ke lowongan entry-level atau magang.',
            ],
            [
                'title'    => 'Docker untuk Developer: Containerize Aplikasi Laravel Anda',
                'category' => 'Programming',
                'user'     => 'budi@ruangit.id',
                'content'  => 'Docker memungkinkan developer mengemas aplikasi beserta semua dependensinya ke dalam unit yang disebut **container**. Container berjalan secara konsisten di semua lingkungan—dari laptop developer hingga server produksi—menghilangkan masalah klasik "it works on my machine".

Untuk containerize aplikasi Laravel, buat file `Dockerfile` yang mendefinisikan image PHP dengan ekstensi yang dibutuhkan, kemudian file `docker-compose.yml` untuk orkestrasi layanan: PHP-FPM, Nginx, MySQL, dan Redis.

Perintah dasar yang perlu dikuasai: `docker build` (membuat image), `docker run` (menjalankan container), `docker-compose up -d` (menjalankan semua layanan), dan `docker exec -it {container} bash` (masuk ke dalam container). Gunakan **volume** untuk persistent data agar database tidak hilang saat container di-restart.',
            ],
            [
                'title'    => 'Memahami DNS: Cara Kerja Domain Name System',
                'category' => 'Networking',
                'user'     => 'rizky@ruangit.id',
                'content'  => 'DNS (Domain Name System) adalah sistem yang menerjemahkan nama domain seperti `ruangit.id` menjadi alamat IP seperti `103.22.90.5` yang dapat dipahami komputer. Tanpa DNS, kita harus menghafal alamat IP setiap website.

**Alur resolusi DNS:**
1. Browser mengecek cache lokal → jika ada, proses selesai.
2. Jika tidak, query dikirim ke **DNS Resolver** (biasanya dari ISP atau 8.8.8.8/Google).
3. Resolver bertanya ke **Root Nameserver** tentang TLD (`.id`, `.com`, dll).
4. Root Nameserver mengarahkan ke **TLD Nameserver**.
5. TLD Nameserver menghasilkan **Authoritative Nameserver** domain tersebut.
6. Authoritative Nameserver memberikan IP yang dicari.

Jenis record DNS penting: **A** (IPv4), **AAAA** (IPv6), **CNAME** (alias), **MX** (mail server), **TXT** (verifikasi domain), dan **NS** (nameserver). Pahami TTL (Time to Live) agar tahu berapa lama cache DNS berlaku.',
            ],
            [
                'title'    => 'Pengantar Ethical Hacking: Penetration Testing dengan Kali Linux',
                'category' => 'Cyber Security',
                'user'     => 'budi@ruangit.id',
                'content'  => 'Ethical hacking atau penetration testing (pentest) adalah praktik menguji keamanan sistem dengan izin resmi pemiliknya untuk menemukan celah sebelum dieksploitasi oleh penyerang jahat. Profesi ini sangat dicari dan dikenal sebagai **penetration tester** atau **security researcher**.

**Kali Linux** adalah distribusi Linux yang dirancang khusus untuk keamanan siber, dilengkapi ratusan tools seperti Nmap (network scanning), Metasploit (exploitation framework), Burp Suite (web app testing), Wireshark (packet analysis), dan John the Ripper (password cracking).

**Tahapan pentest:** Reconnaissance (pengumpulan informasi) → Scanning & Enumeration → Exploitation → Post-Exploitation → Reporting. Selalu lakukan pentest hanya pada sistem yang Anda miliki izinnya. Sertifikasi relevan: CEH, OSCP, CompTIA Security+.',
            ],
        ];

        $createdArticles = [];
        foreach ($articlesData as $data) {
            $user     = collect($users)->firstWhere('email', $data['user']);
            $category = $categories[$data['category']];

            // Cek jika artikel dengan judul sama sudah ada
            $existing = Article::where('title', $data['title'])->first();
            if ($existing) {
                $createdArticles[] = $existing;
                continue;
            }

            $article = Article::create([
                'user_id'     => $user->id,
                'category_id' => $category->id,
                'title'       => $data['title'],
                'content'     => $data['content'],
                'image'       => null,
                'status'      => 'active',
                // slug otomatis dari booted() di model Article
            ]);

            $createdArticles[] = $article;
        }

        $this->command->info('Artikel selesai. Membuat komentar dan like...');

        // ---------------------------------------------------------------
        // 4. COMMENTS & LIKES
        // ---------------------------------------------------------------
        $commentPool = [
            'Artikel yang sangat informatif! Langsung saya bookmark.',
            'Penjelasannya mudah dipahami, cocok untuk pemula seperti saya.',
            'Boleh request topik lanjutannya, min? Sangat bermanfaat!',
            'Akhirnya ada artikel yang njelaskin topik ini dengan jelas. Makasih!',
            'Saya udah coba praktekin langsung dan berhasil. Terima kasih tutorialnya!',
            'Mantap! Langsung kepake buat tugas kuliah saya.',
            'Satu pertanyaan: apakah ini juga berlaku untuk versi terbaru?',
            'Kontennya padat dan berbobot. Jarang ada yang nulis sedetail ini.',
            'Sangat membantu! Saya share ke teman-teman sekelas deh.',
            'Topik yang relevan banget dengan kebutuhan dunia kerja saat ini.',
            'Kalau ada source code lengkapnya, bisa dishare juga nggak min?',
            'Penjelasan step by step-nya enak banget diikutin. Good job!',
        ];

        $usersCollection = collect($users);

        foreach ($createdArticles as $article) {
            // LIKES: antara 1 sampai semua user
            $likeCount   = rand(1, count($users));
            $likingUsers = $usersCollection->shuffle()->take($likeCount);

            foreach ($likingUsers as $user) {
                Like::firstOrCreate([
                    'user_id'    => $user->id,
                    'article_id' => $article->id,
                ]);
            }

            // COMMENTS: antara 2 sampai 5 per artikel
            $commentCount = rand(2, 5);
            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'user_id'    => $usersCollection->random()->id,
                    'article_id' => $article->id,
                    'content'    => $commentPool[array_rand($commentPool)],
                    'is_hidden'  => false,
                ]);
            }
        }

        $this->command->info('');
        $this->command->info('✅ Data dummy IT berhasil di-seed!');
        $this->command->info('   - ' . count($users) . ' users');
        $this->command->info('   - ' . count($categories) . ' kategori');
        $this->command->info('   - ' . count($createdArticles) . ' artikel');
        $this->command->info('   - Like & komentar untuk setiap artikel');
    }
}
