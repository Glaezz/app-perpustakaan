# Aplikasi Perpustakaan

## Deskripsi

Aplikasi Perpustakaan adalah aplikasi web berbasis Laravel untuk membantu pengelolaan data perpustakaan secara terstruktur. Proyek ini menjadi dasar pengembangan fitur seperti pendataan buku, anggota, peminjaman, dan pengembalian buku.

## Nama Proyek

**Aplikasi Perpustakaan** (`app-perpustakaan`)

## Tujuan

Proyek ini dibuat untuk:

- membangun sistem informasi perpustakaan berbasis web;
- memudahkan pengelolaan data buku dan anggota;
- membantu pencatatan transaksi peminjaman dan pengembalian; serta
- menjadi proyek pembelajaran penerapan framework Laravel.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- Composer
- Node.js dan npm

## Menjalankan Secara Lokal

Pastikan PHP 8.2 atau lebih baru, Composer, MySQL, Node.js, dan npm sudah
terpasang.

### 1. Clone repository

```bash
git clone https://github.com/Glaezz/app-perpustakaan.git
cd app-perpustakaan
```

### 2. Install dependency

```bash
composer install
npm install
```

### 3. Setup `.env`

Salin file environment contoh, lalu buat application key:

```bash
cp .env.example .env
php artisan key:generate
```

Pada Windows PowerShell, gunakan `Copy-Item .env.example .env` sebagai
pengganti perintah `cp`.

Buka `.env` dan sesuaikan konfigurasi database MySQL. Contoh konfigurasi yang
digunakan aplikasi ini:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Tambahkan atau pastikan URL API internal berikut tersedia:

```dotenv
INTERNAL_API_URL=http://127.0.0.1:8011
```

Buat database `db_perpustakaan` terlebih dahulu di MySQL jika belum ada.

### 4. Migrate dan seed database

```bash
php artisan migrate --seed
```

### 5. Jalankan dua server Laravel

Dashboard mengambil statistik dan halaman Laporan mengambil data melalui API
internal. Karena itu, jalankan dua proses server pada dua terminal berbeda.

**Terminal 1 - aplikasi utama:**

```bash
php artisan serve --port=8000
```

**Terminal 2 - server API internal:**

```bash
php artisan serve --port=8011
```

Buka aplikasi utama di [http://127.0.0.1:8000](http://127.0.0.1:8000).
Jangan menutup server pada port `8011` selama menggunakan Dashboard atau
Laporan.

## Dokumentasi API

Dokumentasi lengkap seluruh endpoint REST API (`books`, `members`, `loans`, `stats`) beserta contoh response nyata dan status code-nya tersedia di [api.md](./api.md).
