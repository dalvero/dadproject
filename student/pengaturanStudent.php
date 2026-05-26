<?php
session_start();
require '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';

// Keamanan: Pastikan yang akses adalah siswa yang sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../component/login.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$currentPage = 'pengaturan'; // Definisikan halaman saat ini untuk konsistensi

// Ambil data terbaru dari database untuk ditampilkan
$stmt = $conn->prepare("SELECT u.nama_lengkap, u.email, s.status FROM user u JOIN students s ON u.user_id = s.user_id WHERE u.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$studentData = $result->fetch_assoc();

$nama_lengkap = $studentData['nama_lengkap'] ?? 'Nama Siswa';
$email = $studentData['email'] ?? 'email@siswa.com';
$status = $studentData['status'] ?? 'Status tidak diketahui';

$stmt->close();

// Logika untuk menampilkan notifikasi dari session
$alertMessage = '';
$alertType = '';
if (isset($_SESSION['form_success'])) {
    $alertMessage = $_SESSION['form_success'];
    $alertType = 'success';
    unset($_SESSION['form_success']);
} elseif (isset($_SESSION['form_error'])) {
    $alertMessage = $_SESSION['form_error'];
    $alertType = 'error';
    unset($_SESSION['form_error']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Dashboard Siswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <aside class="sidebar-section">
            <?php include 'components/sidebarStudent.php'; ?>
        </aside>

        <main class="main-content-area">
             <nav class="main-navbar">
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="studentDashboard.php?page=notifikasi">Notifikasi</a></li>
                    <li><a href="studentDashboard.php?page=kelas">Kelas Saya</a></li>
                </ul>
            </nav>
            <div class="settings-container">
                <header class="settings-header">
                    <h1>Pengaturan Akun</h1>
                </header>

                <div class="settings-card">
                    <h2>Informasi Profil</h2>
                    <form action="ControllerStudent.php?action=update_profil" method="POST">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($nama_lengkap); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email); ?>" disabled>
                            <small>Email tidak dapat diubah.</small>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <input type="text" id="status" name="status" value="<?= htmlspecialchars($status); ?>" disabled>
                            <small>Status diatur oleh Admin.</small>
                        </div>
                        <div class="form-group">
                            <label for="password_baru">Password Baru</label>
                            <input type="password" id="password_baru" name="password_baru" placeholder="Kosongkan jika tidak ingin diubah">
                        </div>
                        <div class="form-group">
                            <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                            <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ketik ulang password baru">
                        </div>
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </form>
                </div>

                <div class="settings-card danger-zone">
                    <h2>Zona Berbahaya</h2>
                    <p>Menghapus akun akan menghilangkan semua progres belajar dan data Anda secara permanen.</p>
                    <br/>
                    <button type="button" id="hapusAkunBtn" class="btn-primary btn-danger">Hapus Akun Saya</button>
                </div>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertMessage = '<?= addslashes($alertMessage) ?>';
        const alertType = '<?= $alertType ?>';
        if (alertMessage) {
            Swal.fire({
                icon: alertType,
                title: (alertType === 'success' ? 'Berhasil!' : 'Gagal!'),
                text: alertMessage,
                confirmButtonText: 'OK'
            });
        }

        const hapusAkunBtn = document.getElementById('hapusAkunBtn');
        if (hapusAkunBtn) {
            hapusAkunBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Anda Sangat Yakin?',
                    text: "Semua data dan progres belajar Anda akan hilang selamanya. Tindakan ini tidak dapat dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus Akun Saya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'ControllerStudent.php?action=hapus_akun';
                    }
                });
            });
        }
    });
    </script>
</body>
</html>