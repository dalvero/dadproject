<?php
    include_once '../controller/controlKategoriKelas.php';

    $kategori_kelas_id = (int)$_GET['kategori_kelas_id'];

    if (hapus($kategori_kelas_id) > 0) {
        $alert = '<script>
                Swal.fire({
                    title: "Kategori Kelas Terhapus",
                    text: "Kategori Kelas Berhasil di Hapus",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location.href = "manageCategoryClass.php";
                });
            </script>';
    } else {
        $alert =  '<script>
                Swal.fire({
                    title: "Gagal Mengapus!",
                    text: "Terjadi kesalahan saat menghapus data. Silakan coba lagi nanti.",
                    icon: "error",
                    confirmButtonText: "Coba Lagi"
                }).then(function() {
                    window.location.href = "manageCategoryClass.php";
                });
            </script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?= $alert ?>
</body>
</html>