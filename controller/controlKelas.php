<?php
require '../koneksi/koneksi.php';
require_once '../koneksi/validasi.php';

function tambah($data){
    global $conn;

    $title = trim($data['title'] ?? '');
    $desc = trim($data['desc'] ?? '');
    $mentor = $data['mentor'] ?? '';
    $kategori = $data['kategori'] ?? '';
    $enrollment = trim($data['enrollment'] ?? '');

    $error = validate_required($title, 'Judul kelas') ?: validate_max_length($title, 100, 'Judul kelas')
           ?: validate_required($desc, 'Deskripsi')
           ?: validate_integer($mentor, 'Mentor') ?: validate_positive($mentor, 'Mentor')
           ?: validate_integer($kategori, 'Kategori') ?: validate_positive($kategori, 'Kategori')
           ?: validate_required($enrollment, 'Enrollment key') ?: validate_max_length($enrollment, 50, 'Enrollment key');
    if ($error) {
        $_SESSION['validation_error'] = $error;
        return 0;
    }

    $lokasi_file = $_FILES['foto']['tmp_name'];
    $nama_file = $_FILES['foto']['name'];
    $size_file = $_FILES['foto']['size'];
    $direktori = '../picture/' . $nama_file;

    $fileError = validate_file_upload($_FILES['foto'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, 'Foto kelas');
    if ($fileError) {
        $_SESSION['validation_error'] = $fileError;
        return 0;
    }

    if(move_uploaded_file($lokasi_file, $direktori)){
        $stmt = $conn->prepare("INSERT INTO kelas (`title_kelas`, `foto_kelas`, `desk_kelas`, `mentor_id`, `kategori_id`)
                  VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $title, $nama_file, $desc, $mentor, $kategori);
        $stmt->execute();
        $kelas_id = $stmt->insert_id;
        $stmt->close();

        $stmt_key = $conn->prepare("INSERT INTO enrollment_key (`kelas_id`, `enrollment_key`)
                             VALUES (?, ?)");
        $stmt_key->bind_param("is", $kelas_id, $enrollment);
        $stmt_key->execute();
        $stmt_key->close();
    }

    return mysqli_affected_rows($conn);
}

function hapus($id){
    global $conn;

    $id = (int)$id;

    $stmt = $conn->prepare("SELECT `foto_kelas` FROM kelas WHERE kelas_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $foto = $data["foto_kelas"];
    $stmt->close();

    if(file_exists("../picture/" . $foto)){
        unlink("../picture/" . $foto);
    }

    $stmt_del = $conn->prepare("DELETE FROM `kelas` WHERE kelas_id = ?");
    $stmt_del->bind_param("i", $id);
    $stmt_del->execute();
    $stmt_del->close();

    return mysqli_affected_rows($conn);
}

function edit($edit, $id){
    global $conn;

    $id = (int)$id;
    $title = trim($edit['title'] ?? '');
    $desc = trim($edit['desc'] ?? '');
    $mentor = $edit['mentor'] ?? '';
    $kategori = $edit['kategori'] ?? '';
    $enrollment = trim($edit['enrollment'] ?? '');

    $error = validate_positive($id, 'Kelas ID')
           ?: validate_required($title, 'Judul kelas') ?: validate_max_length($title, 100, 'Judul kelas')
           ?: validate_required($desc, 'Deskripsi')
           ?: validate_integer($mentor, 'Mentor') ?: validate_positive($mentor, 'Mentor')
           ?: validate_integer($kategori, 'Kategori') ?: validate_positive($kategori, 'Kategori')
           ?: validate_required($enrollment, 'Enrollment key') ?: validate_max_length($enrollment, 50, 'Enrollment key');
    if ($error) {
        $_SESSION['validation_error'] = $error;
        return 0;
    }

    $lokasi_file = $_FILES['foto']['tmp_name'];
    $nama_file = $_FILES['foto']['name'];
    $size_file = $_FILES['foto']['size'];
    $direktori = '../picture/' . $nama_file;

    $fileError = validate_file_upload($_FILES['foto'], ['jpg', 'jpeg', 'png', 'gif', 'webp'], 5 * 1024 * 1024, 'Foto kelas');
    if ($fileError) {
        $_SESSION['validation_error'] = $fileError;
        return 0;
    }

    $stmt = $conn->prepare("UPDATE kelas SET
                `title_kelas` = ?,
                `foto_kelas` = ?,
                `desk_kelas` = ?,
                `mentor_id` = ?,
                `kategori_id` = ?
                WHERE kelas_id = ?");
    $stmt->bind_param("sssiii", $title, $nama_file, $desc, $mentor, $kategori, $id);
    $stmt->execute();
    $stmt->close();

    $stmt_key = $conn->prepare("UPDATE enrollment_key SET `enrollment_key` = ?
                             WHERE kelas_id = ?");
    $stmt_key->bind_param("si", $enrollment, $id);
    $stmt_key->execute();
    $stmt_key->close();

    return mysqli_affected_rows($conn);
}

?>