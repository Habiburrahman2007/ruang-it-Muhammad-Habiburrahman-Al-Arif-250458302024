# Final Project

## Deskripsi Singkat Aplikasi
Aplikasi ini adalah platform manajemen konten dan blogging berbasis web yang modern dan interaktif. Dibangun untuk memfasilitasi berbagai peran pengguna mulai dari pengunjung biasa, penulis konten, hingga administrator sistem. Aplikasi ini menyediakan antarmuka yang responsif dan pengalaman pengguna yang mulus menggunakan teknologi web terbaru.

## Fitur Utama

### 1. Tamu (Guest)
- **Landing Page**: Halaman sambutan yang informatif.
- **Baca Artikel**: Akses penuh untuk membaca artikel yang dipublikasikan.
- **Lihat Profil**: Melihat profil publik penulis.

### 2. Pengguna (User)
- **Dashboard**: Pusat kontrol pribadi untuk pengguna.
- **Manajemen Artikel**: Membuat, mengedit, dan mengelola artikel sendiri.
- **Manajemen Profil**: Mengatur informasi profil pribadi.

### 3. Admin
- **Statistik Dashboard**: Melihat ringkasan data dan aktivitas platform.
- **Manajemen Kategori**: Mengelola kategori artikel.
- **Kontrol Pengguna**: Mengelola pengguna terdaftar.
- **Kontrol Blog**: Moderasi dan pengelolaan seluruh konten artikel.
- **Kontrol Komentar**: Moderasi komentar pada artikel.

## Teknologi yang Digunakan

### Backend
- **Laravel 12**: Framework PHP modern yang kuat dan ekspresif.
- **PHP 8.3+**: Bahasa pemrograman server-side.
- **MySQL**: Sistem manajemen basis data.

### Frontend
- **Livewire 3.6**: Framework full-stack untuk antarmuka dinamis tanpa meninggalkan PHP.
- **TailwindCSS 4**: Framework CSS utility-first untuk desain yang cepat dan kustom.
- **Flowbite**: Komponen UI berbasis Tailwind CSS.
- **Mazer**: Template UI yang modern dan responsive berbasis Bootstrap.

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstal project ini di komputer lokal Anda:

1. **Clone Repository**
   ```bash
   git clone <repository_url>
   cd finalproject
   ```

2. **Instal Dependensi PHP**
   Pastikan Anda memiliki [Composer](https://getcomposer.org/) terinstal.
   ```bash
   composer install
   ```

3. **Instal Dependensi Node.js**
   Pastikan Anda memiliki [Node.js](https://nodejs.org/) dan NPM terinstal.
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file contoh konfigurasi dan buat file `.env` baru.
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database**
   Jalankan migrasi untuk membuat tabel database.
   ```bash
   php artisan migrate
   ```
   *(Opsional)* Jika ingin mengisi data awal (seeder):
   ```bash
   php artisan migrate --seed
   ```

## Cara Menjalankan Project

Untuk menjalankan aplikasi dalam mode pengembangan, gunakan perintah berikut:

```bash
npm run dev
```

Perintah ini akan menjalankan server Laravel, worker queue, dan Vite development server secara bersamaan.

Buka browser Anda dan akses alamat yang muncul di terminal (biasanya `http://127.0.0.1:8000`).
