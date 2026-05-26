<?php
session_start();
require '../koneksi/koneksi.php';

$data = query("SELECT kelas.kelas_id, kelas.title_kelas, kelas.foto_kelas, kelas.desk_kelas, mentors.nama_depan, mentors.nama_belakang, 
                kategori_kelas.jenis, enrollment_key.enrollment_key FROM kelas 
                INNER JOIN mentors ON kelas.mentor_id = mentors.mentor_id
                INNER JOIN kategori_kelas ON kelas.kategori_id = kategori_kelas.kategori_kelas_id 
                LEFT JOIN enrollment_key ON kelas.kelas_id = enrollment_key.kelas_id 
                ORDER BY kelas.kelas_id DESC");

// Ambil data admin untuk sapaan di header
$dataAdmin = mysqli_query($conn, 'SELECT * FROM user WHERE role = "admin" LIMIT 1'); // Ambil satu admin saja
$data_admin = mysqli_fetch_assoc($dataAdmin);
$nama_lengkap = $data_admin['nama_lengkap'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../picture/logo.png" type="image/x-icon">
    <title>Kelola Kelas - DadProject</title>
    <link rel="stylesheet" href="../css/admin/adminDashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('components/sidebarAdmin.php'); ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Manajemen Kelas</h1>
                <hr>
                <br>
                <p>Selamat Datang, <?= htmlspecialchars($nama_lengkap); ?></p>
            </header>

            <div class="header-controls">
                <a href="addClasses.php" class="boxbtn">Tambah Kelas</a>
                <input type="text" name="keywordKelas" id="keywordKelas" class="inputSearch" autocomplete="off"
                    placeholder="Cari Kelas..">
            </div>

            <div id="searchResult">
                <table class="custom-table" border="1" cellpadding="10" cellspacing="0">
                    <thead>
                        <tr>
                            <th>NO.</th>
                            <th>Aksi</th>
                            <th>Nama Kelas</th>
                            <th>Foto</th>
                            <th>Deskripsi Kelas</th>
                            <th>Nama Mentor</th>
                            <th>Kategori</th>
                            <th>Enrollment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center;">Belum ada kelas yang ditambahkan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; ?>
                            <?php foreach ($data as $dt): ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td class="actions">
                                        <button class="hapus"><a href="#"
                                                onclick="confirmDelete(<?= $dt['kelas_id'] ?>)">Hapus</a></button>
                                        <button class="edit"><a
                                                href="editKelas.php?id=<?= $dt['kelas_id']; ?>">Edit</a></button>
                                    </td>
                                    <td><?= htmlspecialchars($dt["title_kelas"]) ?></td>
                                    <td><img src="../picture/<?= htmlspecialchars($dt["foto_kelas"]) ?>" alt="Foto Kelas" width="100">
                                    </td>
                                    <td><?= htmlspecialchars($dt["desk_kelas"]) ?></td>
                                    <td><?= htmlspecialchars($dt["nama_depan"] . " " . $dt["nama_belakang"]) ?></td>
                                    <td><?= htmlspecialchars($dt["jenis"]) ?></td>
                                    <td><?= htmlspecialchars($dt['enrollment_key']) ?></td>
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
        const keywordInput = document.getElementById('keywordKelas');
        const searchResultContainer = document.getElementById('searchResult');

        keywordInput.addEventListener('keyup', function () {
            const xhr = new XMLHttpRequest();

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    searchResultContainer.innerHTML = xhr.responseText;
                }
            };

            xhr.open('GET', 'searchKelas.php?keywordKelas=' + keywordInput.value, true);
            xhr.send();
        });

        function confirmDelete(id) {
            Swal.fire({
                title: "Anda Yakin?",
                text: "Data kelas beserta konten di dalamnya akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'deleteKelas.php?kelas_id=' + id;
                }
            });
        }
    </script>
</body>

</html>