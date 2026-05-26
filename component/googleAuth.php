<?php
session_start();
require_once 'koneksi.php';
require_once 'callback.php';

if (!isset($_GET['code'])) {
    // Jika tidak ada kode, kembalikan ke login
    header('Location: login.php');
    exit();
}

try {
    // Ambil token akses dari Google
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    
    // Ambil info pengguna dari Google
    $googleAuth = new Google_Service_Oauth2($client);
    $google_info = $googleAuth->userinfo->get();
    $email = $google_info->email;
    $nama_lengkap = $google_info->name;
    $gambar_profil = $google_info->picture;
    
    // Cek apakah email sudah ada di database
    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    // KASUS 1: PENGGUNA SUDAH TERDAFTAR
    if ($user) {
        // ... (kode untuk set session sama persis seperti di login.php Anda) ...
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['info'] = [
            'name' => $user['nama_lengkap'],
            'email' => $user['email'],
            'picture' => $gambar_profil
        ];
        
        switch ($user['role']) {
            case 'admin':
                header('Location: ../admin/dashboardAdmin.php');
                break;
            case 'mentor':
                header('Location: ../mentor/dashboardMentor.php');
                break;
            default:
                header('Location: ../student/dashboardStudent.php');
                break;
        }
        exit();

    } 
    // KASUS 2: PENGGUNA BARU
    else {
        // ... (kode untuk membuat user baru sama persis seperti di login.php Anda) ...
        $role = 'student';
        $random_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $name_parts = explode(" ", $nama_lengkap, 2);
        $nama_depan = $name_parts[0];
        $nama_belakang = $name_parts[1] ?? '';

        // Insert ke tabel user
        $stmt_user = $conn->prepare("INSERT INTO user (nama_depan, nama_belakang, nama_lengkap, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_user->bind_param("ssssss", $nama_depan, $nama_belakang, $nama_lengkap, $email, $random_password, $role);
        $stmt_user->execute();
        
        $user_id = mysqli_insert_id($conn);

        // Insert ke tabel students
        $status_default = 'Aktif';
        $stmt_student = $conn->prepare("INSERT INTO students (user_id, nama_depan, nama_belakang, status) VALUES (?, ?, ?, ?)");
        $stmt_student->bind_param("isss", $user_id, $nama_depan, $nama_belakang, $status_default);
        $stmt_student->execute();
        
        // Set session untuk login
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        $_SESSION['info'] = ['name' => $nama_lengkap, 'email' => $email, 'picture' => $gambar_profil];

        // Arahkan ke dashboard student
        header('Location: ../student/dashboardStudent.php');
        exit();
    }

} catch (Exception $e) {
    $_SESSION['login_error'] = 'Gagal otentikasi dengan Google.';
    header('Location: login.php');
    exit();
}