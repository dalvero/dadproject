<?php
session_start();
include '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';

if (!isset($_SESSION['mentor_id'])) {
    die("Akses ditolak. Silakan login sebagai mentor.");
}
if (!isset($_GET['content_id']) || !is_numeric($_GET['content_id'])) {
    die("Parameter konten tidak valid.");
}

$mentor_id = $_SESSION['mentor_id'];
$content_id = (int)$_GET['content_id'];

$queryContent = mysqli_query($conn, "SELECT * FROM content WHERE content_id = '$content_id'");
$content = mysqli_fetch_assoc($queryContent);

if (!$content) {
    die("Konten tidak ditemukan.");
}

$kelas_id = $content['kelas_id'];
$queryKelas = mysqli_query($conn, "SELECT * FROM kelas WHERE kelas_id = '$kelas_id'");
$data_kelas = mysqli_fetch_assoc($queryKelas);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Konten - <?= htmlspecialchars($content['content_title']) ?></title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <?php include 'components/sidebar.php'; ?>

        <div class="content-area">
            <header class="header-kelas">
                <h1 class="page-title">Edit Konten di Kelas: <?= htmlspecialchars($data_kelas['title_kelas']) ?></h1>
                <a href="kelolaMateri.php?kelas_id=<?= $kelas_id ?>" class="btn-action btn-back" title="Kembali ke Daftar Konten">
                    <i class="fa-solid fa-left-long"><span>kembali</span></i>
                </a>
            </header>

            <main>
                <div class="form-container">

                    <form action="Controller.php?action=edit" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="content_id" value="<?= $content['content_id'] ?>">
                        <input type="hidden" name="kelas_id" value="<?= $kelas_id ?>">
                        <input type="hidden" name="old_file_name" value="<?= htmlspecialchars($content['url_or_file']) ?>">

                        <div class="form-group">
                            <label for="content_title">Judul Konten</label>
                            <input type="text" id="content_title" name="content_title" required
                                value="<?= htmlspecialchars($content['content_title']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="content_type">Tipe Konten</label>
                            <select id="content_type" name="content_type" required>
                                <option value="" disabled>Pilih tipe konten</option>
                                <option value="video_url" <?= $content['content_type'] == 'video_url' ? 'selected' : '' ?>>Video (Link URL)</option>
                                <option value="video_file" <?= $content['content_type'] == 'video_file' ? 'selected' : '' ?>>Video (Upload File)</option>
                                <option value="document" <?= $content['content_type'] == 'document' ? 'selected' : '' ?>>Dokumen/Slide (PDF, PPT)</option>
                                <option value="text" <?= $content['content_type'] == 'text' ? 'selected' : '' ?>>Artikel Teks</option>
                            </select>
                        </div>

                        <div id="url-input-group" class="form-group">
                            <label for="content_url">Link URL Video</label>
                            <input type="url" id="content_url" name="url_or_file" value="<?= $content['content_type'] == 'video_url' ? htmlspecialchars($content['url_or_file']) : '' ?>">
                        </div>

                        <div id="file-input-group" class="form-group">
                            <label for="content_file">Upload File Baru (Opsional)</label>
                            <?php if (($content['content_type'] == 'video_file' || $content['content_type'] == 'document') && !empty($content['url_or_file'])): ?>
                                <p class="current-file-info">File saat ini: <strong><?= htmlspecialchars($content['url_or_file']) ?></strong></p>
                            <?php endif; ?>
                            <input type="file" id="content_file" name="content_file">
                            <small>Kosongkan jika tidak ingin mengubah file.</small>
                        </div>

                        <div id="text-input-group" class="form-group">
                            <label for="content_body">Isi Artikel</label>
                            <textarea id="content_body" name="content_body" rows="10"><?= $content['content_type'] == 'text' ? htmlspecialchars($content['content_body']) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="urutan">Urutan Konten</label>
                            <input type="number" id="urutan" name="urutan" required min="1" value="<?= $content['urutan'] ?>">
                        </div>

                        <div class="form-group">
                            <label for="content_deskripsi">Deskripsi Singkat (Opsional)</label>
                            <textarea id="content_deskripsi" name="content_deskripsi" rows="4"><?= htmlspecialchars($content['content_deskripsi']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-submit">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const contentTypeSelect = document.getElementById('content_type');
            const urlGroup = document.getElementById('url-input-group');
            const fileGroup = document.getElementById('file-input-group');
            const textGroup = document.getElementById('text-input-group');
            const urlInput = document.getElementById('content_url');
            const fileInput = document.getElementById('content_file');
            const textInput = document.getElementById('content_body');

            function toggleContentInputs() {
                const selectedValue = contentTypeSelect.value;
                urlGroup.style.display = 'none';
                urlInput.required = false;
                fileGroup.style.display = 'none';
                fileInput.required = false;
                textGroup.style.display = 'none';
                textInput.required = false;

                if (selectedValue === 'video_url') {
                    urlGroup.style.display = 'block';
                    urlInput.required = true;
                } else if (selectedValue === 'video_file' || selectedValue === 'document') {
                    fileGroup.style.display = 'block';
                } else if (selectedValue === 'text') {
                    textGroup.style.display = 'block';
                    textInput.required = true;
                }
            }
            contentTypeSelect.addEventListener('change', toggleContentInputs);
            toggleContentInputs();
        });
    </script>

</body>
</html>