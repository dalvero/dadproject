<?php
session_start();
require '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';
require_once '../koneksi/validasi.php';
function tambahKonten($data)
{
    global $conn;

    $kelas_id = (int) $data['kelas_id'];
    $content_title = trim($data['content_title'] ?? '');
    $content_type = $data['content_type'] ?? '';
    $urutan = (int) ($data['urutan'] ?? 0);
    $content_deskripsi = trim($data['content_deskripsi'] ?? '');
    $content_value = '';

    $error = validate_positive($kelas_id, 'Kelas ID')
           ?: validate_required($content_title, 'Judul konten') ?: validate_max_length($content_title, 200, 'Judul konten')
           ?: validate_in($content_type, ['video_url', 'video_file', 'document', 'text'], 'Tipe konten')
           ?: validate_positive($urutan, 'Urutan');
    if ($error) {
        $_SESSION['error_message'] = $error;
        return 0;
    }

    switch ($content_type) {
        case 'video_url':
            $content_value = $data['url_or_file'];
            break;

        case 'video_file':
            $videoError = validate_file_upload($_FILES['content_file'] ?? [], ['mp4', 'avi', 'mov', 'wmv', 'webm', 'mkv'], 500 * 1024 * 1024, 'video');
            if ($videoError) {
                $_SESSION['error_message'] = $videoError;
                return 0;
            }
            // fall through
        case 'document':
            if ($content_type === 'document') {
                $docError = validate_file_upload($_FILES['content_file'] ?? [], ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'], 50 * 1024 * 1024, 'dokumen');
                if ($docError) {
                    $_SESSION['error_message'] = $docError;
                    return 0;
                }
            }
            if (isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {

                $target_dir = "../content/";

                $file_extension = pathinfo($_FILES["content_file"]["name"], PATHINFO_EXTENSION);
                $unique_filename = uniqid('konten_', true) . '.' . $file_extension;
                $target_file = $target_dir . $unique_filename;

                if (move_uploaded_file($_FILES['content_file']['tmp_name'], $target_file)) {
                    $content_value = $unique_filename;
                } else {
                    $_SESSION['error_message'] = "Gagal mengupload file.";
                    return 0;
                }
            } else {
                $_SESSION['error_message'] = "Tidak ada file yang diupload atau terjadi error.";
                return 0;
            }
            break;

        case 'text':
            $content_value = $data['content_body'];
            break;

        default:
            $_SESSION['error_message'] = "Tipe konten tidak valid.";
            return 0;
    }

    $stmt = $conn->prepare("INSERT INTO content (kelas_id, content_title, content_type, url_or_file, urutan, content_deskripsi)
              VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $kelas_id, $content_title, $content_type, $content_value, $urutan, $content_deskripsi);
    $stmt->execute();

    $affected_rows = $stmt->affected_rows;
    $stmt->close();

    if ($affected_rows > 0) {
        $_SESSION['success_message'] = "Konten berhasil ditambahkan!";
    } else {
        $_SESSION['error_message'] = "Gagal menyimpan data ke database. Error: " . mysqli_error($conn);
    }

    return $affected_rows;
}

function editKonten($data)
{
    global $conn;

    $content_id = (int) $data['content_id'];
    $kelas_id = (int) $data['kelas_id'];
    $content_title = trim($data['content_title'] ?? '');
    $content_type = $data['content_type'] ?? '';
    $urutan = (int) ($data['urutan'] ?? 0);
    $content_deskripsi = trim($data['content_deskripsi'] ?? '');
    $old_file_name = $data['old_file_name'] ?? '';
    $content_value = '';

    $error = validate_positive($content_id, 'Content ID')
           ?: validate_positive($kelas_id, 'Kelas ID')
           ?: validate_required($content_title, 'Judul konten') ?: validate_max_length($content_title, 200, 'Judul konten')
           ?: validate_in($content_type, ['video_url', 'video_file', 'document', 'text'], 'Tipe konten')
           ?: validate_positive($urutan, 'Urutan');
    if ($error) {
        $_SESSION['error_message'] = $error;
        return;
    }

    switch ($content_type) {
        case 'video_url':
            $content_value = $data['url_or_file'];
            if (!empty($old_file_name) && file_exists('../content/' . $old_file_name)) {
                unlink('../content/' . $old_file_name);
            }
            break;

        case 'video_file':
            $videoError = validate_file_upload($_FILES['content_file'] ?? [], ['mp4', 'avi', 'mov', 'wmv', 'webm', 'mkv'], 500 * 1024 * 1024, 'video');
            if ($videoError) {
                $_SESSION['error_message'] = $videoError;
                return;
            }
            // fall through
        case 'document':
            if ($content_type === 'document') {
                $docError = validate_file_upload($_FILES['content_file'] ?? [], ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'], 50 * 1024 * 1024, 'dokumen');
                if ($docError) {
                    $_SESSION['error_message'] = $docError;
                    return;
                }
            }
            if (isset($_FILES['content_file']) && $_FILES['content_file']['error'] === UPLOAD_ERR_OK) {
                if (!empty($old_file_name) && file_exists('../content/' . $old_file_name)) {
                    unlink('../content/' . $old_file_name);
                }

                $target_dir = "../content/";
                $file_extension = pathinfo($_FILES["content_file"]["name"], PATHINFO_EXTENSION);
                $unique_filename = uniqid('konten_', true) . '.' . $file_extension;
                $target_file = $target_dir . $unique_filename;

                if (move_uploaded_file($_FILES['content_file']['tmp_name'], $target_file)) {
                    $content_value = $unique_filename;
                } else {
                    $_SESSION['error_message'] = "Gagal mengupload file baru.";
                    return;
                }
            } else {
                $content_value = $old_file_name;
            }
            break;

        case 'text':
            $content_value = $data['content_body'];
            if (!empty($old_file_name) && file_exists('../content/' . $old_file_name)) {
                unlink('../content/' . $old_file_name);
            }
            break;
    }

    $stmt = $conn->prepare("UPDATE content SET
                content_title = ?,
                content_type = ?,
                url_or_file = ?,
                urutan = ?,
                content_deskripsi = ?
              WHERE content_id = ?");
    $stmt->bind_param("sssisi", $content_title, $content_type, $content_value, $urutan, $content_deskripsi, $content_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Konten berhasil diperbarui!";
    } else {
        $_SESSION['error_message'] = "Gagal memperbarui konten. Error: " . $stmt->error;
    }
    $stmt->close();
}

function hapusKonten($data)
{
    global $conn;

    $content_id = (int) $data['content_id'];

    $stmt = $conn->prepare("SELECT content_type, url_or_file FROM content WHERE content_id = ?");
    $stmt->bind_param("i", $content_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $content = $result->fetch_assoc();
        $content_type = $content['content_type'];
        $file_name = $content['url_or_file'];
        $stmt->close();

        $stmt_del = $conn->prepare("DELETE FROM content WHERE content_id = ?");
        $stmt_del->bind_param("i", $content_id);
        $stmt_del->execute();

        if ($stmt_del->affected_rows > 0) {
            if (($content_type === 'video_file' || $content_type === 'document') && !empty($file_name)) {
                $file_path = '../content/' . $file_name;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            $_SESSION['success_message'] = "Konten berhasil dihapus.";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus konten dari database.";
        }
        $stmt_del->close();
    } else {
        $_SESSION['error_message'] = "Konten tidak ditemukan.";
        $stmt->close();
    }
}

function updateProfilMentor($data)
{
    global $conn;

    $mentor_id = (int) $_SESSION['mentor_id'];

    $stmt = $conn->prepare("SELECT user_id FROM mentors WHERE mentor_id = ?");
    $stmt->bind_param("i", $mentor_id);
    $stmt->execute();
    $user_data = $stmt->get_result()->fetch_assoc();
    $user_id = (int) $user_data['user_id'];
    $stmt->close();

    $nama_lengkap = trim($data['nama_lengkap'] ?? '');
    $password_baru = $data['password_baru'] ?? '';
    $konfirmasi_password = $data['konfirmasi_password'] ?? '';

    $error = validate_required($nama_lengkap, 'Nama lengkap') ?: validate_max_length($nama_lengkap, 100, 'Nama lengkap');
    if ($error) {
        $_SESSION['error_message'] = $error;
        return;
    }

    if (!empty($password_baru)) {
        $err = validate_min_length($password_baru, 6, 'Password baru');
        if ($err) {
            $_SESSION['error_message'] = $err;
            return;
        }
    }

    $stmt_nama = $conn->prepare("UPDATE user SET nama_lengkap = ? WHERE user_id = ?");
    $stmt_nama->bind_param("si", $nama_lengkap, $user_id);
    $stmt_nama->execute();
    $stmt_nama->close();

    if (!empty($password_baru)) {
        if ($password_baru !== $konfirmasi_password) {
            $_SESSION['error_message'] = "Password baru dan konfirmasi tidak cocok.";
            return;
        }

        $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);

        $stmt_pass = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
        $stmt_pass->bind_param("si", $hashed_password, $user_id);
        $stmt_pass->execute();
        $stmt_pass->close();
    }

    $_SESSION['success_message'] = "Silahkan Relogin agar perubahan dapat terlihat";
}

function hapusAkunMentor()
{
    global $conn;

    $mentor_id = (int) $_SESSION['mentor_id'];

    $stmt = $conn->prepare("SELECT user_id FROM mentors WHERE mentor_id = ?");
    $stmt->bind_param("i", $mentor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $stmt->close();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }
    $user_data = $result->fetch_assoc();
    $user_id = (int) $user_data['user_id'];
    $stmt->close();

    // Hapus file konten mentor
    $stmt_konten = $conn->prepare("SELECT c.url_or_file FROM content c JOIN kelas k ON c.kelas_id = k.kelas_id WHERE k.mentor_id = ? AND (c.content_type = 'video_file' OR c.content_type = 'document')");
    $stmt_konten->bind_param("i", $mentor_id);
    $stmt_konten->execute();
    $result_konten = $stmt_konten->get_result();
    while ($konten = $result_konten->fetch_assoc()) {
        $file_path = '../content/' . $konten['url_or_file'];
        if (file_exists($file_path))
            unlink($file_path);
    }
    $stmt_konten->close();

    $stmt_del_mentor = $conn->prepare("DELETE FROM mentors WHERE mentor_id = ?");
    $stmt_del_mentor->bind_param("i", $mentor_id);
    $stmt_del_mentor->execute();
    $stmt_del_mentor->close();

    $stmt_del_user = $conn->prepare("DELETE FROM user WHERE user_id = ?");
    $stmt_del_user->bind_param("i", $user_id);
    $stmt_del_user->execute();
    $stmt_del_user->close();

    session_destroy();
    header("Location: ../index.php?pesan=akun_dihapus");
    exit();
}


// ==================================================================================
$action = $_REQUEST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();
    switch ($action) {
        case 'edit':
            editKonten($_POST);
            $redirect_url = "kelolaMateri.php?kelas_id=" . (int) $_POST['kelas_id'];
            break;
        case 'update_profil':
            updateProfilMentor($_POST);
            $redirect_url = "mentorDashboard.php";
            break;
        default:
            tambahKonten($_POST);
            $redirect_url = "kelolaMateri.php?kelas_id=" . (int) $_POST['kelas_id'];
            break;
    }
    header("Location: " . $redirect_url);
    exit();

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['mentor_id'])) {
        die("Akses ditolak.");
    }

    switch ($action) {
        case 'hapus':
            hapusKonten($_GET);
            $redirect_url = "kelolaMateri.php?kelas_id=" . (int) $_GET['kelas_id'];
            break;
        case 'hapus_akun':
            hapusAkunMentor();
            break;
        default:
            die("Aksi tidak dikenal untuk metode GET.");
    }
    header("Location: " . $redirect_url);
    exit();

} else {
    die("Metode request tidak diizinkan.");
}