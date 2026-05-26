<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<aside class="sidebar">
    <div class="user-profile">
        <div class="avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
        </div>
        <h2>Admin</h2>
        <p><?= htmlspecialchars($nama_lengkap ?? 'Admin'); ?></p>
    </div>
    <nav class="navigation">
        <ul>
            <li>
                <a href="../index.php" class="<?= ($current_page == '') ? 'active' : '' ?>">
                    Home
                </a>
            </li>
            <li>
                <a href="adminDashboard.php" class="<?= ($current_page == 'adminDashboard.php') ? 'active' : '' ?>">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="manageUser.php"
                    class="<?= ($current_page == 'manageUser.php' || $current_page == 'editUser.php' || $current_page == 'addUser.php') ? 'active' : '' ?>">
                    Pengguna
                </a>
            </li>
            <li>
                <a href="manageClasses.php"
                    class="<?= ($current_page == 'manageClasses.php' || $current_page == 'editKelas.php' || $current_page == 'addClasses.php') ? 'active' : '' ?>">
                    Kelas
                </a>
            </li>
            <li>
                <a href="manageCategoryClass.php"
                    class="<?= ($current_page == 'manageCategoryClass.php' || $current_page == 'addCategory.php' || $current_page == 'editCategory.php') ? 'active' : '' ?>">
                    Kategori Kelas
                </a>
            </li>
            <li>
                <a href="../component/comingSoon.php"
                    class="<?= ($current_page == 'comingSoon.php') ? 'active' : '' ?>">
                    Kontak
                </a>
            </li>
            <li>
                <a href="../logout.php">
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</aside>