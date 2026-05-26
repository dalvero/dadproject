<?php
session_start();
require '../koneksi/koneksi.php';

$data = query('SELECT * FROM `kategori_kelas` ORDER BY kategori_kelas_id DESC');

$dataAdmin = mysqli_query($conn, 'SELECT nama_lengkap FROM user WHERE role = "admin" LIMIT 1');
$data_admin =  mysqli_fetch_assoc($dataAdmin);
$nama_lengkap = $data_admin['nama_lengkap'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../picture/logo.png" type="image/x-icon">
    <title>Kelola Kategori Kelas - DadProject</title>
    <link rel="stylesheet" href="../css/admin/adminDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
       <?php include('components/sidebarAdmin.php');?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Manajemen Kategori Kelas</h1>
                <hr>
                <br>
                <p>Selamat Datang, <?= htmlspecialchars($nama_lengkap);?></p>
            </header>

            <div class="header-controls">
                <a href="addKategoriKelas.php" class="boxbtn">Tambah Kategori</a>
                <input type="text" name="keywordKategori" id="keywordKategori" class="inputSearch" autocomplete="off" placeholder="Cari Kategori..">
            </div>

            <div id="searchResult">
                <table class="custom-table" border="1" cellpadding="10" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Aksi</th>
                            <th>Jenis Kategori</th>
                            <th>Deskripsi</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">Belum ada kategori yang ditambahkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; ?>
                            <?php foreach ($data as $dt): ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td class="actions">
                                         <button class="hapus"><a href="#" onclick="confirmDelete(<?= $dt['kategori_kelas_id'] ?>)">Hapus</a></button>
                                        <button class="edit"><a href="editKategoriKelas.php?id=<?= $dt['kategori_kelas_id']; ?>">Edit</a></button>
                                    </td>
                                    <td><?= htmlspecialchars($dt["jenis"]) ?></td>
                                    <td><?= htmlspecialchars($dt["deskripsi"]) ?></td>
                                    <td><img src="../picture/<?= htmlspecialchars($dt["foto"]) ?>" alt="Foto Kategori" width="100"></td>
                                </tr>
                                <?php $i++ ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        <script>
            const keywordInput = document.getElementById('keywordKategori');
            const searchResultContainer = document.getElementById('searchResult');
            console.log();
            
            keywordInput.addEventListener('keyup', function() {
                const xhr = new XMLHttpRequest();

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        searchResultContainer.innerHTML = xhr.responseText;
                    }
                };

                xhr.open('GET', 'searchKategori.php?keywordKategori=' + keywordInput.value, true);
                xhr.send();
            });

            function confirmDelete(id) {
                Swal.fire({
                    title: "Anda Yakin?",
                    text: "Data kategori akan dihapus permanen!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'deleteKategori.php?kategori_kelas_id=' + id;
                    }
                });
            }
        </script>
</body>
</html>