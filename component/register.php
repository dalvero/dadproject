<?php
session_start();
require_once '../koneksi/csrf.php';

// ARRAY ASSOC PESAN ERROR
$errors = [
    'daftar' => $_SESSION['register_error'] ?? ''
];
unset($_SESSION['register_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- REMIXICON -->
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
        rel="stylesheet" />


    <link rel="shortcut icon" href="../picture/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/regist/regist.css">

    <!-- SCROLL REVEAL LIBRARY -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <script>
        const isError = <?= !empty($errors['daftar']) ? 'true' : 'false' ?>;
    </script>

</head>

<body>
    <div class="toast" data-show-toast="<?= !empty($errors['daftar']) ? 'true' : 'false' ?>">
        <div class="toast-content">
            <i class="ri-error-warning-line check"></i>
            <div class="message">
                <span class="text text-1">Error</span>
                <span class="text text-2"><?= htmlspecialchars($errors['daftar']) ?></span>
            </div>
        </div>
        <i class="ri-close-large-fill close"></i>
        <div class="progress"></div>
    </div>

    <div class="register container">
        <div class="leftSection">
            <h1 class="title">Register</h1>
            <h2 class="subTitle">Let's register and join us to make a dream project</h2>

            <svg class="registSvg" id="sw-js-blob-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
                <defs>
                    <clipPath id="blobClip">
                        <path d="M22.4,-39.2C28.3,-35.4,31.8,-27.9,33.7,-20.7C35.5,-13.6,35.7,-6.8,35.8,0.1C35.9,6.9,35.9,13.8,33.3,19.7C30.7,25.5,25.5,30.3,19.5,34.7C13.5,39.1,6.8,43.1,0.4,42.5C-6,41.9,-12.1,36.6,-19,32.8C-25.9,28.9,-33.7,26.4,-36.7,21.2C-39.7,16,-37.9,8,-38,0C-38,-8.1,-40,-16.1,-36.8,-21.1C-33.7,-26.2,-25.6,-28.2,-18.6,-31.3C-11.6,-34.5,-5.8,-38.8,1.2,-40.9C8.2,-43,16.5,-42.9,22.4,-39.2Z"
                            transform="translate(50 50)" />
                    </clipPath>
                </defs>

                <!-- Gambar yang ditempel di dalam bentuk blob -->
                <image href="../picture/registPict.jpg" x="0" y="0" width="100" height="100" clip-path="url(#blobClip)" preserveAspectRatio="xMidYMid slice" />
            </svg>
        </div>

        <div class="containerForm">
            <!-- DIRECTION KE CEK AKUN UNTUK VALIDASI -->
            <form class="registerForm" action="cekAkun.php" method="post">
                <?= csrf_field() ?>
                <!-- Input nama depan -->
                <div class="registerBox">
                    <input type="text" name="namaDepan" id="inputField" class="registerInput" placeholder=" " required>
                    <label for="inputField" class="registerLabel">Nama Depan</label>
                    <i class="ri-user-fill registIcon"></i>
                </div>

                <!-- Input nama belakang -->
                <div class="registerBox">
                    <input type="text" name="namaBelakang" id="inputField" class="registerInput" placeholder=" " required>
                    <label for="inputField" class="registerLabel">Nama Belakang</label>
                    <i class="ri-user-fill registIcon"></i>
                </div>

                <!-- Input email -->
                <div class="registerBox">
                    <input type="email" name="email" id="inputField" class="registerInput" placeholder=" " required>
                    <label for="inputField" class="registerLabel">Email</label>
                    <i class="ri-mail-line registIcon"></i>
                </div>

                <!-- Input username -->
                <div class="registerBox">
                    <input type="username" name="username" id="inputField" class="registerInput" placeholder=" " required>
                    <label for="inputField" class="registerLabel">Username</label>
                    <i class="ri-id-card-fill registIcon"></i>
                </div>

                <!-- Input kata sandi -->
                <div class="registerBox">
                    <input type="password" name="kataSandi" id="inputField" class="registerInput" placeholder=" " required>
                    <label for="inputField" class="registerLabel">Kata Sandi</label>
                    <i class="ri-lock-fill registIcon"></i>
                </div>

                <!-- Combo box -->
                <div class="registerBox">
                    <label for="status" class="labelSelect">Status pengguna</label>
                    <select class="statusBox" name="status" id="status">
                        <option value="Mahasiswa">Mahasiswa</option>
                        <option value="Siswa">Siswa</option>
                    </select>
                </div>

                <div class="registerBox">
                    <button type="submit" name="daftar" class="registButton">Daftar</button>
                    <div class="loginBox">
                        <p>Sudah punya akun? <a href="login.php">Masuk Akun</a></p>
                    </div>
                </div>
        </div>
        </form>
    </div>

    <!-- SCRIPT -->
    <script src="../script/regist.js"></script>
</body>

</html>