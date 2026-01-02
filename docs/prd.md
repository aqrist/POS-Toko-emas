# Product Requirements Document (PRD)

## 1. Ringkasan Produk

Aplikasi **POS Toko Emas** adalah aplikasi kasir berbasis web (PWA) yang dibangun menggunakan **Laravel Breeze (Blade)** dengan pendekatan **offline-first**. Aplikasi ini dirancang untuk mendukung **jual-beli emas dan perhiasan**, memiliki **multi cabang**, **RBAC (Role-Based Access Control)**, serta mampu berjalan **tanpa internet** dengan mekanisme **sinkronisasi data otomatis** saat koneksi tersedia.

Target utama aplikasi ini adalah toko emas skala kecil hingga menengah dengan kebutuhan operasional harian yang stabil, cepat, dan aman.

---

## 2. Tujuan & Masalah yang Diselesaikan

### Tujuan

* Menyediakan sistem POS emas yang **tetap berjalan saat offline**
* Menyederhanakan pengelolaan harga emas dengan **1x input harga pasar**
* Mendukung operasional **multi cabang**
* Menjamin keamanan akses melalui **RBAC**
* Menghadirkan pengalaman modern dengan **PWA + shadCN UI**

### Masalah yang Diselesaikan

* Ketergantungan koneksi internet di toko
* Perhitungan harga emas yang kompleks dan rawan salah
* Data transaksi tidak sinkron antar cabang
* Tidak adanya pembatasan akses user berbasis peran

---

## 3. Target Pengguna

* **Kasir Cabang**: transaksi jual beli
* **Admin Cabang**: laporan & manajemen data cabang
* **Super Admin**: kontrol global (harga, cabang, user)
* **Owner / Management**: monitoring laporan

---

## 4. Teknologi & Arsitektur

### Stack Teknologi

* Backend: **Laravel 11**
* Auth: **Laravel Breeze (Blade)**
* Frontend UI: **shadCN (Blade compatible / Tailwind)**
* Database: MySQL / PostgreSQL
* Offline Storage: **IndexedDB / LocalStorage**
* Identifier: **UUID (v7 disarankan)**
* PWA: Service Worker + Web App Manifest

### Konsep Offline-First

* Semua transaksi disimpan ke **local storage (IndexedDB)** terlebih dahulu
* Setiap data memiliki:

  * `uuid`
  * `is_synced`
  * `synced_at`
* Background sync saat online
* Conflict resolution: **last-write-wins + log audit**

---

## 5. Fitur Utama

### 5.1 Autentikasi & RBAC

* Login / Logout
* Role:

  * Super Admin
  * Admin Cabang
  * Kasir
* Akses berbasis:

  * Role
  * Cabang

---

### 5.2 Manajemen Cabang (Multi Branch)

* CRUD Cabang
* Set cabang aktif saat login
* Set printer / device per cabang (future-ready)

---

### 5.3 Setup Harga Emas (Single Input)

* Input **Harga Pasar Emas (per gram)** hanya 1x
* Sistem otomatis menghitung:

  * Harga per kadar (24K, 23K, 22K, 18K, dll)
* Rumus tersimpan & terstandarisasi
* Berlaku global atau per cabang (opsional)

---

### 5.4 Master Data Kadar & Produk

* Master Kadar Emas

  * Nama Kadar
  * Persentase
* Jenis Produk:

  * Emas Batangan
  * Perhiasan

---

### 5.5 Transaksi Jual Emas

* Input berat (gram)
* Pilih kadar
* Harga otomatis
* Biaya tambahan (opsional)
* Metode pembayaran:

  * Cash
  * Transfer
* Cetak / share struk

---

### 5.6 Transaksi Beli Emas

* Input berat & kadar
* Potongan susut
* Harga beli otomatis
* Data supplier / customer

---

### 5.7 Manajemen Customer

* CRUD Customer
* Riwayat transaksi
* Identitas:

  * Nama
  * No HP
  * Alamat (opsional)

---

### 5.8 Laporan & Monitoring

* Laporan Harian
* Laporan Cabang
* Laporan Global (Super Admin)
* Filter:

  * Tanggal
  * Cabang
  * Jenis transaksi

---

### 5.9 Sinkronisasi Data

* Auto sync saat online
* Manual sync button
* Status indikator:

  * Offline
  * Syncing
  * Synced

---

## 6. PWA Requirements

* Installable (Add to Home Screen)
* Offline page fallback
* Cache:

  * App Shell
  * API response terakhir
* Background Sync
* Push Notification (future)

---

## 7. Non-Functional Requirements

* Performance: < 1s response (offline)
* Security:

  * CSRF
  * RBAC enforcement
* Reliability:

  * Tidak ada data hilang saat offline
* Scalability:

  * Siap > 50 cabang

---

## 8. Data Model (High Level)

* users (uuid, role_id, branch_id)
* branches (uuid)
* gold_prices (uuid, market_price)
* gold_levels (uuid, percentage)
* transactions (uuid, type, branch_id)
* transaction_items (uuid)
* customers (uuid)
* sync_logs (uuid, table, status)

---

## 9. Out of Scope (Versi 1)

* Integrasi ERP eksternal
* Akuntansi lengkap
* Multi currency

---

## 10. Success Metrics

* Aplikasi tetap usable 100% saat offline
* 0 data loss
* Sinkronisasi < 5 detik per 100 transaksi
* Kasir dapat input transaksi < 30 detik

---

## 11. Catatan Pengembangan

* Prioritaskan offline logic sebelum UI
* UUID wajib untuk semua tabel
* shadCN digunakan konsisten untuk UX modern
* Blade tetap modular & reusable

---

**Dokumen ini menjadi acuan utama pengembangan POS Toko Emas (PWA Offline-First).**
