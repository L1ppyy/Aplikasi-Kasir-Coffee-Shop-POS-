# 🏪 POSMaster — Aplikasi Kasir Point of Sale

Aplikasi kasir Point of Sale (POS) lengkap berbasis **Laravel 11** dengan fitur admin dashboard, manajemen produk, laporan penjualan, dan kasir real-time.

---

## ✨ Fitur Lengkap

### 🛒 Kasir (POS Interface)
- Tampilan produk dengan grid yang responsif
- Filter produk per kategori dan pencarian real-time
- Keranjang belanja interaktif (tambah, kurang, hapus)
- Input nama dan telepon pelanggan
- Diskon per item / diskon total (nominal atau persentase)
- Pajak otomatis sesuai setting
- 5 metode pembayaran: Tunai, QRIS, Debit, Kredit, Transfer
- Hitung kembalian otomatis + quick cash buttons
- Cetak struk / receipt setelah transaksi
- Update stok otomatis setelah transaksi

### 📊 Admin Dashboard
- Statistik penjualan hari ini & bulan ini
- Grafik penjualan 7 hari terakhir
- Breakdown metode pembayaran
- Transaksi terbaru
- Alert produk stok menipis
- Top produk terlaris bulan ini

### 📦 Manajemen Produk
- CRUD lengkap (Create, Read, Update, Delete)
- Upload gambar produk
- Kategori dengan warna dan emoji icon
- SKU & Barcode
- Harga beli & harga jual
- Stok minimum dengan alert
- Penyesuaian stok (masuk/keluar/adjustment)
- Riwayat pergerakan stok

### 🏷️ Manajemen Kategori
- CRUD kategori dengan warna dan emoji
- Jumlah produk per kategori

### 🧾 Manajemen Transaksi
- Daftar semua transaksi dengan filter
- Detail transaksi lengkap
- Update status transaksi
- Pembatalan transaksi (otomatis restore stok)

### 📈 Laporan
- Laporan penjualan per periode
- Grafik penjualan harian
- Top produk terlaris
- Breakdown metode pembayaran
- Laporan pengeluaran
- Estimasi laba bersih

### 💸 Pengeluaran
- Catat pengeluaran operasional
- Kategori: operasional, gaji, sewa, utilitas, bahan, lainnya
- Filter by tanggal & kategori

### 👥 Manajemen Pengguna
- CRUD pengguna admin dan kasir
- Hak akses berbeda (admin vs kasir)
- Aktif/nonaktif akun

### ⚙️ Pengaturan
- Nama & info toko
- Upload logo
- Pajak default
- Pesan footer struk
- Mata uang

---

## 🚀 Cara Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Web server (Apache/Nginx) atau gunakan `php artisan serve`

### Langkah Instalasi

**1. Extract dan masuk ke folder proyek**
```bash
cd posmaster
```

**2. Install dependencies**
```bash
composer install
```

**3. Copy file environment**
```bash
cp .env.example .env
```

**4. Generate application key**
```bash
php artisan key:generate
```

**5. Konfigurasi database di `.env`**
```env
DB_DATABASE=pos_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

**6. Buat database di MySQL**
```sql
CREATE DATABASE pos_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**7. Jalankan migrasi dan seeder**
```bash
php artisan migrate --seed
```

**8. Buat storage link**
```bash
php artisan storage:link
```

**9. Jalankan server**
```bash
php artisan serve
```

**10. Buka browser**: `http://localhost:8000`

---

## 🔑 Akun Default

| Role  | Email            | Password  |
|-------|------------------|-----------|
| Admin | admin@pos.com    | password  |
| Kasir | kasir@pos.com    | password  |

---

## 📁 Struktur Folder Penting

```
pos-laravel/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          ← Controller admin
│   │   ├── AuthController.php
│   │   └── CashierController.php
│   ├── Models/             ← Semua model
│   └── Http/Middleware/
├── database/
│   ├── migrations/         ← Struktur tabel
│   └── seeders/            ← Data awal
├── resources/views/
│   ├── admin/              ← Halaman admin
│   ├── cashier/            ← Halaman kasir
│   ├── auth/               ← Login page
│   └── layouts/            ← Template layout
└── routes/web.php          ← Semua routing
```

---

## 📸 Tampilan Aplikasi

- **Login**: Halaman login modern dengan animasi
- **Kasir**: POS interface full-screen dengan cart di kanan
- **Admin Dashboard**: Dashboard dengan chart & statistik
- **Produk**: Grid dengan gambar, harga, stok
- **Laporan**: Chart penjualan + tabel detail

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Template + Vanilla CSS + Vanilla JS
- **Fonts**: Space Grotesk (Google Fonts)
- **Icons**: Emoji-based icons

---

## 📝 Catatan

- Pastikan `storage/` dan `bootstrap/cache/` writeable: `chmod -R 775 storage bootstrap/cache`
- Upload gambar disimpan di `storage/app/public/products/`
- Untuk production: set `APP_DEBUG=false` dan `APP_ENV=production`
