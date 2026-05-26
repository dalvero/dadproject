<?php
session_start();
require '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';
require_once '../koneksi/validasi.php';

// --- FUNGSI-FUNGSI LOGIKA ---

function updateProfilStudent($data) {
    global $conn;

    // Pastikan user_id ada di session dan valid
    if (!isset($_SESSION['user_id'])) return;
    $user_id = (int)$_SESSION['user_id'];

    // Ambil data dari form dan lakukan sanitasi
    $nama_lengkap = trim($data['nama_lengkap'] ?? '');
    $password_baru = $data['password_baru'] ?? '';
    $konfirmasi_password = $data['konfirmasi_password'] ?? '';

    $error = validate_required($nama_lengkap, 'Nama lengkap') ?: validate_max_length($nama_lengkap, 100, 'Nama lengkap');
    if ($error) {
        $_SESSION['form_error'] = $error;
        return;
    }

    if (!empty($password_baru)) {
        $err = validate_min_length($password_baru, 6, 'Password baru');
        if ($err) {
            $_SESSION['form_error'] = $err;
            return;
        }
    }

    // 1. Update nama lengkap di tabel 'user'
    $stmt_nama = $conn->prepare("UPDATE user SET nama_lengkap = ? WHERE user_id = ?");
    $stmt_nama->bind_param("si", $nama_lengkap, $user_id);
    $stmt_nama->execute();
    $stmt_nama->close();

    // 2. Jika password baru diisi, update password
    if (!empty($password_baru)) {
        if ($password_baru !== $konfirmasi_password) {
            $_SESSION['form_error'] = "Password baru dan konfirmasi tidak cocok.";
            return; // Hentikan fungsi
        }
        
        // Hash password baru sebelum disimpan (SANGAT PENTING)
        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
        
        $stmt_pass = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
        $stmt_pass->bind_param("si", $hashed_password, $user_id);
        $stmt_pass->execute();
        $stmt_pass->close();
    }
    
    // Set pesan sukses dan update nama di session agar langsung berubah
    $_SESSION['form_success'] = "Profil berhasil diperbarui!";
    $_SESSION['nama_lengkap'] = $nama_lengkap;
}

function hapusAkunStudent() {
    global $conn;

    if (!isset($_SESSION['user_id'])) return;
    $user_id = (int)$_SESSION['user_id'];

    // Hapus dari tabel user akan otomatis menghapus dari tabel lain jika ada ON DELETE CASCADE
    // Jika tidak ada, hapus manual dari tabel students dan kelas_student terlebih dahulu.
    // Asumsi kita hapus manual:
    $stmt_del_user = $conn->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt_del_user->bind_param("i", $user_id);
    $stmt_del_user->execute();
    $stmt_del_user->close();

    // Hancurkan semua sesi dan arahkan ke halaman utama
    session_destroy();
    header("Location: ../index.php?pesan=akun_dihapus");
    exit();
}


// --- ROUTER / LOGIKA UTAMA ---

$action = $_GET['action'] ?? null;

// Pastikan hanya siswa yang login yang bisa mengakses controller ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    die("Akses tidak diizinkan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    if ($action === 'update_profil') {
        updateProfilStudent($_POST);
    }
    // Arahkan kembali ke halaman pengaturan setelah proses POST selesai
    header("Location: pengaturanStudent.php");
    exit();

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'hapus_akun') {
        hapusAkunStudent(); // Fungsi ini sudah menangani redirect dan exit
    } else {
        // Jika ada aksi GET lain di masa depan, bisa ditambahkan di sini
        header("Location: studentDashboard.php"); // Arahkan ke dashboard jika aksi tidak dikenal
        exit();
    }
}
?>