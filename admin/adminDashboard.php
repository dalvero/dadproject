<?php
include '../koneksi/koneksi.php';

$dataAdmin = mysqli_query($conn, 'SELECT * FROM user');
$countUser = mysqli_query($conn, 'SELECT COUNT(*) AS jumlah_user FROM user');
$countKelas = mysqli_query($conn, 'SELECT COUNT(*) AS jumlah_kelas FROM kelas');
$countKategori = mysqli_query($conn, 'SELECT COUNT(*) AS jumlah_kategori FROM kategori_kelas');

$data_admin =  mysqli_fetch_assoc($dataAdmin);
$user =  mysqli_fetch_assoc($countUser);
$kelas = mysqli_fetch_assoc($countKelas);
$kategoriKelas = mysqli_fetch_assoc($countKategori);

$jumlahUser = $user['jumlah_user'];
$jumlahKelas = $kelas['jumlah_kelas'];
$jumlahKategori = $kategoriKelas['jumlah_kategori'];
$nama_lengkap = $data_admin['nama_lengkap'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="picture/logo (1).png" type="image/x-icon">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/admin/adminDashboard.css">
</head>

<body>
    <div class="wrapper">
        <?php include('components/sidebarAdmin.php');?>
        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Dashboard</h1>
                <hr>
                <br>
                <p>Selamat Datang, <?= $nama_lengkap;?></p>
            </header>
            <div class="stats-container">
                <!-- Manajemen Pengguna -->
                <div class="stat-card">

                    <div class="stat-head">
                        <i class="fa-solid fa-user"></i>
                        <div class="side">
                            <h3><?= $jumlahUser; ?><p>Pengguna</p>
                            </h3>
                        </div>
                    </div>

                    <a href="manageUser.php">
                        <div class="stat-foot">
                            <p>Lihat Detail</p>
                            <i class="fa-solid fa-circle-right"></i>
                        </div>
                    </a>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <i class="fa-solid fa-landmark"></i>
                        <h3><?= $jumlahKelas ?><p>Kelas</p>
                        </h3>
                    </div>
                    <a href="manageClasses.php">
                        <div class="stat-foot">
                            <p>Lihat Detail</p>
                            <i class="fa-solid fa-circle-right"></i>
                        </div>
                    </a>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <i class="fa-solid fa-folder-tree"></i>
                        <h3><?= $jumlahKategori; ?><p>Kategori Kelas</p>
                        </h3>
                    </div>
                    <a href="manageCategoryClass.php">
                        <div class="stat-foot">
                            <p>Lihat Detail</p>
                            <i class="fa-solid fa-circle-right"></i>
                        </div>
                    </a>
                </div>

                <div class="stat-card">
                    <div class="stat-head">
                        <i class="fa-solid fa-message"></i>
                        <h3>
                            <p>Kontak</p>
                        </h3>
                    </div>
                    <a href="../component/comingSoon.php">
                        <div class="stat-foot">
                            <p>Lihat Detail</p>
                            <i class="fa-solid fa-circle-right"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>