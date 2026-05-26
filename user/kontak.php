<?php
session_start();
require '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : null;

$profile_link = '#';
if ($user_role === 'admin') {
    $profile_link = 'admin/adminDashboard.php';
} elseif ($user_role === 'mentor') {
    $profile_link = 'mentor/mentorDashboard.php';
} elseif ($user_role === 'student') {
    $profile_link = 'student/dashboardStudent.php';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<style>
    hr {
        border-color: #9333EA;

    }
</style>

<body class="bg-[#0F172A]">
    <nav class="fixed top-0 right-0 left-0">
        <div class="flex h-20 p-10 bg-[#0F172A] items-center justify-between shadow-xl/30">
            <div class="flex justify-center items-center gap-5">
                <img class="h-10 hover:scale-105 duration-300" src="../picture/logo1.png" alt="Dad project">
            </div>
            <div class="flex justify-center items-center">
                <ul class="flex items-center text-white gap-10">
                    <li class="text-xl hover:text-[#BE185D] hover:scale-105 duration-300"><a
                            href="../index.php">Home</a></li>
                    <li class="text-xl hover:text-[#BE185D] hover:scale-105 duration-300"><a
                            href="menuKelas.php">Kelas</a></li>
                    <li class="text-xl hover:text-[#BE185D] hover:scale-105 duration-300"><a href="#">Kontak</a></li>
                    <?php if ($user_role === 'admin'): ?>
                        <li class="text-xl hover:text-[#BE185D] hover:scale-105 duration-300"><a
                                href="../admin/adminDashboard.php">Dashboard Admin</a></li>
                    <?php elseif ($user_role === 'mentor'): ?>
                        <li class="text-xl hover:text-[#BE185D] hover:scale-105 duration-300"><a
                                href="../mentor/mentorDashboard.php">Dashboard Mentor</a></li>
                    <?php elseif ($user_role === 'student'): ?>
                        <li class="text-xl hover:text-[#BE185D] hover:scale-105 duration-300"><a
                                href="../student/dashboardStudent.php">Dashboard Siswa</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="mx-10 mt-25">
        <h1 class="text-3xl text-white font-semibold">Kontak</h1>
        <br>
        <hr>
    </div>

    <div class="flex p-10 gap-10">
        <div class="flex justify-center items-center w-[30%] h-150 bg-[#1C2435] border-2 border-[#9333EA] rounded-xl">
            <img class="h-30" src="../picture/logo1.png" alt="">
        </div>

        <form class="flex flex-col p-10 w-[70%] h-150 bg-[#1C2435] border-2 border-[#9333EA] rounded-xl" method="POST"
            enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="flex flex-col text-xl">
                <ul>
                    <li class="flex flex-col text-xl">
                        <label class="text-base text-white" for="nama">Nama</label>
                        <input
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="text" name="nama" id="nama" required>
                    </li>

                    <li class="flex flex-col text-xl">
                        <label class="text-base text-white" for="email">Email</label>
                        <input
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="text" name="email" id="email" required>
                    </li>

                    <li class="flex flex-col text-xl">
                        <label class="text-base text-white" for="kategori">Pilih Kategori:</label>
                        <select class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"  name="kategori" id="kategori">
                            <option value="olahraga">saran</option>
                            <option value="musik">kritik</option>
                        </select>
                    </li>

                    <li class="flex flex-col text-xl">
                        <label class="text-base text-white" for="desc">Deskripsi</label>
                        <textarea
                            class="h-40 bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="text" name="desc" id="desc" required></textarea>
                    </li>
                </ul>
                <div class="flex justify-between mt-5">
                    <button class="bg-[#C084FC] w-30 p-2 rounded-md hover:bg-[#9333EA] hover:scale-102 duration-200"
                        type="submit" name="submit">Kirim</button>
                </div>
            </div>
        </form>
    </div>

</body>

</html>