<?php
session_start();
require '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';
require_once '../koneksi/validasi.php';

function redirectUser($role)
{
    switch ($role) {
        case 'admin':
            header('Location: ../admin/adminDashboard.php');
            break;
        case 'mentor':
            header('Location: ../mentor/mentorDashboard.php');
            break;
        case 'student':
            header('Location: ../student/dashboardStudent.php');
            break;
        default:
            $_SESSION['login_error'] = "Peran pengguna tidak dikenali.";
            header('Location: login.php');
            break;
    }
    exit();
}

if (isset($_POST['masuk'])) {
    verify_csrf_token();

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['kataSandi'] ?? '';

    $error = validate_required($email, 'Email') ?: validate_email($email)
           ?: validate_required($password, 'Kata sandi');
    if ($error) {
        $_SESSION['login_error'] = $error;
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];

            if ($user['role'] === 'mentor') {
                $stmtMentor = $conn->prepare("SELECT * FROM mentors WHERE user_id = ?");
                $stmtMentor->bind_param("i", $user['user_id']);
                $stmtMentor->execute();
                $mentorResult = $stmtMentor->get_result();
                $mentor = $mentorResult->fetch_assoc();
                $_SESSION['mentor_id'] = $mentor['mentor_id'];
                $stmtMentor->close();
            }

            $stmt->close();
            header("Location: ../index.php");
            exit();
        }
    }
    $stmt->close();

    $_SESSION['login_error'] = "Email atau kata sandi salah!";
    header("Location: login.php");
    exit();
} else if (isset($_POST['daftar'])) {
    verify_csrf_token();

    $nama_depan = trim($_POST['namaDepan'] ?? '');
    $nama_belakang = trim($_POST['namaBelakang'] ?? '');
    $nama_lengkap = $nama_depan . ' ' . $nama_belakang;
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $status = $_POST['status'] ?? '';
    $plainPassword = $_POST['kataSandi'] ?? '';

    $error = validate_required($nama_depan, 'Nama depan') ?: validate_max_length($nama_depan, 50, 'Nama depan')
           ?: validate_required($nama_belakang, 'Nama belakang') ?: validate_max_length($nama_belakang, 50, 'Nama belakang')
           ?: validate_required($email, 'Email') ?: validate_email($email)
           ?: validate_required($username, 'Username') ?: validate_min_length($username, 3, 'Username') ?: validate_max_length($username, 30, 'Username')
           ?: validate_required($plainPassword, 'Kata sandi') ?: validate_min_length($plainPassword, 6, 'Kata sandi')
           ?: validate_in($status, ['Mahasiswa', 'Siswa'], 'Status');
    if ($error) {
        $_SESSION['register_error'] = $error;
        header("Location: register.php");
        exit;
    }

    $password = password_hash($plainPassword, PASSWORD_BCRYPT);
    $role = "student";

    // Cek email duplikat
    $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['register_error'] = 'Email sudah terdaftar!';
        $stmt->close();
        header("Location: register.php");
        exit;
    }
    $stmt->close();

    // Cek username duplikat
    $stmt = $conn->prepare("SELECT username FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['register_error'] = 'Username sudah terdaftar!';
        $stmt->close();
        header("Location: register.php");
        exit;
    }
    $stmt->close();

    // Insert user
    $stmt = $conn->prepare("INSERT INTO user (nama_depan, nama_belakang, nama_lengkap, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nama_depan, $nama_belakang, $nama_lengkap, $username, $email, $password, $role);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    // Insert student
    $stmt = $conn->prepare("INSERT INTO students (user_id, nama_depan, nama_belakang, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $nama_depan, $nama_belakang, $status);
    $stmt->execute();
    $stmt->close();

    header("Location: login.php");
    exit;
}