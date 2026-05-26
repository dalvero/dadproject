<?php
session_start();
require '../koneksi/koneksi.php';

// Keamanan: Pastikan user login dan merupakan siswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../component/login.php");
    exit();
}

// Keamanan: Pastikan ada parameter 'id' (kelas_id) dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID kelas tidak valid.");
}
$kelas_id = (int) $_GET['id'];

// --- AMBIL DATA UTAMA ---

// 1. Ambil detail kelas (judul, deskripsi, dll)
$stmt_kelas = $conn->prepare("SELECT title_kelas, desk_kelas FROM kelas WHERE kelas_id = ?");
$stmt_kelas->bind_param("i", $kelas_id);
$stmt_kelas->execute();
$kelas = $stmt_kelas->get_result()->fetch_assoc();
if (!$kelas) {
    die("Kelas tidak ditemukan.");
}
$stmt_kelas->close();

// 2. Ambil semua daftar konten/materi untuk kelas ini, diurutkan
$stmt_konten = $conn->prepare("SELECT * FROM content WHERE kelas_id = ? ORDER BY urutan ASC");
$stmt_konten->bind_param("i", $kelas_id);
$stmt_konten->execute();
$konten_list = $stmt_konten->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_konten->close();

// 3. Tentukan konten pertama yang akan ditampilkan saat halaman dimuat
$konten_pertama = $konten_list[0] ?? null;

// Fungsi helper untuk render konten
function render_content_viewer($konten)
{
    if (!$konten) {
        echo '<div class="content-empty">Pilih materi di sebelah kanan untuk memulai.</div>';
        return;
    }

    $tipe = $konten['content_type'];
    $path = $konten['url_or_file'];
    $judul = htmlspecialchars($konten['content_title']);
    $deskripsi = nl2br(htmlspecialchars($konten['content_deskripsi'])); // nl2br untuk menjaga baris baru

    echo "<h1>{$judul}</h1>";
    echo "<p class='content-description'>{$deskripsi}</p>";
    echo "<div class='separator'></div>";

    switch ($tipe) {
        case 'video_file':
            echo "<video controls class='media-viewer' src='../content/{$path}'></video>";
            break;
        case 'video_url':
            // Cek apakah ini link YouTube
            if (preg_match('/(youtube\.com|youtu\.be)\/(watch\?v=)?([a-zA-Z0-9_-]+)/', $path, $matches)) {
                $youtube_id = $matches[3];
                echo "<div class='video-responsive'><iframe class='media-viewer' src='https://www.youtube.com/embed/{$youtube_id}' frameborder='0' allowfullscreen></iframe></div>";
            } else {
                echo "<p>Tipe video URL ini tidak didukung. <a href='{$path}' target='_blank'>Buka di tab baru</a></p>";
            }
            break;
        // VERSI BARU
        case 'document':
            // Cek ekstensi file
            $file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if ($file_extension == 'pdf') {
                // Jika PDF, tampilkan menggunakan iframe untuk viewer bawaan browser
                echo "<iframe class='media-viewer pdf-viewer' src='../content/{$path}'></iframe>";
            } else {
                // Jika bukan PDF (misal: PPTX, DOCX), berikan tombol download karena browser tidak bisa menampilkannya langsung
                echo "<div class='download-container'>
                <i class='fas fa-file-download'></i>
                <h4>File Siap Diunduh</h4>
                <p>Browser tidak dapat menampilkan file ini secara langsung. Silakan unduh untuk melihatnya.</p>
                <a href='../content/{$path}' class='btn-download' download>
                    Unduh File (" . htmlspecialchars($path) . ")
                </a>
              </div>";
            }
            break;
        case 'text':
            // Tampilkan artikel teks
            echo "<div class='text-viewer'>" . nl2br(htmlspecialchars($konten['content_body'])) . "</div>";
            break;
        default:
            echo "<div class='content-unsupported'>Tipe konten tidak dikenali.</div>";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi: <?= htmlspecialchars($kelas['title_kelas']) ?></title>
    <link rel="icon" href="../picture/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/detailKelas.css">
</head>

<body>
     <nav class="main-navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <a href="../index.php">
                    <img class="logo" src="../picture/logo1.png" alt="Dad Project Logo">
                </a>
            </div>
            <div class="navbar-right">
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="../student/studentDashboard.php">Dashboard</a></li>
                    <li><a href="#" class="active">Materi</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="materi-container">
        <main class="content-viewer-section" id="content-viewer">
            <?php render_content_viewer($konten_pertama); ?>
        </main>

        <aside class="playlist-section">
            <header class="playlist-header">
                <h2><?= htmlspecialchars($kelas['title_kelas']) ?></h2>
                <p><?= count($konten_list) ?> Materi</p>
            </header>
            <div class="playlist-items">
                <?php if (empty($konten_list)): ?>
                    <div class="playlist-item-empty">Belum ada materi di kelas ini.</div>
                <?php else: ?>
                    <?php foreach ($konten_list as $index => $item): ?>
                        <div class="playlist-item <?= ($index == 0) ? 'active' : '' ?>"
                            data-content-id="<?= $item['content_id'] ?>" data-type="<?= $item['content_type'] ?>"
                            data-path="<?= htmlspecialchars($item['url_or_file']) ?>"
                            data-title="<?= htmlspecialchars($item['content_title']) ?>"
                            data-desc="<?= htmlspecialchars($item['content_deskripsi']) ?>"
                            data-body="<?= htmlspecialchars($item['content_body'] ?? '') ?>">
                            <div class="item-icon">
                                <?php
                                $icon_class = 'fa-file-alt';
                                if (strpos($item['content_type'], 'video') !== false)
                                    $icon_class = 'fa-play-circle';
                                if ($item['content_type'] == 'document')
                                    $icon_class = 'fa-file-pdf';
                                ?>
                                <i class="fas <?= $icon_class ?>"></i>
                            </div>
                            <div class="item-info">
                                <span class="item-urutan">Materi <?= $item['urutan'] ?></span>
                                <h4 class="item-title"><?= htmlspecialchars($item['content_title']) ?></h4>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>
    </div>

    <script src="js/detailMateri.js"></script> 
</body>

</html>