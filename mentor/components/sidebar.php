<?php
$nama_lengkap = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Mentor';
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<aside class="sidebar">
    <div class="user-profile">
        <div class="avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
        </div>
        <h2>Mentor</h2>
        <p><?= htmlspecialchars($nama_lengkap); ?></p>
    </div>
    <nav class="navigation">
        <ul>
            <li>
                <a href="../index.php">Home</a>
            </li>
            <li>
                <a href="mentorDashboard.php" 
                   class="<?= ($current_page == 'mentorDashboard.php' || $current_page == 'kelolaMateri.php' || $current_page == 'uploadKonten.php' || $current_page == 'editKonten.php') ? 'active' : '' ?>">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="../component/comingSoon.php" 
                   class="<?= ($current_page == 'comingSoon.php' && strpos($_SERVER['REQUEST_URI'], 'Tugas') !== false) ? 'active' : '' ?>">
                    Tugas & Kuis
                </a>
            </li>
            <li>
                <a href="../component/comingSoon.php"
                   class="<?= ($current_page == 'comingSoon.php' && strpos($_SERVER['REQUEST_URI'], 'Penilaian') !== false) ? 'active' : '' ?>">
                    Penilaian
                </a>
            </li>
            <li>
                <a href="../component/comingSoon.php"
                   class="<?= ($current_page == 'comingSoon.php' && strpos($_SERVER['REQUEST_URI'], 'Sertifikat') !== false) ? 'active' : '' ?>">
                    Sertifikat Kelulusan
                </a>
            </li>
            <li>
                <a href="pengaturanMentor.php" 
                   class="<?= ($current_page == 'pengaturanMentor.php') ? 'active' : '' ?>">
                    Pengaturan
                </a>
            </li>
            <li>
                <a href="../logout.php">Keluar</a>
            </li>
        </ul>
    </nav>
</aside>