<?php
require '../koneksi/koneksi.php';
require_once '../koneksi/validasi.php';

function tambah($data){
    global $conn;

    $jenis = trim($data['jenis'] ?? '');
    $desc = trim($data['desc'] ?? '');

    $error = validate_required($jenis, 'Jenis kategori') ?: validate_max_length($jenis, 100, 'Jenis kategori')
           ?: validate_required($desc, 'Deskripsi');
    if ($error) {
        $_SESSION['validation_error'] = $error;
        return 0;
    }

    $lokasi_file = $_FILES['foto']['tmp_name'];
    $nama_file = $_FILES['foto']['name'];
    $size_file = $_FILES['foto']['size'];
    $direktori = '../picture/' . $nama_file;

    $fileError = validate_file_upload($_FILES['foto'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, 'Foto kategori');
    if ($fileError) {
        $_SESSION['validation_error'] = $fileError;
        return 0;
    }

    if(move_uploaded_file($lokasi_file, $direktori)){
        $stmt = $conn->prepare("INSERT INTO `kategori_kelas` (`jenis`, `deskripsi`, `foto`) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $jenis, $desc, $nama_file);
        $stmt->execute();
        $stmt->close();
    }

    return mysqli_affected_rows($conn);
}

function edit($edit, $id){
    global $conn;

    $id = (int)$id;
    $jenis = trim($edit['jenis'] ?? '');
    $desc = trim($edit['desc'] ?? '');

    $error = validate_positive($id, 'Kategori ID')
           ?: validate_required($jenis, 'Jenis kategori') ?: validate_max_length($jenis, 100, 'Jenis kategori')
           ?: validate_required($desc, 'Deskripsi');
    if ($error) {
        $_SESSION['validation_error'] = $error;
        return 0;
    }

    $lokasi_file = $_FILES['foto']['tmp_name'];
    $nama_file = $_FILES['foto']['name'];
    $size_file = $_FILES['foto']['size'];
    $direktori = '../picture/' . $nama_file;

    $fileError = validate_file_upload($_FILES['foto'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, 'Foto kategori');
    if ($fileError) {
        $_SESSION['validation_error'] = $fileError;
        return 0;
    }

    $stmt = $conn->prepare("UPDATE `kategori_kelas` SET
    `jenis` = ?,
    `deskripsi` = ?,
    `foto` = ?
    WHERE `kategori_kelas_id` = ?");
    $stmt->bind_param("sssi", $jenis, $desc, $nama_file, $id);
    $stmt->execute();
    $stmt->close();

    return mysqli_affected_rows($conn);
}

function hapus($id){
    global $conn;

    $id = (int)$id;

    $stmt = $conn->prepare("SELECT `foto` FROM kategori_kelas WHERE kategori_kelas_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $foto = $result->fetch_assoc()['foto'];
    $stmt->close();

    if(file_exists("../picture/" . $foto)){
        unlink("../picture/" . $foto);
    }

    $stmt_del = $conn->prepare("DELETE FROM `kategori_kelas` WHERE `kategori_kelas_id` = ?");
    $stmt_del->bind_param("i", $id);
    $stmt_del->execute();
    $stmt_del->close();

    return mysqli_affected_rows($conn);
}
?>