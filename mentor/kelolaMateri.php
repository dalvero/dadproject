<?php
session_start();
include '../koneksi/koneksi.php';

if (!isset($_SESSION['mentor_id'])) {
    die("Akses ditolak. Silakan login sebagai mentor.");
}
$mentor_id = $_SESSION['mentor_id'];

if (!isset($_GET['kelas_id']) || !is_numeric($_GET['kelas_id'])) {
    die("Parameter kelas tidak valid.");
}
$kelas_id = (int) $_GET['kelas_id'];

$queryKelas = mysqli_query($conn, "SELECT * FROM kelas WHERE kelas_id = '$kelas_id'");
$data_kelas = mysqli_fetch_assoc($queryKelas);

if (!$data_kelas) {
    die("Kelas dengan ID tersebut tidak ditemukan.");
}

$queryContent = mysqli_query($conn, "SELECT * FROM content WHERE kelas_id = '$kelas_id' ORDER BY urutan ASC");

$queryMentor = mysqli_query($conn, "
    SELECT user.nama_lengkap 
    FROM mentors 
    JOIN user ON mentors.user_id = user.user_id 
    WHERE mentors.mentor_id = '$mentor_id'
");
$dataMentor = mysqli_fetch_assoc($queryMentor);
$nama_mentor = $dataMentor['nama_lengkap'] ?? 'Mentor';

function getIconForContentType($type)
{
    switch ($type) {
        case 'video_url':
        case 'video_file':
            return 'fa-solid fa-film';
        case 'document':
            return 'fa-solid fa-file-pdf';
        case 'text':
            return 'fa-solid fa-file-alt';
        default:
            return 'fa-solid fa-play';
    }
}

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
    <title>Kelola Konten - <?= htmlspecialchars($data_kelas['title_kelas']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="css/mentorDashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'components/sidebar.php'; ?>
        <div class="content-area">
            <header class="header-kelas">
                <h1 class="page-title">Detail Kelas: <?= htmlspecialchars($data_kelas['title_kelas']) ?></h1>
                <div class="tabs">
                    <a href="#" class="tab-item active">Konten</a>
                    <a class="tab-item" href="uploadKonten.php?kelas_id=<?= $kelas_id ?>">Upload Konten</a>
                </div>
            </header>
            <main>

                <?php
                if (mysqli_num_rows($queryContent) > 0) {
                    while ($content = mysqli_fetch_assoc($queryContent)) { ?>
                        <div class="class-list-item">
                            <div class="video-placeholder">
                                <i class="<?= getIconForContentType($content['content_type']); ?>"></i>
                            </div>
                            <div class="class-info">
                                <h3><?=htmlspecialchars($content['urutan'])?>. <?= htmlspecialchars($content['content_title']); ?></h3>
                                <p><?= htmlspecialchars($content['content_deskripsi']); ?></p>
                            </div>
                            <div class="class-actions">
                                   <a href="editKonten.php?action=edit&content_id=<?= $content['content_id']; ?>&kelas_id=<?= $kelas_id; ?>"
                                    class="btn-action btn-edit" title="Edit Konten">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="Controller.php?action=hapus&content_id=<?= $content['content_id']; ?>&kelas_id=<?= $kelas_id; ?>"
                                    class="btn-action btn-delete" title="Hapus Konten">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="empty-state-container">
                        <div class="empty-state-icon">
                            <i class="fa-solid fa-folder-plus"></i>
                        </div>
                        <div class="empty-state-text">
                            <h2>Anda Belum Memiliki Konten</h2>
                            <p>Silakan upload kontens baru untuk menampilkan daftar konten dari kelas anda.</p>
                        </div>
                    <?php } ?>
            </main>
        </div>
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

            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();

                    const deleteUrl = this.href;

                    Swal.fire({
                        title: 'Anda yakin?',
                        text: "Konten yang sudah dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = deleteUrl;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>