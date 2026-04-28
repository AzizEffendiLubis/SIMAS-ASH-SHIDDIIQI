# SIMAS – Sistem Informasi Manajemen Aset
### Pondok Pesantren Ash-Shiddiiqi

---

## Persyaratan
- PHP >= 8.1 (dengan ekstensi: pdo, pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath)
- Composer >= 2.0
- MySQL 5.7+ / MariaDB 10.3+
- Node.js >= 16 & NPM (opsional, hanya untuk build assets)

---

## Langkah Instalasi

### 1. Buat project Laravel baru, lalu salin file SIMAS ke dalamnya
```bash
composer create-project laravel/laravel simas-app "^10.0"
cd simas-app
```
Salin seluruh isi folder **simas/** (kecuali `vendor/` dan `node_modules/`) ke dalam folder `simas-app/`.

### 2. (Alternatif) Langsung install dependensi di folder ini
```bash
cd simas
composer install
```

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi database di `.env`
```env
DB_DATABASE=simas
DB_USERNAME=root
DB_PASSWORD=
```
Buat database terlebih dahulu di MySQL:
```sql
CREATE DATABASE simas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Migrasi & seed database
```bash
php artisan migrate --seed
```

### 6. Buat symlink storage (untuk upload foto)
```bash
php artisan storage:link
```

### 7. Jalankan server
```bash
php artisan serve
```
Akses di: **http://localhost:8000**

---

## Akun Default

| Role              | Username       | Password   | Akses                              |
|-------------------|---------------|------------|------------------------------------|
| Super Admin       | `admin`        | `password` | Penuh – semua fitur + manajemen user |
| Kepala Yayasan    | `kepalayayasan`| `password` | Lihat aset, setujui/tolak pengadaan  |
| Admin Unit (SD)   | `adminsd`      | `password` | Kelola aset & pengadaan unit SD      |
| Admin Unit (SMP)  | `adminsmp`     | `password` | Kelola aset & pengadaan unit SMP     |
| Admin Unit (SMA)  | `adminsma`     | `password` | Kelola aset & pengadaan unit SMA     |
| Petugas Perbaikan | `teknisi`      | `password` | Lihat & update status perbaikan      |
| User Biasa        | `guruipa`      | `password` | Lapor kerusakan aset                 |
| User (nonaktif)   | `staff01`      | `password` | Contoh akun belum diaktifkan         |

---

## Hak Akses per Role

| Menu                    | Super Admin | Kepala Yayasan | Admin Unit | Petugas Perbaikan | User Biasa |
|-------------------------|:-----------:|:--------------:|:----------:|:-----------------:|:----------:|
| Dashboard               | ✅           | ✅              | ✅          | ✅                 | ✅          |
| Daftar Aset             | ✅           | ✅ (lihat)      | ✅ (unit)   | ❌                 | ❌          |
| Pengadaan Aset          | ✅           | ❌              | ✅          | ❌                 | ❌          |
| Persetujuan Pengadaan   | ✅           | ✅              | ❌          | ❌                 | ❌          |
| Perbaikan Aset          | ✅           | ✅ (lihat)      | ✅          | ✅ (update)        | ✅ (lapor)  |
| Manajemen Pengguna      | ✅           | ❌              | ❌          | ❌                 | ❌          |

---

## Fitur Utama

- **Multi-role** dengan hak akses berbeda per menu
- **Pendaftaran mandiri** untuk user biasa, dengan aktivasi oleh Super Admin
- **Filter & pencarian** di semua halaman daftar
- **Upload foto** aset dan kerusakan
- **Persetujuan pengadaan** langsung dari Kepala Yayasan
- **Riwayat perbaikan** per aset
- **Responsive** – dapat diakses dari mobile

---

## Struktur Folder Penting

```
app/
├── Http/
│   ├── Controllers/        ← Logic bisnis
│   └── Middleware/         ← Autentikasi & cek akses menu
├── Models/                 ← User, Asset, Repair, Procurement
database/
├── migrations/             ← Skema tabel
└── seeders/                ← Data awal (akun & contoh data)
resources/views/
├── auth/                   ← Login, Register, Lupa Password
├── layouts/app.blade.php   ← Template utama dengan sidebar
├── dashboard/
├── assets/
├── repairs/
├── procurements/
└── users/
routes/web.php              ← Semua routing dengan middleware menu
```

---

## Catatan Pengembangan

- Sistem menggunakan **custom middleware `menu`** untuk mengecek akses per halaman
- Setiap user menyimpan `menu_access` (JSON array) yang bisa dikustomisasi per individu
- Super Admin **selalu** punya akses penuh tanpa perlu cek `menu_access`
- User biasa yang mendaftar otomatis berstatus `nonaktif` – harus diaktifkan Super Admin
