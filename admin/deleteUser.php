<?php
    include_once '../controller/controlUser.php';

    $user_id = (int)$_GET['user_id'];

    if (delete_user($user_id) > 0) {
        $alert = '<script>
                Swal.fire({
                    title: "User Terhapus",
                    text: "User Berhasil di Hapus",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location.href = "manageUser.php";
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
                    window.location.href = "manageUser.php";
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