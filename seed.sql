-- DADProject - Sample Data
-- Jalankan setelah user.sql dan database.sql
-- Semua akun default menggunakan password: password123

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- Password hash untuk "password123"
-- $2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy

-- ============================================================
-- 1. TABEL USER (3 admin, 2 mentor, 4 student)
-- ============================================================

INSERT INTO `user` (`user_id`, `nama_depan`, `nama_belakang`, `nama_lengkap`, `username`, `email`, `password`, `role`) VALUES
-- Admin
(1, 'Aditya', 'Wijayanto', 'Aditya Wijayanto', 'admin1', 'aditya@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'admin'),
(2, 'Daniel', 'Jefry', 'Daniel Jefry', 'admin2', 'daniel@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'admin'),
(3, 'Mohammad', 'David', 'Mohammad David', 'admin3', 'david@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'admin'),
-- Mentor
(4, 'Rani', 'Wijaya', 'Rani Wijaya', 'rani123', 'rani@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'mentor'),
(5, 'Budi', 'Santoso', 'Budi Santoso', 'budi123', 'budi@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'mentor'),
-- Student
(6, 'Daniel', 'Alfero', 'Daniel Alfero', 'alfero123', 'dandan@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'student'),
(7, 'Daffa', 'Ahmad', 'Daffa Ahmad', 'daffa123', 'daffa@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'student'),
(8, 'Siti', 'Nurhaliza', 'Siti Nurhaliza', 'siti123', 'siti@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'student'),
(9, 'Ahmad', 'Fauzi', 'Ahmad Fauzi', 'fauzi123', 'fauzi@gmail.com', '$2y$10$GEv0Umi9HzSw/8D03XlJIu1l0uxO26sbQFvRiPWL0pItq4XBZHgXy', 'student');

-- ============================================================
-- 2. TABEL ADMIN
-- ============================================================

INSERT INTO `admin` (`user_id`, `nama_depan`, `nama_belakang`) VALUES
(1, 'Aditya', 'Wijayanto'),
(2, 'Daniel', 'Jefry'),
(3, 'Mohammad', 'David');

-- ============================================================
-- 3. TABEL MENTORS
-- ============================================================

INSERT INTO `mentors` (`mentor_id`, `user_id`, `nama_depan`, `nama_belakang`, `expertise`) VALUES
(1, 4, 'Rani', 'Wijaya', 'Web Development'),
(2, 5, 'Budi', 'Santoso', 'Mobile Development');

-- ============================================================
-- 4. TABEL STUDENTS
-- ============================================================

INSERT INTO `students` (`student_id`, `user_id`, `nama_depan`, `nama_belakang`, `status`) VALUES
(1, 6, 'Daniel', 'Alfero', 'Mahasiswa'),
(2, 7, 'Daffa', 'Ahmad', 'Siswa'),
(3, 8, 'Siti', 'Nurhaliza', 'Mahasiswa'),
(4, 9, 'Ahmad', 'Fauzi', 'Siswa');

-- ============================================================
-- 5. TABEL KATEGORI KELAS
-- ============================================================

INSERT INTO `kategori_kelas` (`kategori_kelas_id`, `jenis`, `deskripsi`, `foto`) VALUES
(1, 'Web Development', 'Belajar membuat website dari dasar hingga mahir menggunakan HTML, CSS, JavaScript, dan framework modern.', 'javascriptLogo.png'),
(2, 'Mobile Development', 'Kelas pengembangan aplikasi mobile untuk Android dan iOS menggunakan berbagai teknologi.', 'mobiledeveloper.jpg'),
(3, 'Data Science', 'Pelajari analisis data, machine learning, dan artificial intelligence untuk pemecahan masalah nyata.', 'iotengineer.jpg'),
(4, 'UI/UX Design', 'Desain antarmuka pengguna yang menarik dan pengalaman pengguna yang optimal.', 'safar-safarov-koOdUvfGr4c-unsplash.jpg');

-- ============================================================
-- 6. TABEL KELAS
-- ============================================================

INSERT INTO `kelas` (`kelas_id`, `title_kelas`, `foto_kelas`, `desk_kelas`, `mentor_id`, `kategori_id`) VALUES
(1, 'Belajar HTML & CSS untuk Pemula', 'javascriptLogo.png', 'Kelas dasar untuk mempelajari HTML dan CSS dari nol. Cocok untuk pemula yang ingin membuat website pertama.', 1, 1),
(2, 'JavaScript Modern (ES6+)', 'phpLogo1.png', 'Pelajari JavaScript modern mulai dari syntax dasar, DOM manipulation, async/await, hingga framework.', 1, 1),
(3, 'Flutter untuk Pemula', 'mobiledeveloper.jpg', 'Bangun aplikasi mobile cross-platform menggunakan Flutter dan Dart dari dasar.', 2, 2),
(4, 'Python untuk Data Science', 'iotengineer.jpg', 'Kelas lengkap belajar Python untuk analisis data, visualisasi, dan machine learning.', 2, 3);

-- ============================================================
-- 7. TABEL ENROLLMENT KEY
-- ============================================================

INSERT INTO `enrollment_key` (`kelas_id`, `enrollment_key`) VALUES
(1, 'HTML2024'),
(2, 'JS2024'),
(3, 'FLUTTER2024'),
(4, 'PYTHON2024');

-- ============================================================
-- 8. TABEL CONTENT (Materi Kelas)
-- ============================================================

INSERT INTO `content` (`content_id`, `kelas_id`, `content_title`, `content_type`, `url_or_file`, `urutan`, `content_deskripsi`, `content_body`) VALUES
-- Kelas 1: HTML & CSS
(1, 1, 'Pengenalan HTML', 'text', NULL, 1, 'Apa itu HTML dan bagaimana cara kerjanya', '<h2>Apa itu HTML?</h2><p>HTML (HyperText Markup Language) adalah bahasa markup standar untuk membuat halaman web. HTML mendeskripsikan struktur halaman web menggunakan elemen-elemen yang direpresentasikan oleh tag.</p><h3>Tag Dasar HTML</h3><ul><li><code>&lt;html&gt;</code> - Root element</li><li><code>&lt;head&gt;</code> - Informasi metadata</li><li><code>&lt;body&gt;</code> - Konten halaman</li></ul>'),
(2, 1, 'Struktur Dasar HTML', 'video_url', 'https://www.youtube.com/embed/UB1O30fR-EE', 2, 'Video tutorial struktur dasar HTML', NULL),
(3, 1, 'CSS Fundamentals', 'text', NULL, 3, 'Dasar-dasar CSS untuk styling halaman web', '<h2>CSS Fundamentals</h2><p>CSS (Cascading Style Sheets) digunakan untuk mengatur tampilan dan layout halaman web.</p><h3>Cara Menggunakan CSS</h3><ol><li>Inline CSS - langsung di elemen</li><li>Internal CSS - di dalam tag style</li><li>External CSS - file .css terpisah</li></ol>'),
(4, 1, 'Materi Lengkap HTML CSS', 'document', 'html-css-guide.pdf', 4, 'PDF panduan lengkap HTML & CSS', NULL),

-- Kelas 2: JavaScript
(5, 2, 'Pengenalan JavaScript', 'text', NULL, 1, 'Apa itu JavaScript dan mengapa penting dipelajari', '<h2>Apa itu JavaScript?</h2><p>JavaScript adalah bahasa pemrograman yang berjalan di browser. JavaScript memungkinkan halaman web menjadi interaktif.</p><h3>Contoh Sederhana</h3><pre><code>console.log("Hello, World!");</code></pre>'),
(6, 2, 'Variabel dan Tipe Data', 'video_url', 'https://www.youtube.com/embed/9emXNzqCKyg', 2, 'Video pembahasan variabel dan tipe data di JavaScript', NULL),
(7, 2, 'DOM Manipulation', 'text', NULL, 3, 'Cara memanipulasi elemen HTML menggunakan JavaScript', '<h2>DOM Manipulation</h2><p>DOM (Document Object Model) adalah representasi struktur HTML yang bisa diakses dan dimanipulasi menggunakan JavaScript.</p><h3>Metode Umum</h3><ul><li>document.getElementById()</li><li>document.querySelector()</li><li>element.innerHTML</li><li>element.style</li></ul>'),

-- Kelas 3: Flutter
(8, 3, 'Installasi Flutter & Dart', 'text', NULL, 1, 'Langkah-langkah menginstall Flutter di komputer', '<h2>Installasi Flutter</h2><p>Flutter adalah framework UI dari Google untuk membangun aplikasi mobile cross-platform.</p><h3>Langkah Install</h3><ol><li>Download Flutter SDK dari flutter.dev</li><li>Extract ke folder yang diinginkan</li><li>Tambahkan ke PATH environment variable</li><li>Jalankan <code>flutter doctor</code> untuk verifikasi</li></ol>'),
(9, 3, 'Widget Dasar Flutter', 'video_url', 'https://www.youtube.com/embed/x0uinJvhNxI', 2, 'Video tutorial widget-widget dasar di Flutter', NULL),
(10, 3, 'StatefulWidget vs StatelessWidget', 'text', NULL, 3, 'Perbedaan dan kapan menggunakan masing-masing widget', '<h2>StatefulWidget vs StatelessWidget</h2><h3>StatelessWidget</h3><p>Widget yang tidak memiliki state. Sekali dibuat, tampilannya tidak berubah.</p><h3>StatefulWidget</h3><p>Widget yang memiliki state dan bisa berubah selama lifecycle-nya.</p>'),

-- Kelas 4: Python Data Science
(11, 4, 'Install Python & Jupyter', 'text', NULL, 1, 'Setup environment Python dan Jupyter Notebook', '<h2>Setup Python untuk Data Science</h2><h3>Langkah Install</h3><ol><li>Install Python 3.x dari python.org</li><li>Install pip (biasanya sudah include)</li><li>Jalankan: <code>pip install jupyter notebook</code></li><li>Buka dengan: <code>jupyter notebook</code></li></ol>'),
(12, 4, 'NumPy & Pandas Dasar', 'video_url', 'https://www.youtube.com/embed/vmEHCJofslg', 2, 'Video tutorial library NumPy dan Pandas', NULL),
(13, 4, 'Data Visualization dengan Matplotlib', 'document', 'matplotlib-guide.pdf', 3, 'Panduan visualisasi data menggunakan Matplotlib', NULL);

-- ============================================================
-- 9. TABEL KELAS_STUDENT (Enrollment)
-- ============================================================

INSERT INTO `kelas_student` (`kelas_student_id`, `student_id`, `course_id`) VALUES
-- Daniel Alfero mengikuti 2 kelas
(1, 1, 1),
(2, 1, 2),
-- Daffa Ahmad mengikuti 1 kelas
(3, 2, 1),
-- Siti Nurhaliza mengikuti 2 kelas
(4, 3, 3),
(5, 3, 4),
-- Ahmad Fauzi mengikuti 1 kelas
(6, 4, 3);

COMMIT;
