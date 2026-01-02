# POS Toko Emas

Aplikasi POS Toko Emas adalah sistem kasir berbasis web (PWA) untuk transaksi jual-beli emas dan perhiasan. Aplikasi ini dirancang dengan pendekatan offline-first, mendukung multi cabang, serta menerapkan RBAC agar akses fitur sesuai peran pengguna. Ketika koneksi tersedia, data akan disinkronkan otomatis.

## Fitur Utama

- Autentikasi dan RBAC (Super Admin, Admin Cabang, Kasir).
- Manajemen cabang (multi branch).
- Setup harga emas satu kali input harga pasar.
- Master data kadar emas dan jenis produk.
- Transaksi jual dan beli emas dengan perhitungan otomatis.
- Manajemen customer dan riwayat transaksi.
- Laporan harian, per cabang, dan global.
- Sinkronisasi data otomatis saat online.
- Dukungan PWA (installable, offline fallback, service worker).

## Teknologi

- Laravel 12 + Laravel Breeze (Blade).
- Tailwind CSS v4.
- PWA (Service Worker + Web App Manifest).
- IndexedDB / LocalStorage untuk mode offline.
- Database MySQL atau PostgreSQL.

## Prasyarat

- PHP 8.4+
- Composer
- Node.js + npm
- Database (MySQL atau PostgreSQL)

## Setup

Jalankan perintah berikut dari root project:

```bash
composer run setup
```

Perintah di atas akan:

- Menginstal dependency PHP dan Node.
- Membuat file `.env` dari `.env.example`.
- Generate app key.
- Menjalankan migrasi database.
- Build aset frontend.

Jika ingin setup manual:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

## Konfigurasi

- Sesuaikan koneksi database di file `.env`.
- Pastikan `APP_URL` sesuai dengan environment lokal Anda.

## Menjalankan Aplikasi

Mode pengembangan (server, queue, log, dan Vite):

```bash
composer run dev
```

Atau jalankan layanan secara terpisah:

```bash
php artisan serve
npm run dev
```

## Build Frontend

```bash
npm run build
```

## Testing

Jalankan semua test:

```bash
composer run test
```

Atau gunakan perintah Laravel:

```bash
php artisan test
```

## Struktur Direktori

- `app/` - logika aplikasi.
- `resources/` - view Blade, CSS, dan JS.
- `routes/` - definisi rute web dan API.
- `database/` - migrasi, seeder, dan factory.
- `public/` - file publik dan aset PWA.

## Lisensi

Aplikasi ini menggunakan lisensi MIT.
