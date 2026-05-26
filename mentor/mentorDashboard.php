<?php
session_start();
require '../koneksi/koneksi.php'; 

$mentor_id = $_SESSION['mentor_id'];

$query_kelas_dan_konten = "
    SELECT k.*, COUNT(c.content_id) AS jumlah_konten FROM kelas k LEFT JOIN content c ON k.kelas_id = c.kelas_id 
    WHERE k.mentor_id = '$mentor_id' GROUP BY  k.kelas_id ORDER BY k.kelas_id DESC ";
$result = query($query_kelas_dan_konten);

$nama_lengkap = $_SESSION['nama_lengkap'] ?? 'Mentor';

$alertMessage = '';
$alertType = '';
if (isset($_SESSION['success_message'])) {
    $alertMessage = $_SESSION['success_message'];
    $alertType = 'success';
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $alertMessage = $_SESSION['error_message'];
    $alertType = 'error';
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mentor</title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Dashboard</h1>
                <hr>
                <p>Selamat Datang, <?= htmlspecialchars($nama_lengkap) ?></p>
            </header>
            <div class="card-grid">
                <?php if (empty($result)): ?>
                    <div class="empty-state-container">
                        <h2>Anda Belum Punya Kelas</h2>
                        <p>Silakan hubungi admin untuk dibuatkan kelas baru.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($result as $row): ?>
                        <div class="card">
                            <div class="class-card-banner"
                                style="background-image: url('../picture/<?= htmlspecialchars($row['foto_kelas']) ?>'); background-size: cover; background-position: center;">
                            </div>
                            <div class="class-card-content">
                                <h3><?= htmlspecialchars($row['title_kelas']) ?></h3>
                                <div class="class-card-stats"><?= htmlspecialchars(substr($row['desk_kelas'], 0, 100)) . '...' ?></div>
                                <div class="class-card-stats"><span>80 Students</span> • <span><?= $row['jumlah_konten']; ?> Modul</span></div>
                            </div>
                            <div class="class-card-footer">
                                <div class="class-actions">
                                    <a href="editKelas.php?kelas_id=<?= $row['kelas_id'] ?>" class="btn-action btn-edit" title="Edit Kelas">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="controlKelas.php?action=delete&kelas_id=<?= $row['kelas_id'] ?>" class="btn-action btn-delete" title="Hapus Kelas" onclick="return confirm('Anda yakin ingin menghapus Kelas ini?');">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                                <div>
                                    <a class="btn btn-secondary" href="kelolaMateri.php?kelas_id=<?= $row['kelas_id'] ?>">
                                        Kelola Materi
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertMessage = '<?= addslashes($alertMessage) ?>';
            const alertType = '<?= $alertType ?>';
            if (alertMessage) {
                Swal.fire({
                    icon: alertType,
                    title: (alertType === 'success' ? 'Berhasil!' : 'Oops...'),
                    text: alertMessage,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        });
    </script>
</body>
</html>