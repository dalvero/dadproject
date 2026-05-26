<?php
session_start();
include '../koneksi/koneksi.php';

$kelas_id = (int) $_GET['kelas_id'];

$sql_kelas = "SELECT * FROM kelas WHERE kelas_id = $kelas_id";
$result_kelas = mysqli_query($conn, $sql_kelas);
$kelas = mysqli_fetch_assoc($result_kelas);


$mentor_id = $kelas['mentor_id'];

$sql_mentor = "SELECT user.nama_lengkap 
               FROM mentors 
               JOIN user ON mentors.user_id = user.user_id 
               WHERE mentors.mentor_id = '$mentor_id'";
$result_mentor = mysqli_query($conn, $sql_mentor);
$dataMentor = mysqli_fetch_assoc($result_mentor);

$nama_mentor = $dataMentor ? $dataMentor['nama_lengkap'] : 'Mentor Tidak Ditemukan';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas - Mentor Dashboard</title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'components/sidebar.php'; ?>

        <div class="main-content">
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']);?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']);?>
            <?php endif; ?>

            <header>
                <h1>Edit Kelas: <?= htmlspecialchars($kelas['title_kelas']) ?></h1>
            </header>
            <div class="form-container">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="kelas_id" value="<?= $kelas['kelas_id'] ?>">
                    <div class="form-group">
                        <label for="nama_kelas">Nama Kelas</label>
                        <input type="text" id="title_kelas" name="title_kelas"
                            value="<?= htmlspecialchars($kelas['title_kelas']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Singkat</label>
                        <textarea id="desk_kelas" name="desk_kelas" rows="5"
                            required><?= htmlspecialchars($kelas['desk_kelas']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="banner_image">Banner Kelas</label>
                        <?php if (!empty($kelas['banner_image'])): ?>
                            <div style="margin-bottom: 10px;">
                                <p style="color: #6c757d; font-size: 0.9em;">Banner saat ini:</p>
                                <img src="path/to/your/images/<?= htmlspecialchars($kelas['banner_image']) ?>"
                                    alt="Banner saat ini" style="max-width: 200px; border-radius: 8px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="banner_image" name="banner_image" accept="image/*">
                        <small>Kosongkan jika tidak ingin mengubah banner.</small>
                    </div>

                    <button type="submit" class="btn-submit">Simpan Perubahan</button>

                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="mentorDashboard.php" style="color: #6c757d; text-decoration: none;">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>