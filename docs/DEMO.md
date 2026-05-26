# DADProject - Demo & Setup Guide

> Step-by-step menjalankan project DADProject di lokal.

---

## Prasyarat

| Software | Versi Minimum | Keterangan |
|----------|---------------|------------|
| Laragon / XAMPP | - | Web server (Apache + MySQL) |
| PHP | 8.0+ | Sudah include di Laragon/XAMPP |
| MariaDB / MySQL | 10.4+ | Sudah include di Laragon/XAMPP |
| Composer | 2.x | Install dependency Google API |
| Browser | Chrome/Firefox/Edge | Untuk mengakses aplikasi |

---

## Langkah 1: Clone / Copy Project

Copy folder `dadproject` ke direktori web server:

- **Laragon:** `C:\laragon\www\dadproject`
- **XAMPP:** `C:\xampp\htdocs\dadproject`

---

## Langkah 2: Jalankan Laragon / XAMPP

1. Buka **Laragon** atau **XAMPP**
2. Start **Apache** dan **MySQL**
3. Pastikan kedua service berjalan (indicator hijau / running)

---

## Langkah 3: Buat Database

1. Buka **phpMyAdmin** di browser: `http://localhost/phpmyadmin`
2. Klik **New** (sidebar kiri) atau tab **Databases**
3. Isi nama database: `dadproject`
4. Collation biarkan default: `utf8mb4_general_ci`
5. Klik **Create**

---

## Langkah 4: Import Database

Masih di phpMyAdmin, pilih database `dadproject`:

### 4.1 Import tabel `user` + data akun

1. Klik tab **Import** (atau menu **Import** di atas)
2. Klik **Choose File** / **Browse**
3. Pilih file: `dadproject/user.sql`
4. Klik **Go** / **Import**
5. Tunggu sampai muncul pesan sukses

### 4.2 Import tabel lainnya

1. Klik tab **Import** lagi
2. Pilih file: `dadproject/database.sql`
3. Klik **Go** / **Import**
4. Tunggu sampai muncul pesan sukses

### 4.3 Verifikasi

Setelah import, sidebar kiri phpMyAdmin harus menampilkan 8 tabel:

```
dadproject
├── admin
├── content
├── enrollment_key
├── kelas
├── kelas_student
├── kategori_kelas
├── mentors
├── students
└── user
```

---

## Langkah 5: Install Composer Dependency

Buka terminal / command prompt, masuk ke folder project:

```bash
cd C:\laragon\www\dadproject
composer install
```

> Folder `vendor/` akan terbuat otomatis berisi Google API Client.

Jika belum punya Composer, download di: https://getcomposer.org/download/

---

## Langkah 6: Konfigurasi Database Connection

Buka file `koneksi/koneksi.php`, pastikan konfigurasi sesuai:

```php
$conn = mysqli_connect("localhost", "root", "", "dadproject");
```

| Parameter | Default Laragon/XAMPP | Keterangan |
|-----------|----------------------|------------|
| Host | `localhost` | - |
| Username | `root` | - |
| Password | `""` (kosong) | Laragon default tidak pakai password |
| Database | `dadproject` | Nama database yang dibuat tadi |

> **Catatan:** Jika MySQL menggunakan port selain default (3306), tambahkan parameter port:
> ```php
> $conn = mysqli_connect("localhost", "root", "", "dadproject", 3306);
> ```

---

## Langkah 7: Buka Aplikasi

Buka browser, akses:

```
http://localhost/dadproject
```

Halaman landing page DADProject akan tampil.

---

## Akun Default

Gunakan akun berikut untuk login:

| Role | Email | Password |
|------|-------|----------|
| Admin | `aditya@gmail.com` | `password123` |
| Admin | `daniel@gmail.com` | `password123` |
| Admin | `david@gmail.com` | `password123` |
| Mentor | `rani@gmail.com` | `password123` |
| Student | `dandan@gmail.com` | `password123` |
| Student | `damad@gmail.com` | `password123` |

---

## Alur Demo

### Sebagai Admin

1. Login dengan akun admin
2. Dashboard akan menampilkan statistik (total pengguna, kelas, kategori)
3. **Kelola Pengguna** → tambah, edit, hapus user
4. **Kelola Kategori** → buat kategori kelas (misal: "Web Development", "Mobile Development")
5. **Kelola Kelas** → buat kelas baru, assign mentor, set enrollment key

### Sebagai Mentor

1. Login dengan akun mentor
2. Dashboard menampilkan kelas yang di-assign
3. Klik kelas → **Kelola Materi**
4. Upload konten (video URL, video file, document PDF, atau text artikel)
5. Atur urutan konten, edit, atau hapus

### Sebagai Student

1. Login dengan akun student
2. Di dashboard, masukkan **Enrollment Key** yang diberikan admin/mentor
3. Kelas akan muncul di tab "Kelas Saya"
4. Klik **Lanjutkan Belajar** → viewer materi (video/PDF/text)
5. Buka **Pengaturan** untuk edit profil atau ganti password

---

## Troubleshooting

### "Koneksi gagal"
- Pastikan MySQL berjalan di Laragon/XAMPP
- Cek konfigurasi di `koneksi/koneksi.php` (host, username, password, database name)
- Pastikan database `dadproject` sudah dibuat dan tabel sudah di-import

### "Table 'dadproject.xxx' doesn't exist"
- Pastikan kedua file SQL (`user.sql` dan `database.sql`) sudah di-import
- Urutan import: `user.sql` dulu, lalu `database.sql`

### Halaman blank / error 500
- Cek PHP version: `php -v` (minimal 8.0)
- Cek error log Laragon/XAMPP
- Pastikan folder `vendor/` ada (jalankan `composer install`)

### Google OAuth tidak berfungsi
- Buka `component/callback.php`
- Isi Client ID dan Client Secret dari Google Cloud Console
- Redirect URI harus sesuai: `http://localhost/dadproject/component/googleAuth.php`

---

## Struktur URL

| Halaman | URL |
|---------|-----|
| Landing Page | `http://localhost/dadproject/` |
| Login | `http://localhost/dadproject/component/login.php` |
| Register | `http://localhost/dadproject/component/register.php` |
| Admin Dashboard | `http://localhost/dadproject/admin/adminDashboard.php` |
| Mentor Dashboard | `http://localhost/dadproject/mentor/mentorDashboard.php` |
| Student Dashboard | `http://localhost/dadproject/student/studentDashboard.php` |
| Browse Kelas | `http://localhost/dadproject/user/menuKelas.php` |
