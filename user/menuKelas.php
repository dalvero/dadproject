<?php
session_start();
require '../koneksi/koneksi.php'; // Pastikan koneksi $conn ada di sini

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$profile_link = '#';
if ($user_role === 'admin') {
    $profile_link = '../admin/adminDashboard.php';
} elseif ($user_role === 'mentor') {
    $profile_link = '../mentor/mentorDashboard.php';
} elseif ($user_role === 'student') {
    $profile_link = '../student/studentDashboard.php';
}

// --- LOGIKA PENCARIAN DAN FILTER DENGAN KEAMANAN (PREPARED STATEMENTS) ---
$kategori_filter = isset($_POST['kategori']) ? (int)$_POST['kategori'] : null;
$search = isset($_POST['search']) ? $_POST['search'] : null;

// Query dasar yang aman
$query = "SELECT k.kelas_id, k.title_kelas, k.foto_kelas, k.desk_kelas, m.nama_depan, m.nama_belakang, kk.jenis 
FROM kelas k 
JOIN mentors m ON k.mentor_id = m.mentor_id 
JOIN kategori_kelas kk ON k.kategori_id = kk.kategori_kelas_id 
WHERE 1=1";

$params = [];
$types = '';

if ($kategori_filter) {
    $query .= " AND k.kategori_id = ?";
    $params[] = $kategori_filter;
    $types .= 'i';
}
if ($search) {
    $query .= " AND k.title_kelas LIKE ?";
    $search_param = "%" . $search . "%";
    $params[] = $search_param;
    $types .= 's';
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$data_kelas = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Variabel untuk menampung script SweetAlert dari session
$sweetAlertScript = '';
if (isset($_SESSION['flash_message'])) {
    $flash = $_SESSION['flash_message'];
    $status = $flash['status'] ?? 'info';
    $title = $flash['title'] ?? 'Info';
    $message = $flash['message'] ?? '';
    $details = $flash['details'] ?? '';

    $htmlContent = "<p>" . addslashes($message) . "</p>";
    if (!empty($details)) {
        $htmlContent .= "<div>" . addslashes($details) . "</div>";
    }

    $sweetAlertScript = "<script>
        Swal.fire({
            icon: '" . $status . "',
            title: '" . addslashes($title) . "',
            html: `" . $htmlContent . "`,
            confirmButtonText: 'OK'
        });
    </script>";
    unset($_SESSION['flash_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSS untuk Pop-up -->
    <style>
        .popup-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none; justify-content: center; align-items: center; z-index: 1000;
        }
        .popup-overlay.show { display: flex; }
        .popup-container {
            background: #1C2435; color: white; padding: 2rem; border-radius: 12px;
            width: 90%; max-width: 450px; position: relative;
        }
        .popup-close-btn {
            position: absolute; top: 10px; right: 15px; background: none; border: none;
            font-size: 2rem; color: #aaa; cursor: pointer;
        }
        .form-container { display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem; }
        .form-container label { font-weight: 600; }
        .form-container .input {
            width: 100%; padding: 0.75rem; border: 1px solid #334155;
            border-radius: 8px; font-size: 1rem; background-color: #0F172A;
        }
        .popup-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem; }
        .btn-confirm { background-color: #BE185D; padding: 0.5rem 1.5rem; border-radius: 8px; }
        .btn-cancel { background-color: #475569; padding: 0.5rem 1.5rem; border-radius: 8px; }
    </style>
</head>
<body class="bg-[#0F172A]">
    <!-- Navbar (Kode Anda sudah baik) -->
    <nav class="fixed top-0 right-0 left-0 z-50">
        <!-- ... kode navbar Anda ... -->
    </nav>

    <main class="pt-24">
        <div class="flex justify-center items-center mt-10">
            <form class="flex gap-2" action="" method="post">
                <input class="h-5 w-60 p-5 bg-[#1C2435] border border-[#1C2435] focus:border-[#BE185D] focus:outline-none text-base text-white rounded-[20px] shadow-xl/30 hover:shadow-lg hover:shadow-[#BE185D] hover:scale-102 duration-300" type="text" name="search" placeholder="Cari kelas..." value="<?= htmlspecialchars($search ?? '') ?>">            
                <button type="submit" class="text-white px-4 rounded-lg bg-[#BE185D] hover:scale-105 duration-300">Cari</button>
            </form>
        </div>

        <section class="p-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 place-items-center w-full h-fit bg-[#1C2435] rounded-[15px] p-10">
                <?php if (count($data_kelas) > 0): ?>
                    <?php foreach ($data_kelas as $kelas): ?>
                    <div class="w-full max-w-sm rounded-xl overflow-hidden shadow-md hover:shadow-lg hover:scale-102 transition duration-300 bg-[#C084FC]">
                        <img class="h-40 w-full object-cover" src="../picture/<?= htmlspecialchars($kelas['foto_kelas']) ?>" alt="<?= htmlspecialchars($kelas['title_kelas']) ?>">
                        <div class="p-4">
                            <p class="mb-2 bg-[#581c87] w-fit px-2 py-1 text-white text-sm rounded-lg"><?= htmlspecialchars($kelas['jenis']) ?></p>
                            <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($kelas['title_kelas']) ?></h3>
                            <p class="mt-2 text-sm text-gray-700">Mentor: <?= htmlspecialchars($kelas['nama_depan'] . ' ' . $kelas['nama_belakang']) ?></p>
                            <button class="w-full mt-4 px-4 py-2 bg-white text-[#581c87] font-semibold rounded-lg shadow-md hover:bg-[#581c87] hover:text-white transition duration-300 border-2 border-[#581c87] join-btn" data-kelas-id="<?= $kelas['kelas_id'] ?>">
                                Join Kelas
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-white col-span-4">Kelas tidak ditemukan. Coba kata kunci atau filter lain.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Pop-up HTML -->
    <div id="popup-overlay" class="popup-overlay">
        <div class="popup-container">
            <button class="popup-close-btn">×</button>
            <h2>Enrollment Kelas</h2>
            <form id="enroll-form" action="../konfirmasiEnroll.php" method="POST" class="form-container">
                <input type="hidden" name="kelas_id" id="popup-hidden-kelas-id" value="">
                <label for="email">Email:</label>
                <input type="email" id="email" class="input" name="email" required placeholder="contoh@email.com">
                <label for="password">Password:</label>
                <input type="password" id="password" class="input" name="password" required>
                <div class="popup-actions">
                    <button type="button" id="popup-cancel-btn" class="btn-cancel">Batal</button>
                    <button type="submit" name="enroll" id="popup-confirm-btn" class="btn-confirm">Enroll</button>                                            
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript untuk Pop-up -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popupOverlay = document.getElementById('popup-overlay');
            const hiddenKelasIdInput = document.getElementById('popup-hidden-kelas-id');
            const popupCloseBtn = document.querySelector('.popup-close-btn');
            const popupCancelBtn = document.getElementById('popup-cancel-btn');

            function openPopup(kelasId) {
                hiddenKelasIdInput.value = kelasId;
                popupOverlay.classList.add('show');
            }

            function closePopup() {
                popupOverlay.classList.remove('show');
            }

            document.addEventListener('click', (e) => {
                // Gunakan e.target.closest untuk menangani klik pada elemen di dalam tombol
                const joinBtn = e.target.closest('.join-btn');
                if (joinBtn) {
                    // Cek jika user sudah login
                    <?php if (!$user_id): ?>
                        // Jika belum login, tampilkan pesan dan arahkan ke halaman login
                        Swal.fire({
                            title: 'Harap Login Terlebih Dahulu',
                            text: "Anda harus login untuk dapat bergabung dengan kelas.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Login Sekarang!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../component/login.php';
                            }
                        });
                    <?php else: ?>
                        // Jika sudah login, tampilkan pop-up form
                        const kelasId = joinBtn.dataset.kelasId;
                        openPopup(kelasId);
                    <?php endif; ?>
                }
            });

            popupCloseBtn.addEventListener('click', closePopup);
            popupCancelBtn.addEventListener('click', closePopup);
            popupOverlay.addEventListener('click', (e) => {
                if (e.target === popupOverlay) {
                    closePopup();
                }
            });
        });
    </script>
    
    <!-- Cetak script SweetAlert dari PHP -->
    <?= $sweetAlertScript ?>
</body>
</html>