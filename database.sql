-- DADProject - Database Schema
-- Jalankan query ini setelah membuat database 'dadproject'
-- Pastikan user.sql sudah di-import terlebih dahulu (tabel user)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- Tabel: admin
-- --------------------------------------------------------

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `nama_depan` varchar(50) DEFAULT NULL,
  `nama_belakang` varchar(50) DEFAULT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: mentors
-- --------------------------------------------------------

CREATE TABLE `mentors` (
  `mentor_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nama_depan` varchar(50) DEFAULT NULL,
  `nama_belakang` varchar(50) DEFAULT NULL,
  `expertise` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`mentor_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: students
-- --------------------------------------------------------

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nama_depan` varchar(50) DEFAULT NULL,
  `nama_belakang` varchar(50) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: kategori_kelas
-- --------------------------------------------------------

CREATE TABLE `kategori_kelas` (
  `kategori_kelas_id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kategori_kelas_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: kelas
-- --------------------------------------------------------

CREATE TABLE `kelas` (
  `kelas_id` int(11) NOT NULL AUTO_INCREMENT,
  `title_kelas` varchar(200) NOT NULL,
  `foto_kelas` varchar(255) DEFAULT NULL,
  `desk_kelas` text DEFAULT NULL,
  `mentor_id` int(11) DEFAULT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`kelas_id`),
  FOREIGN KEY (`mentor_id`) REFERENCES `mentors`(`mentor_id`) ON DELETE SET NULL,
  FOREIGN KEY (`kategori_id`) REFERENCES `kategori_kelas`(`kategori_kelas_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: enrollment_key
-- --------------------------------------------------------

CREATE TABLE `enrollment_key` (
  `kelas_id` int(11) NOT NULL,
  `enrollment_key` varchar(50) NOT NULL,
  UNIQUE KEY `enrollment_key` (`enrollment_key`),
  FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`kelas_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: kelas_student
-- --------------------------------------------------------

CREATE TABLE `kelas_student` (
  `kelas_student_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`kelas_student_id`),
  FOREIGN KEY (`student_id`) REFERENCES `students`(`student_id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `kelas`(`kelas_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabel: content
-- --------------------------------------------------------

CREATE TABLE `content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas_id` int(11) NOT NULL,
  `content_title` varchar(200) NOT NULL,
  `content_type` varchar(20) NOT NULL,
  `url_or_file` text DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `content_deskripsi` text DEFAULT NULL,
  `content_body` longtext DEFAULT NULL,
  PRIMARY KEY (`content_id`),
  FOREIGN KEY (`kelas_id`) REFERENCES `kelas`(`kelas_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
