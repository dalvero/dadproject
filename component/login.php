<?php
session_start();
// MEMBUAT CALLBACK
require_once 'callback.php';
require_once '../koneksi/csrf.php';

// ARRAY ASSOC PESAN ERROR
$errors = [
    'login' => $_SESSION['login_error'] ?? ''
];
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" href="../picture/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/login/login.css">

    <!-- REMIXICON -->
    <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
        rel="stylesheet" />

    <!-- SCROLL REVEAL LIBRARY -->
    <script src="https://unpkg.com/scrollreveal"></script>

    <script>
        const isError = <?= !empty($errors['login']) ? 'true' : 'false' ?>;
    </script>
</head>

<body>
    <div class="toast" data-show-toast="<?= !empty($errors['login']) ? 'true' : 'false' ?>">
        <div class="toast-content">
            <i class="ri-error-warning-line check"></i>
            <div class="message">
                <span class="text text-1">Error</span>
                <span class="text text-2"><?= htmlspecialchars($errors['login']) ?></span>
            </div>
        </div>
        <i class="ri-close-large-fill close"></i>
        <div class="progress"></div>
    </div>

    <div class="login container">
        <div class="title">
            <h1>Welcome back!</h1>
            <h2>Let's login and join us to make a dream project</h1>
                <p class="subTitle">Stay focused, track your progress, and win the day</p>
        </div>

        <div class="containerForm">
            <form class="loginForm" action="cekAkun.php" method="post">
                <?= csrf_field() ?>
                <h1>Login</h1>
                <!-- Input email -->
                <div class="loginBox">
                    <input type="email" name="email" id="inputField" class="loginInput" placeholder=" " required>
                    <label for="inputField" class="loginLabel">Email</label>
                    <i class="ri-mail-line loginIcon"></i>
                </div>

                <!-- Input kata sandi -->
                <div class="loginBox">  
                    <input type="password" name="kataSandi" id="inputField" class="loginInput" placeholder=" " required>
                    <label for="inputField" class="loginLabel">Kata Sandi</label>
                    <i class="ri-lock-line loginIcon"></i>
                </div>

                <div class="loginBox">
                    <button type="submit" name="masuk" class="loginButton">Login</button>

                    <div class="registBox">
                        <p>Belum punya akun? <a href="register.php">Buat Akun</a></p>
                    </div>
                </div>

                <div class="socialIcon">
                    <a href="<?= $client->createAuthUrl() ?>"><i class="ri-google-fill"></i></a>
                    <i class="ri-github-fill"></i>
                    <i class="ri-facebook-circle-fill"></i>
                </div>
            </form>
        </div>
    </div>
    <svg class="loginSvg" viewBox="0 0 566 840" xmlns="http://www.w3.org/2000/svg">
        <mask id="mask0" mask-type="alpha">
            <path d="M342.407 73.6315C388.53 56.4007 394.378 17.3643 391.538
                0H566V840H0C14.5385 834.991 100.266 804.436 77.2046 707.263C49.6393
                591.11 115.306 518.927 176.468 488.873C363.385 397.026 156.98 302.824
                167.945 179.32C173.46 117.209 284.755 95.1699 342.407 73.6315Z" />
        </mask>

        <g mask="url(#mask0)">
            <path d="M342.407 73.6315C388.53 56.4007 394.378 17.3643 391.538
                0H566V840H0C14.5385 834.991 100.266 804.436 77.2046 707.263C49.6393
                591.11 115.306 518.927 176.468 488.873C363.385 397.026 156.98 302.824
                167.945 179.32C173.46 117.209 284.755 95.1699 342.407 73.6315Z" />

            <!-- Insert your image (recommended size: 1000 x 1200) -->
            <image class="loginImg" href="../picture/loginPict1.jpg" />
        </g>
    </svg>
    </div>

    <!-- SCRIPT -->
    <script src="../script/login.js"></script>
</body>

</html>