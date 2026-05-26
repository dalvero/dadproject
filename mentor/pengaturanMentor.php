<?php
session_start();
include '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';

$mentor_id = $_SESSION['mentor_id']; 

$queryMentor = mysqli_query($conn, "
    SELECT user.nama_lengkap, user.email 
    FROM mentors 
    JOIN user ON mentors.user_id = user.user_id 
    WHERE mentors.mentor_id = '$mentor_id'
");

$dataMentor = mysqli_fetch_assoc($queryMentor);
$nama_mentor = $dataMentor['nama_lengkap'] ?? 'Mentor';
$email = $dataMentor['email'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Dashboard Mentor</title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
</head>
<body>
    <div class="dashboard-container">
        <?php include 'components/sidebar.php'; ?>
        <main class="main-content">
            <header>
                <h1>Pengaturan</h1>
                <hr>
            </header>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <div class="settings-card">
                <h2>Informasi Profil</h2>
                <form action="Controller.php?action=update_profil" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($nama_mentor);?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email);?>" disabled>
                        <small>Email tidak dapat diubah.</small>
                    </div>
                    <div class="form-group">
                        <label for="password_baru">Password Baru</label>
                        <input type="password" id="password_baru" name="password_baru" placeholder="Kosongkan jika tidak ingin diubah">
                    </div>
                    <div class="form-group">
                        <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                        <input type="password" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ketik ulang password baru">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>

            <div class="settings-card danger-zone">
                <h2>Zona Berbahaya</h2>
                <p>Tindakan ini akan Menghapus akun Anda secara permanen dan tidak dapat dikembalikan.</p>
                <br/>
                <button type="button" id="hapusAkunBtn" class="btn btn-danger">Hapus Akun Saya</button>
            </div>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hapusAkunBtn = document.getElementById('hapusAkunBtn');
        
        if (hapusAkunBtn) {
            hapusAkunBtn.addEventListener('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Anda Sangat Yakin?',
                    html: "Tindakan ini akan menghapus akun Anda, semua kelas, dan materi yang telah Anda buat. <br><b>Ini tidak dapat dibatalkan.</b><br><br>Ketik '<b>hapus akun saya</b>' di bawah ini untuk konfirmasi.",
                    icon: 'warning',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus Akun Saya',
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    showLoaderOnConfirm: true,
                    preConfirm: (inputValue) => {
                        if (inputValue !== 'hapus akun saya') {
                            Swal.showValidationMessage('Teks konfirmasi tidak cocok. Silakan ketik "hapus akun saya".');
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'Controller.php?action=hapus_akun';
                    }
                });
            });
        }
    });
    </script>
</body>
</html>