# DADProject - Master Documentation

> **Platform Pembelajaran Pemrograman dengan Mentor Langsung**

| Info         | Detail                                    |
|--------------|-------------------------------------------|
| **Project**  | DADProject                                |
| **Type**     | Learning Management System (LMS)          |
| **Language** | PHP Native (No Framework)                 |
| **Database** | MySQL / MariaDB (`dadproject`)            |
| **Server**   | Laragon (localhost), PHP 8.0.30, MariaDB 10.4.32 |
| **Location** | `c:\laragon\www\dadproject`               |

---

## Daftar Isi

1. [Ringkasan Proyek](#1-ringkasan-proyek)
2. [Struktur Direktori](#2-struktur-direktori)
3. [Database Schema](#3-database-schema)
4. [Sistem Autentikasi](#4-sistem-autentikasi)
5. [Fitur Per Role](#5-fitur-per-role)
6. [Arsitektur & Pola Kode](#6-arsitektur--pola-kode)
7. [Sistem Enrollment](#7-sistem-enrollment)
8. [Halaman Coming Soon / Mockup](#8-halaman-coming-soon--mockup)
9. [Masalah Keamanan](#9-masalah-keamanan-security-notes)
10. [Route / Navigasi](#10-route--navigasi)
11. [Cara Menjalankan Project](#11-cara-menjalankan-project)
12. [Dependencies & Library](#12-dependencies--library)
13. [Catatan Teknis Lainnya](#13-catatan-teknis-lainnya)

---

## 1. Ringkasan Proyek

DADProject adalah platform pembelajaran pemrograman yang menyediakan:

- Materi belajar lengkap (Video & PDF)
- Mentoring langsung dari mentor berpengalaman
- Tugas & Evaluasi
- Akses Gratis
- Sertifikat Kelulusan
- Belajar dari Dasar hingga Mahir

Platform ini mendukung **3 role pengguna**:

| Role      | Deskripsi                                        |
|-----------|--------------------------------------------------|
| **Admin** | Mengelola seluruh sistem (user, kelas, kategori)  |
| **Mentor**| Mengelola materi pembelajaran untuk kelas yang di-assign |
| **Student** | Mengakses materi, mengikuti kelas, dan mengerjakan tugas |

---

## 2. Struktur Direktori

```
dadproject/
├── index.php                    # Halaman utama / landing page
├── konfirmasiEnroll.php         # Handler konfirmasi enrollment dari home
├── logout.php                   # Destroy session dan redirect ke index
├── user.sql                     # Database seed file (tabel user)
├── composer.json                # Dependency: Google API Client
│
├── admin/                       # Panel Admin
│   ├── components/
│   │   └── sidebarAdmin.php     # Komponen sidebar admin
│   ├── adminDashboard.php       # Dashboard admin (statistik)
│   ├── manageUser.php           # CRUD pengguna
│   ├── manageClasses.php        # CRUD kelas
│   ├── manageCategoryClass.php  # CRUD kategori kelas
│   ├── manageContact.php        # Kelola kontak (placeholder)
│   ├── addClasses.php           # Form tambah kelas
│   ├── addKategoriKelas.php     # Form tambah kategori
│   ├── editUser.php             # Form edit pengguna
│   ├── editKelas.php            # Form edit kelas
│   ├── editkategorikelas.php    # Form edit kategori
│   ├── deleteUser.php           # Handler hapus user
│   ├── deleteKelas.php          # Handler hapus kelas
│   ├── deleteKategori.php       # Handler hapus kategori
│   ├── searchUser.php           # AJAX search user
│   ├── searchKelas.php          # AJAX search kelas
│   └── searchKategori.php       # AJAX search kategori
│
├── component/                   # Autentikasi & Komponen Shared
│   ├── login.php                # Form login
│   ├── register.php             # Form registrasi
│   ├── cekAkun.php              # Handler login & registrasi
│   ├── googleAuth.php           # Handler callback Google OAuth
│   ├── callback.php             # Setup Google OAuth Client
│   └── comingSoon.php           # Halaman fitur coming soon
│
├── controller/                  # Business Logic / CRUD Controllers
│   ├── controlUser.php          # Fungsi CRUD user
│   ├── controlKelas.php         # Fungsi CRUD kelas
│   └── controlKategoriKelas.php # Fungsi CRUD kategori kelas
│
├── koneksi/                     # Koneksi DB & Utilities
│   ├── koneksi.php              # Koneksi database + helper query()
│   └── enkripsi.php             # Utility hashing password
│
├── mentor/                      # Panel Mentor
│   ├── components/
│   │   └── sidebar.php          # Komponen sidebar mentor
│   ├── Controller.php           # Controller utama mentor
│   ├── mentorDashboard.php      # Dashboard mentor
│   ├── kelolaMateri.php         # Kelola materi per kelas
│   ├── uploadKonten.php         # Upload konten baru
│   ├── editKonten.php           # Edit konten
│   ├── detailKonten.php         # Detail konten kelas
│   ├── editKelas.php            # Edit detail kelas
│   ├── pengaturanMentor.php     # Pengaturan profil mentor
│   ├── tugasDanKuisMentor.php   # Kelola tugas & kuis (mockup)
│   ├── penilaianMentor.php      # Penilaian siswa (mockup)
│   ├── sertifikatMentor.php     # Sertifikat kelulusan (mockup)
│   ├── css/mentorDashboard.css  # Style mentor
│   └── js/navbar.js             # JS sidebar mentor
│
├── student/                     # Panel Student
│   ├── components/
│   │   └── sidebarStudent.php   # Komponen sidebar student
│   ├── ControllerStudent.php    # Controller student
│   ├── studentDashboard.php     # Dashboard student
│   ├── detailMateri.php         # Viewer materi (video/PDF/text)
│   ├── pengaturanStudent.php    # Pengaturan profil student
│   ├── css/style.css            # Style student dashboard
│   ├── css/detailKelas.css      # Style viewer materi
│   └── js/detailMateri.js       # JS switching materi
│
├── user/                        # Halaman Publik
│   ├── menuKelas.php            # Browse semua kelas
│   └── kontak.php               # Form kontak / feedback
│
├── css/                         # Global Stylesheets
│   ├── index/index.css          # Style halaman utama
│   ├── login/login.css          # Style login
│   ├── regist/regist.css        # Style registrasi
│   ├── admin/adminDashboard.css # Style admin
│   └── comingSoon/comingSoon.css # Style coming soon
│
├── script/                      # Global JavaScript
│   ├── login.js                 # Animasi login (ScrollReveal)
│   └── regist.js                # Animasi registrasi (ScrollReveal)
│
├── picture/                     # Gambar (logo, hero, thumbnail)
├── svg/                         # SVG dekoratif (login.svg, regist.svg)
├── content/                     # Upload materi (MP4, PPTX)
└── vendor/                      # Composer dependencies
```

---

## 3. Database Schema

### 3.1 Tabel `user`

| Kolom           | Tipe           | Keterangan                      |
|-----------------|----------------|----------------------------------|
| `user_id`       | INT (PK, AI)   | ID unik pengguna                |
| `nama_depan`    | VARCHAR        | Nama depan                      |
| `nama_belakang` | VARCHAR        | Nama belakang                   |
| `nama_lengkap`  | VARCHAR        | Nama lengkap (auto-generated)   |
| `username`      | VARCHAR        | Username unik                   |
| `email`         | VARCHAR        | Email unik                      |
| `password`      | VARCHAR        | Password (bcrypt hash)          |
| `role`          | ENUM           | `'admin'`, `'mentor'`, `'student'` |

### 3.2 Tabel `admin`

| Kolom           | Tipe           | Keterangan                      |
|-----------------|----------------|----------------------------------|
| `user_id`       | INT (FK)       | Relasi ke tabel `user`          |
| `nama_depan`    | VARCHAR        | Nama depan admin                |
| `nama_belakang` | VARCHAR        | Nama belakang admin             |

### 3.3 Tabel `mentors`

| Kolom           | Tipe           | Keterangan                      |
|-----------------|----------------|----------------------------------|
| `mentor_id`     | INT (PK, AI)   | ID unik mentor                  |
| `user_id`       | INT (FK)       | Relasi ke tabel `user`          |
| `nama_depan`    | VARCHAR        | Nama depan mentor               |
| `nama_belakang` | VARCHAR        | Nama belakang mentor            |
| `expertise`     | VARCHAR        | Keahlian mentor                 |

### 3.4 Tabel `students`

| Kolom           | Tipe           | Keterangan                      |
|-----------------|----------------|----------------------------------|
| `student_id`    | INT (PK, AI)   | ID unik student                 |
| `user_id`       | INT (FK)       | Relasi ke tabel `user`          |
| `nama_depan`    | VARCHAR        | Nama depan student              |
| `nama_belakang` | VARCHAR        | Nama belakang student           |
| `status`        | VARCHAR        | `'Mahasiswa'` / `'Siswa'`       |

### 3.5 Tabel `kelas`

| Kolom           | Tipe           | Keterangan                      |
|-----------------|----------------|----------------------------------|
| `kelas_id`      | INT (PK, AI)   | ID unik kelas                   |
| `title_kelas`   | VARCHAR        | Judul kelas                     |
| `foto_kelas`    | VARCHAR        | Path foto kelas                 |
| `desk_kelas`    | TEXT           | Deskripsi kelas                 |
| `mentor_id`     | INT (FK)       | Relasi ke tabel `mentors`       |
| `kategori_id`   | INT (FK)       | Relasi ke tabel `kategori_kelas`|

### 3.6 Tabel `kategori_kelas`

| Kolom               | Tipe           | Keterangan                    |
|----------------------|----------------|-------------------------------|
| `kategori_kelas_id`  | INT (PK, AI)   | ID unik kategori              |
| `jenis`              | VARCHAR        | Nama kategori                 |
| `deskripsi`          | TEXT           | Deskripsi kategori            |
| `foto`               | VARCHAR        | Path foto kategori            |

### 3.7 Tabel `enrollment_key`

| Kolom            | Tipe           | Keterangan                      |
|------------------|----------------|----------------------------------|
| `kelas_id`       | INT (FK)       | Relasi ke tabel `kelas`         |
| `enrollment_key` | VARCHAR        | Kunci enrollment unik           |

### 3.8 Tabel `kelas_student`

| Kolom             | Tipe           | Keterangan                      |
|-------------------|----------------|----------------------------------|
| `kelas_student_id`| INT (PK, AI)   | ID unik enrollment              |
| `student_id`      | INT (FK)       | Relasi ke tabel `students`      |
| `course_id`       | INT (FK)       | Relasi ke tabel `kelas`         |

### 3.9 Tabel `content`

| Kolom              | Tipe           | Keterangan                      |
|--------------------|----------------|----------------------------------|
| `content_id`       | INT (PK, AI)   | ID unik konten                  |
| `kelas_id`         | INT (FK)       | Relasi ke tabel `kelas`         |
| `content_title`    | VARCHAR        | Judul konten                    |
| `content_type`     | VARCHAR        | Tipe: `video_url`, `video_file`, `document`, `text` |
| `url_or_file`      | VARCHAR        | URL atau path file              |
| `urutan`           | INT            | Urutan tampil konten            |
| `content_deskripsi`| TEXT           | Deskripsi konten                |
| `content_body`     | LONGTEXT       | Isi konten (untuk tipe text)    |

> **Catatan:** Hanya tabel `user` yang memiliki file SQL (`user.sql`). Tabel lainnya harus dibuat manual di database.

---

## 4. Sistem Autentikasi

### 4.1 Login (Email/Password)

**File:** `component/cekAkun.php`

**Alur:**
1. User mengisi form login (email + password) di `component/login.php`
2. Form POST ke `component/cekAkun.php` dengan `$_POST['masuk']`
3. Query mencari user berdasarkan email di tabel `user`
4. Verifikasi password menggunakan `password_verify()`
5. Set session variables dan redirect ke dashboard sesuai role

### 4.2 Login (Google OAuth 2.0)

**File:** `component/callback.php`, `component/googleAuth.php`

**Alur:**
1. User klik "Login with Google" di halaman login
2. Redirect ke Google OAuth consent screen
3. Google callback ke `component/googleAuth.php`
4. Jika user baru: auto-registrasi sebagai student
5. Jika user sudah ada: langsung login
6. Set session dan redirect ke dashboard

### 4.3 Registrasi

**File:** `component/register.php`, `component/cekAkun.php`

**Alur:**
1. User mengisi form registrasi (nama, email, username, password, status)
2. Form POST ke `component/cekAkun.php` dengan `$_POST['daftar']`
3. Insert ke tabel `user` (role = `'student'`)
4. Insert ke tabel `students`
5. Redirect ke halaman login dengan pesan sukses

### 4.4 Session Variables

Setelah login, session menyimpan:

| Variable                | Keterangan                              |
|-------------------------|------------------------------------------|
| `$_SESSION['loggedin']` | `true`                                   |
| `$_SESSION['user_id']`  | ID user                                  |
| `$_SESSION['role']`     | `'admin'` \| `'mentor'` \| `'student'`  |
| `$_SESSION['nama_lengkap']` | Nama lengkap                        |
| `$_SESSION['email']`    | Email                                    |
| `$_SESSION['username']` | Username                                 |
| `$_SESSION['password']` | Password hash (**ISSUE:** tidak perlu disimpan) |
| `$_SESSION['mentor_id']`| ID mentor (khusus role mentor)           |

### 4.5 Role-Based Redirect Setelah Login

| Role      | Redirect ke                  |
|-----------|------------------------------|
| `admin`   | `admin/adminDashboard.php`   |
| `mentor`  | `mentor/mentorDashboard.php` |
| `student` | `student/studentDashboard.php` |

---

## 5. Fitur Per Role

### 5.1 Admin (`admin/`)

| Fitur                | File                      | Deskripsi                                              |
|----------------------|---------------------------|--------------------------------------------------------|
| Dashboard            | `adminDashboard.php`      | Statistik total pengguna, kelas, kategori, kontak       |
| Kelola Pengguna      | `manageUser.php`          | CRUD pengguna + AJAX live search                       |
| Kelola Kelas         | `manageClasses.php`       | CRUD kelas + AJAX live search                          |
| Kelola Kategori      | `manageCategoryClass.php` | CRUD kategori + AJAX live search                       |
| Kelola Kontak        | `manageContact.php`       | Placeholder / coming soon                               |
| Tambah Kelas         | `addClasses.php`          | Form: judul, foto, deskripsi, enrollment key, mentor, kategori |
| Tambah Kategori      | `addKategoriKelas.php`    | Form: jenis, foto, deskripsi                            |
| Edit User            | `editUser.php`            | Dynamic form berdasarkan role                           |
| Edit Kelas           | `editKelas.php`           | Edit detail kelas                                       |
| Edit Kategori        | `editkategorikelas.php`   | Edit detail kategori                                    |

### 5.2 Mentor (`mentor/`)

| Fitur                  | File                       | Deskripsi                                              |
|------------------------|----------------------------|--------------------------------------------------------|
| Dashboard              | `mentorDashboard.php`      | Kelas yang di-assign, card dengan foto & jumlah konten  |
| Kelola Materi          | `kelolaMateri.php`         | List konten per kelas dengan edit/delete                |
| Upload Konten          | `uploadKonten.php`         | 4 tipe: `video_url`, `video_file`, `document`, `text`  |
| Edit Konten            | `editKonten.php`           | Edit konten yang sudah ada                              |
| Detail Konten          | `detailKonten.php`         | Daftar konten per kelas dengan action icons             |
| Edit Kelas             | `editKelas.php`            | Edit nama, deskripsi, banner kelas                      |
| Pengaturan Profil      | `pengaturanMentor.php`     | Ubah nama, password, hapus akun                         |
| Tugas & Kuis           | `tugasDanKuisMentor.php`   | **Mockup** - belum diimplementasi                       |
| Penilaian              | `penilaianMentor.php`      | **Mockup** - belum diimplementasi                       |
| Sertifikat             | `sertifikatMentor.php`     | **Mockup** - belum diimplementasi                       |

### 5.3 Student (`student/`)

| Fitur                | File                       | Deskripsi                                              |
|----------------------|----------------------------|--------------------------------------------------------|
| Dashboard            | `studentDashboard.php`     | Tab "Kelas Saya" + "Notifikasi", pagination (4/halaman)|
| Viewer Materi        | `detailMateri.php`         | Split layout: viewer (kiri) + playlist (kanan)          |
| Pengaturan Profil    | `pengaturanStudent.php`    | Ubah nama, password, hapus akun                         |

**Tipe konten yang didukung di Viewer Materi:**

| Tipe          | Render                                    |
|---------------|-------------------------------------------|
| `video_url`   | Embed YouTube/Vimeo via iframe            |
| `video_file`  | HTML5 `<video>` player                    |
| `document`    | PDF via iframe; file lain via download    |
| `text`        | Render artikel HTML                       |

### 5.4 Publik (`user/` & `index.php`)

| Fitur                   | File                      | Deskripsi                                    |
|-------------------------|---------------------------|----------------------------------------------|
| Landing Page            | `index.php`               | Hero, slider, learning path, class popup      |
| Browse Kelas            | `user/menuKelas.php`      | Search & filter kategori, card kelas          |
| Kontak                  | `user/kontak.php`         | Form saran/kritik (backend belum ada)         |
| Konfirmasi Enrollment   | `konfirmasiEnroll.php`    | Verifikasi credentials & lookup enrollment key|

---

## 6. Arsitektur & Pola Kode

### 6.1 Pola Arsitektur

- Tidak menggunakan framework (PHP Native)
- Tidak ada routing (`.htaccess` / front controller)
- Setiap halaman diakses langsung via filename
- **Pattern:** File-based routing (1 file = 1 halaman)
- Controller terpisah di folder `controller/` dan per-role

### 6.2 Koneksi Database

**File:** `koneksi/koneksi.php`

- Menggunakan `mysqli_connect`
- Helper function `query()` untuk SELECT (return associative array)
- Koneksi manual di setiap file yang membutuhkan

### 6.3 Controller Pattern

| File                           | Fungsi                                              |
|--------------------------------|------------------------------------------------------|
| `controller/controlUser.php`   | `create_user()`, `update_user()`, `delete_user()`    |
| `controller/controlKelas.php`  | `tambah()`, `edit()`, `hapus()`                      |
| `controller/controlKategoriKelas.php` | `tambah()`, `edit()`, `hapus()`              |
| `mentor/Controller.php`        | Router via `$_REQUEST['action']`                     |
| `student/ControllerStudent.php`| `updateProfilStudent()`, `hapusAkunStudent()`        |

### 6.4 File Upload

| Tipe            | Tujuan                   | Penamaan                        |
|-----------------|--------------------------|---------------------------------|
| Foto kelas      | `picture/`               | Original filename               |
| Materi video    | `content/`               | `konten_[uniqid].mp4`           |
| Materi dokumen  | `content/`               | `konten_[uniqid].[ext]`         |

### 6.5 Dependensi Eksternal

**Composer:**
- `google/apiclient` - Google API Client for OAuth
- `firebase/php-jwt` - JWT library (dependency of google/apiclient)

**CDN (loaded di HTML):**
- SweetAlert2 v11 - Konfirmasi & alert
- Tailwind CSS - Styling form & halaman tertentu
- Remixicon v4.5.0 - Ikon login/register
- Font Awesome v6.5.1 - Ikon dashboard
- ScrollReveal - Animasi login/register
- Google Fonts (Poppins, Nunito) - Typography

---

## 7. Sistem Enrollment

### 7.1 Alur Enrollment dari Home Page

```
User (login) → Klik kelas → Popup email/password
→ Submit ke konfirmasiEnroll.php → Verifikasi credentials
→ Lookup enrollment_key → Tampilkan key via SweetAlert
→ User masukkan key di dashboard student
```

### 7.2 Alur Enrollment dari Dashboard Student

```
Student → studentDashboard.php → Form "Masukkan Enrollment Key"
→ Input key → Validasi di database
→ Jika valid: INSERT ke kelas_student
→ Kelas muncul di tab "Kelas Saya"
```

---

## 8. Halaman Coming Soon / Mockup

Fitur berikut sudah ada UI-nya (HTML statis) tetapi **belum diimplementasikan** backend-nya:

| No | Fitur                    | File                                    | Status                    |
|----|--------------------------|-----------------------------------------|---------------------------|
| 1  | Tugas & Kuis (Mentor)   | `mentor/tugasDanKuisMentor.php`         | Mockup, data hardcoded    |
| 2  | Penilaian (Mentor)       | `mentor/penilaianMentor.php`            | Mockup, data hardcoded    |
| 3  | Sertifikat (Mentor)      | `mentor/sertifikatMentor.php`           | Mockup, data hardcoded    |
| 4  | Kontak (Admin)           | `admin/manageContact.php`               | Placeholder kosong        |
| 5  | Form Kontak Backend      | `user/kontak.php`                       | Form ada, backend tidak   |
| 6  | Notifikasi (Student)     | `student/studentDashboard.php` (tab)    | Placeholder               |

---

## 9. Masalah Keamanan (Security Notes)

### 9.1 SQL Injection Risk - FIXED

> **Status: Sudah diperbaiki** (2026-05-26)

Semua query di seluruh aplikasi sekarang menggunakan **prepared statements**. File yang telah diperbaiki:

- `admin/searchUser.php`
- `admin/searchKelas.php`
- `admin/searchKategori.php`
- `admin/editUser.php`
- `admin/editKelas.php`
- `admin/editkategorikelas.php`
- `controller/controlUser.php`
- `controller/controlKelas.php`
- `controller/controlKategoriKelas.php`
- `mentor/Controller.php`
- `component/cekAkun.php`

File yang sudah aman sebelumnya (tidak perlu perbaikan):
- `student/ControllerStudent.php`
- `student/studentDashboard.php`
- `user/menuKelas.php`
- `konfirmasiEnroll.php`

### 9.2 Password Hash di Session - FIXED

> **Status: Sudah diperbaiki** (2026-05-26)

**File:** `component/cekAkun.php`

Baris `$_SESSION['password'] = $user['password']` sudah dihapus. Password hash tidak lagi disimpan di session.

### 9.3 Hardcoded Credentials

**File:** `component/callback.php`

Google OAuth Client ID dan Secret di-hardcode di source code. Seharusnya menggunakan environment variables (`.env`).

### 9.4 No CSRF Protection - FIXED

> **Status: Sudah diperbaiki** (2026-05-26)

File helper CSRF dibuat di `koneksi/csrf.php` dengan 3 fungsi:
- `generate_csrf_token()` - generate token unik per session
- `csrf_field()` - output hidden input field untuk form
- `verify_csrf_token()` - verifikasi token saat form di-submit

Form yang telah dilindungi:

| Form | File | Processor |
|------|------|-----------|
| Login | `component/login.php` | `component/cekAkun.php` |
| Register | `component/register.php` | `component/cekAkun.php` |
| Tambah User | `admin/manageUser.php` | same page |
| Tambah Kelas | `admin/addClasses.php` | same page |
| Tambah Kategori | `admin/addKategoriKelas.php` | same page |
| Edit User | `admin/editUser.php` | same page |
| Edit Kelas | `admin/editKelas.php` | same page |
| Edit Kategori | `admin/editkategorikelas.php` | same page |
| Upload Konten | `mentor/uploadKonten.php` | `mentor/Controller.php` |
| Edit Konten | `mentor/editKonten.php` | `mentor/Controller.php` |
| Pengaturan Mentor | `mentor/pengaturanMentor.php` | `mentor/Controller.php` |
| Enrollment Student | `student/studentDashboard.php` | same page |
| Pengaturan Student | `student/pengaturanStudent.php` | `student/ControllerStudent.php` |
| Kontak | `user/kontak.php` | (future backend) |
| Enrollment Home | `index.php` | `konfirmasiEnroll.php` |

### 9.5 No Input Validation (Server-Side) - FIXED

> **Status: Sudah diperbaiki** (2026-05-26)

File helper validasi dibuat di `koneksi/validasi.php` dengan fungsi:
- `validate_required()` - cek field wajib
- `validate_email()` - format email
- `validate_min_length()` / `validate_max_length()` - panjang string
- `validate_in()` - nilai harus dalam whitelist
- `validate_integer()` / `validate_positive()` - validasi angka
- `validate_file_upload()` - tipe & ukuran file

File yang telah ditambahkan validasi:

| File | Validasi |
|------|----------|
| `component/cekAkun.php` | Login: email format, required. Register: nama, email, username (3-30 char), password (6+ char), status whitelist |
| `controller/controlUser.php` | create_user & update_user: semua field required, format email, panjang, role whitelist, status/expertise per role |
| `controller/controlKelas.php` | tambah & edit: required fields, mentor/kategori harus integer positif, file upload (jpg/png/gif/webp, max 5MB) |
| `controller/controlKategoriKelas.php` | tambah & edit: required fields, file upload validation |
| `mentor/Controller.php` | tambahKonten & editKonten: required, tipe konten whitelist, urutan positif. updateProfilMentor: nama required, password 6+ char |
| `student/ControllerStudent.php` | updateProfilStudent: nama required, password 6+ char |
| `student/studentDashboard.php` | enrollment key: required, max 50 char |

### 9.6 File Upload Validation - FIXED

> **Status: Sudah diperbaiki** (2026-05-26)

Validasi tipe file ditambahkan menggunakan `validate_file_upload()` dari `koneksi/validasi.php`:

| Upload | File | Tipe Diizinkan | Max Size |
|--------|------|----------------|----------|
| Foto Kelas | `controller/controlKelas.php` | jpg, jpeg, png, gif, webp | 5MB |
| Foto Kategori | `controller/controlKategoriKelas.php` | jpg, jpeg, png, gif, webp | 5MB |
| Video File | `mentor/Controller.php` | mp4, avi, mov, wmv, webm, mkv | 500MB |
| Document | `mentor/Controller.php` | pdf, doc, docx, ppt, pptx, xls, xlsx, txt | 50MB |

---

## 10. Route / Navigasi

### 10.1 Navigasi Publik (navbar)

| Menu       | Link                        |
|------------|-----------------------------|
| Home       | `index.php`                 |
| Kelas      | `user/menuKelas.php`        |
| Kontak     | `user/kontak.php`           |
| Dashboard  | `[sesuai role]` (jika login)|
| Login      | `component/login.php`       |
| Register   | `component/register.php`    |

### 10.2 Navigasi Admin (sidebar)

| Menu            | Link                              |
|-----------------|-----------------------------------|
| Home            | `../index.php`                    |
| Dashboard       | `adminDashboard.php`              |
| Pengguna        | `manageUser.php`                  |
| Kelas           | `manageClasses.php`               |
| Kategori Kelas  | `manageCategoryClass.php`         |
| Kontak          | `../component/comingSoon.php`     |
| Logout          | `../logout.php`                   |

### 10.3 Navigasi Mentor (sidebar)

| Menu            | Link                              |
|-----------------|-----------------------------------|
| Home            | `../index.php`                    |
| Dashboard       | `mentorDashboard.php`             |
| Tugas & Kuis    | `../component/comingSoon.php`     |
| Penilaian       | `../component/comingSoon.php`     |
| Sertifikat      | `../component/comingSoon.php`     |
| Pengaturan      | `pengaturanMentor.php`            |
| Keluar          | `../logout.php`                   |

### 10.4 Navigasi Student (sidebar)

| Menu             | Link                                      |
|------------------|-------------------------------------------|
| Home             | `../index.php`                            |
| Notifikasi       | `studentDashboard.php?page=notifikasi`    |
| Kelas Saya       | `studentDashboard.php?page=kelas`         |
| Pengaturan Akun  | `pengaturanStudent.php`                   |
| Keluar           | `../logout.php`                           |

---

## 11. Cara Menjalankan Project

### Prasyarat

- Laragon (atau XAMPP) dengan PHP 8.0+ dan MariaDB/MySQL
- Composer (untuk install dependency)

### Langkah

1. Clone/copy project ke `c:\laragon\www\dadproject`
2. Jalankan Laragon (Apache + MySQL)
3. Buka phpMyAdmin, buat database `dadproject`
4. Import file `user.sql` untuk membuat tabel `user` + seed data
5. Buat tabel lainnya secara manual (lihat [Section 3: Database Schema](#3-database-schema))
6. Jalankan: `composer install` (di folder project)
7. Buka browser: `http://localhost/dadproject`

### Akun Default (dari `user.sql`)

| Role      | Email                | Password     |
|-----------|----------------------|--------------|
| Admin     | admin1@gmail.com     | password123  |
| Admin     | admin2@gmail.com     | password123  |
| Admin     | admin3@gmail.com     | password123  |
| Mentor    | mentor1@gmail.com    | password123  |
| Student   | student1@gmail.com   | password123  |
| Student   | student2@gmail.com   | password123  |

---

## 12. Dependencies & Library

### 12.1 Composer (`composer.json`)

| Package           | Version | Keterangan                        |
|-------------------|---------|-----------------------------------|
| `google/apiclient`| ^2.15   | Google API Client Library         |
| `firebase/php-jwt`| ^6.0    | JWT (transitive dependency)       |

### 12.2 CDN Libraries

| Library              | URL                                                          |
|----------------------|--------------------------------------------------------------|
| SweetAlert2 v11      | `https://cdn.jsdelivr.net/npm/sweetalert2@11`                |
| Tailwind CSS         | `https://cdn.tailwindcss.com`                                |
| Tailwind Browser     | `https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4`        |
| Remixicon v4.5.0     | `https://cdn.jsdelivr.net/npm/remixicon@4.5.0`               |
| Font Awesome v6.5.1  | `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1`  |
| ScrollReveal         | `https://unpkg.com/scrollreveal`                             |
| Google Fonts Poppins | `https://fonts.googleapis.com/css2?family=Poppins`           |
| Google Fonts Nunito  | `https://fonts.googleapis.com/css2?family=Nunito`            |

---

## 13. Catatan Teknis Lainnya

### 13.1 Mixed Styling

| Pendekatan              | Halaman                                        |
|-------------------------|------------------------------------------------|
| Custom CSS              | Halaman utama, login, register, admin, mentor, student |
| Tailwind CSS (CDN)      | Form tambah/edit admin, `menuKelas`, `kontak`  |

Tidak ada design system yang konsisten.

### 13.2 Tidak Ada `.htaccess` / Front Controller

Setiap halaman diakses langsung. Tidak ada clean URL routing.

### 13.3 Anomali `manageContact.php`

Halaman `admin/manageContact.php` menggunakan hardcoded sidebar alih-alih include `sidebarAdmin.php`, berbeda dari halaman admin lainnya.

### 13.4 Enkripsi Utility

File `koneksi/enkripsi.php` mengkoneksi ke database `test` di port 3307, berbeda dari koneksi utama. Ini adalah utility terpisah untuk batch-hashing password.

### 13.5 Google OAuth

Memerlukan setup Google Cloud Console:
- Client ID dan Secret harus dikonfigurasi di `component/callback.php`
- Redirect URI harus sesuai dengan URL aplikasi

---
