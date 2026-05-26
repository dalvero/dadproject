<?php
session_start(); // Wajib ada di paling atas
require_once 'koneksi/koneksi.php'; // Pastikan koneksi $conn ada
require_once 'koneksi/csrf.php';

// Hanya jalankan jika form di-submit dengan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    verify_csrf_token();
    
    // Validasi input
    if (!isset($_POST['email'], $_POST['password'], $_POST['kelas_id'])) {
        // Set pesan error jika input tidak lengkap
        $_SESSION['flash_message'] = [
            'status' => 'error',
            'message' => 'Input tidak lengkap. Harap coba lagi.'
        ];
        header('Location: index.php');
        exit();
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $kelas_id = (int)$_POST['kelas_id'];

    // 1. Ambil data user dengan AMAN (Prepared Statement)
    $stmt = $conn->prepare("SELECT user_id, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // 2. Verifikasi user dan password
    if ($user && password_verify($password, $user['password'])) {
        
        // 3. Ambil enrollment key dengan AMAN (Prepared Statement)
        $stmt_key = $conn->prepare("SELECT enrollment_key FROM enrollment_key WHERE kelas_id = ?");
        $stmt_key->bind_param("i", $kelas_id);
        $stmt_key->execute();
        $result_key = $stmt_key->get_result();
        $enrollment = $result_key->fetch_assoc();
        $stmt_key->close();

        if ($enrollment) {
            // BERHASIL: Tambahkan pesan tambahan di sini
            $_SESSION['flash_message'] = [
                'status' => 'success',
                'title' => 'Enrollment Berhasil!', // Judul bisa dikustomisasi
                'message' => 'Anda telah berhasil terdaftar di kelas.',
                'details' => 'Enrollment Key Anda: <strong>' . htmlspecialchars($enrollment['enrollment_key']) . '</strong><br><small>Silakan simpan key ini untuk digunakan di dashboard siswa.</small>'
            ];
        } else {
            // GAGAL: Key tidak ditemukan
            $_SESSION['flash_message'] = [
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Enrollment key untuk kelas ini tidak dapat ditemukan.',
                'details' => 'Pastikan Anda memilih kelas yang benar atau hubungi administrator.'
            ];
        }

    } else {
        // GAGAL: Email atau password salah
        $_SESSION['flash_message'] = [
            'status' => 'error',
            'title' => 'Autentikasi Gagal!',
            'message' => 'Email atau kata sandi yang Anda masukkan salah.',
            'details' => 'Periksa kembali kredensial Anda dan coba lagi.'
        ];
    }
}

// Redirect kembali ke halaman utama SETELAH semua logika selesai
header('Location: index.php');
exit();
?>