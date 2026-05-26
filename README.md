# DADProject

Platform Pembelajaran Pemrograman dengan Mentor Langsung

## Fitur

- **3 Role:** Admin, Mentor, Student
- **Materi Interaktif:** Video, PDF, dan Artikel
- **Sistem Enrollment:** Daftar kelas dengan enrollment key
- **Kelola Kelas:** CRUD kelas, kategori, dan konten
- **Google OAuth:** Login dengan akun Google
- **Responsive:** Tampilan responsif untuk desktop dan mobile

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.0+ (Native) |
| Database | MySQL / MariaDB |
| Frontend | HTML, CSS, JavaScript |
| Library | SweetAlert2, Tailwind CSS, Font Awesome |
| Auth | Google OAuth 2.0 |

## Instalasi

### Prasyarat

- Laragon / XAMPP (PHP 8.0+, MySQL/MariaDB)
- Composer

### Langkah

```bash
# 1. Clone repository
git clone https://github.com/dalvero/dadproject.git
cd dadproject

# 2. Install dependency
composer install

# 3. Buat database 'dadproject' di phpMyAdmin

# 4. Import database (urutkan)
#    - user.sql (struktur tabel user)
#    - database.sql (struktur tabel lainnya)
#    - seed.sql (data sample, opsional)

# 5. Konfigurasi koneksi di koneksi/koneksi.php
#    sesuaikan host, username, password, database name

# 6. Buka di browser
#    http://localhost/dadproject
```

## Akun Default

Setelah import `seed.sql`:

| Role | Email | Password |
|------|-------|----------|
| Admin | aditya@gmail.com | password123 |
| Mentor | rani@gmail.com | password123 |
| Student | dandan@gmail.com | password123 |

## Struktur Project

```
dadproject/
├── admin/              # Panel Admin
├── component/          # Autentikasi & Komponen Shared
├── controller/         # Business Logic (CRUD)
├── koneksi/            # Koneksi DB & Utilities
├── mentor/             # Panel Mentor
├── student/            # Panel Student
├── user/               # Halaman Publik
├── css/                # Global Stylesheets
├── script/             # Global JavaScript
├── picture/            # Gambar & Asset
├── content/            # Upload Materi
├── vendor/             # Composer Dependencies
├── docs/               # Dokumentasi
├── database.sql        # Struktur Database
├── seed.sql            # Data Sample
└── user.sql            # Struktur Tabel User
```

## Dokumentasi

- [MASTER_DOCS.md](docs/MASTER_DOCS.md) - Dokumentasi lengkap project
- [DEMO.md](DEMO.md) - Panduan demo & setup detail

## License

MIT
