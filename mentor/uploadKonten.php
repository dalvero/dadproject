<?php
session_start();
include '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';

$mentor_id = $_SESSION['mentor_id'] ?? null;
$kelas_id = isset($_GET['kelas_id']) ? (int) $_GET['kelas_id'] : null;

if (!$mentor_id || !$kelas_id) {
    die("Akses tidak sah atau parameter tidak lengkap.");
}

$queryKelas = mysqli_query($conn, "SELECT * FROM kelas WHERE kelas_id = '$kelas_id'");
$data_kelas = mysqli_fetch_assoc($queryKelas);

if (!$data_kelas) {
    die("Kelas tidak ditemukan.");
}


$queryMentor = mysqli_query($conn, "
    SELECT user.nama_lengkap 
    FROM mentors 
    JOIN user ON mentors.user_id = user.user_id 
    WHERE mentors.mentor_id = '$mentor_id'
");
$dataMentor = mysqli_fetch_assoc($queryMentor);
$nama_mentor = $dataMentor['nama_lengkap'] ?? 'Mentor';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Konten - <?= htmlspecialchars($data_kelas['title_kelas']) ?></title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="dashboard-container">
        <?php include 'components/sidebar.php'; ?>

        <div class="content-area">
            <header class="header-kelas">
                <h1 class="page-title">Detail Kelas: <?= htmlspecialchars($data_kelas['title_kelas']) ?></h1>
                <div class="tabs">
                    <a class="tab-item" href="kelolaMateri.php?kelas_id=<?= $kelas_id ?>">Konten</a>
                    <a href="#" class="tab-item active">Upload Konten</a>
                </div>
            </header>

            <main>
                <div class="form-container">
                    <form action="Controller.php" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="kelas_id" value="<?= htmlspecialchars($kelas_id) ?>">

                        <div class="form-group">
                            <label for="content_title">Judul Konten</label>
                            <input type="text" id="content_title" name="content_title"
                                placeholder="Contoh: Pengenalan HTML" required>
                        </div>

                        <div class="form-group">
                            <label for="content_type">Tipe Konten</label>
                            <select id="content_type" name="content_type" required>
                                <option value="" disabled selected>Pilih tipe konten</option>
                                <option value="video_url">Video (Link URL)</option>
                                <option value="video_file">Video (Upload File)</option>
                                <option value="document">Dokumen/Slide (PDF, PPT)</option>
                                <option value="text">Artikel Teks</option>
                            </select>
                        </div>


                        <div id="url-input-group" class="form-group" style="display: none;">
                            <label for="content_url">Link URL Video</label>
                            <input type="text" id="content_url" name="url_or_file"
                                placeholder="https://youtube.com/watch?v=... atau upload file di bawah">
                            <small>Masukkan link lengkap dari platform seperti YouTube atau Vimeo.</small>
                        </div>


                        <div id="file-input-group" class="form-group" style="display: none;">
                            <label for="content_file">Upload File</label>
                            <input type="file" id="content_file" name="content_file">
                            <small>Pilih file video (MP4) atau dokumen (PDF, PPTX).</small>
                        </div>


                        <div id="text-input-group" class="form-group" style="display: none;">
                            <label for="content_body">Isi Artikel</label>
                            <textarea id="content_body" name="content_body" rows="10"
                                placeholder="Tulis artikel atau materi teks di sini..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="urutan">Urutan Konten</label>
                            <input type="number" id="urutan" name="urutan" placeholder="Contoh: 1" required min="1">
                            <small>Urutan materi ini akan ditampilkan di dalam kelas.</small>
                        </div>

                        <div class="form-group">
                            <label for="content_deskripsi">Deskripsi Singkat (Opsional)</label>
                            <textarea id="content_deskripsi" name="content_deskripsi" rows="4"
                                placeholder="Jelaskan secara singkat isi dari konten ini..."></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn-submit">Simpan & Upload Konten</button>
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

            contentTypeSelect.addEventListener('change', function () {
                const selectedValue = this.value;

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
                    fileInput.required = true;
                } else if (selectedValue === 'text') {
                    textGroup.style.display = 'block';
                    textInput.required = true;
                }
            });
        });
    </script>

</body>

</html>