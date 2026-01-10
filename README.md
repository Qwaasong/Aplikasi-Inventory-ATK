<img src="public\assets\images\Group 4.png" alt="Laravel">
<div align="center">

[![Laravel](https://img.shields.io/badge/Laravel-1.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.0.0-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)

</div>

## 📋 Deskripsi Aplikasi

**KopsisApp** adalah sistem informasi koperasi sekolah yang dirancang khusus untuk memudahkan pengelolaan transaksi dan aktivitas koperasi di lingkungan sekolah, khususnya SMKN 9 Malang. Aplikasi ini menyediakan solusi komprehensif untuk manajemen stok barang, pencatatan transaksi, laporan keuangan, dan manajemen pengguna dengan antarmuka yang intuitif dan mudah digunakan.

Aplikasi ini dibangun dengan teknologi modern dan dirancang untuk memberikan efisiensi dalam pengelolaan koperasi sekolah, memungkinkan staf koperasi untuk fokus pada pelayanan kepada siswa dan guru.

## ✨ Fitur Utama

### 📦 Manajemen Barang
- **Manajemen Produk**: Tambah, edit, dan hapus produk dengan kategori dan satuan yang beragam
- **Manajemen Vendor**: Pengelolaan data pemasok barang koperasi
- **Barang Masuk**: Pencatatan pembelian barang dari vendor ke dalam stok
- **Barang Keluar**: Pencatatan penjualan barang dari stok koperasi
- **Stok Terkini**: Monitoring stok barang secara real-time

### 💰 Manajemen Keuangan
- **Transaksi Keuangan**: Pencatatan pemasukan dan pengeluaran
- **Dashboard Keuangan**: Visualisasi grafik pendapatan dan pengeluaran
- **Laporan Keuangan**: Ringkasan keuangan mingguan, bulanan, dan tahunan
- **Riwayat Transaksi**: Pencatatan lengkap semua transaksi yang terjadi

### 👥 Manajemen Pengguna
- **Sistem Otentikasi**: Login, registrasi, dan manajemen profil pengguna
- **Manajemen Pengguna**: Tambah, edit, dan hapus akun pengguna
- **Role-based Access**: Akses berdasarkan peran (admin, kasir, anggota)

### 📊 Dashboard & Laporan
- **Dashboard Interaktif**: Ringkasan data penting dalam satu tampilan
- **Grafik Keuangan**: Visualisasi data keuangan dalam bentuk grafik
- **Filter Data**: Pencarian dan filter data untuk semua modul
- **Responsive Design**: Tampilan yang optimal di berbagai perangkat

## 🛠️ Teknologi yang Digunakan

### Backend
- **Framework**: [Laravel 11.x](https://laravel.com) - Web application framework
- **Bahasa Pemrograman**: PHP 8.2
- **Database**: MySQL
- **ORM**: Eloquent ORM

### Frontend
- **CSS Framework**: [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- **JavaScript**: Alpine.js, Chart.js, Tom Select
- **Build Tool**: Vite
- **HTTP Client**: Axios

### API & Tools
- **API Development**: Laravel API Resources
- **Development Environment**: Laravel Sail (opsional)
- **Code Quality**: Laravel Pint, PHP CS Fixer
- **Testing**: PestPHP, PHPUnit

## 🚀 Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Node.js & NPM
- Web server (Apache/Nginx)

### Langkah-langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/username/koperasi-siswa.git
   cd koperasi-siswa
   ```

2. **Install dependensi PHP**
   ```bash
   composer install
   ```

3. **Install dependensi JavaScript**
   ```bash
   npm install
   ```

4. **Buat file environment**
   ```bash
   cp .env.example .env
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi database**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=306
   DB_DATABASE=nama_database
   DB_USERNAME=nama_pengguna
   DB_PASSWORD=kata_sandi
   ```

7. **Migrasi database**
   ```bash
   php artisan migrate --seed
   ```

8. **Compile assets**
   ```bash
   npm run build
   # atau untuk mode development
   npm run dev
   ```

9. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

Aplikasi akan berjalan di `http://localhost:8000`

## 📖 Cara Penggunaan

### Admin Panel
1. Buka aplikasi di browser
2. Login dengan akun admin
3. Gunakan menu navigasi untuk mengakses berbagai modul

### Manajemen Produk
1. Masuk ke menu "Produk"
2. Tambahkan produk baru atau edit produk yang sudah ada
3. Pilih kategori dan satuan yang sesuai

### Manajemen Stok
1. Gunakan menu "Barang Masuk" untuk mencatat pembelian
2. Gunakan menu "Barang Keluar" untuk mencatat penjualan
3. Monitor stok terkini di menu "Stok Terkini"

### Laporan Keuangan
1. Akses dashboard untuk melihat ringkasan keuangan
2. Gunakan filter waktu untuk melihat data sesuai periode
3. Lihat grafik keuangan untuk analisis visual

## 🤝 Kontribusi

Kami menyambut baik kontribusi dari komunitas untuk meningkatkan kualitas aplikasi ini. Berikut adalah langkah-langkah untuk berkontribusi:

1. **Fork repository** ini
2. **Buat branch** baru (`git checkout -b feature/NamaFitur`)
3. **Commit perubahan** (`git commit -m 'Add some NamaFitur'`)
4. **Push ke branch** (`git push origin feature/NamaFitur`)
5. **Buka Pull Request**

### Panduan Kontribusi
- Pastikan kode Anda mengikuti standar Laravel dan PSR-12
- Tambahkan dokumentasi yang diperlukan
- Sertakan test jika memungkinkan
- Jelaskan perubahan yang Anda buat secara rinci

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT). Anda bebas menggunakan, memodifikasi, dan mendistribusikan kode ini dengan syarat menyertakan informasi lisensi dan hak cipta.

## 👨‍💻 Pengembang

Aplikasi ini dikembangkan sebagai bagian dari program PKL (Praktik Kerja Lapangan) di SMKN 9 Malang.

## 📞 Dukungan

Jika Anda mengalami masalah atau memiliki pertanyaan tentang aplikasi ini, silakan:

- Membuka issue di repository GitHub
- Menghubungi tim pengembang langsung
- Membaca dokumentasi lengkap di Wiki

---

<div align="center">

**KopsisApp** - Solusi Modern untuk Koperasi Sekolah

© 2024 KopsisApp SMKN 9 Malang. All rights reserved.

</div>
