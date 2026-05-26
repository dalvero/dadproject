<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas & Kuis - Dashboard Mentor</title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Manajemen Tugas & Kuis</h1>
                <button class="btn btn-primary">Buat Tugas/Kuis Baru</button>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kelas</th>
                            <th>Batas Waktu</th>
                            <th>Pengumpulan</th>
                            <th>Status</th>
                            <th class="action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Judul">Proyek Akhir: Membuat Landing Page</td>
                            <td data-label="Kelas">Web Development</td>
                            <td data-label="Batas Waktu">30 Des 2023, 23:59</td>
                            <td data-label="Pengumpulan">45/50</td>
                            <td data-label="Status"><span class="status-tag status-aktif">Aktif</span></td>
                            <td data-label="Aksi" class="action"><button class="btn btn-secondary">Lihat</button></td>
                        </tr>
                        <tr>
                            <td data-label="Judul">Kuis 1: Dasar-dasar UI/UX</td>
                            <td data-label="Kelas">Mobile App</td>
                            <td data-label="Batas Waktu">15 Des 2023, 23:59</td>
                            <td data-label="Pengumpulan">78/80</td>
                            <td data-label="Status"><span class="status-tag status-selesai">Selesai</span></td>
                            <td data-label="Aksi" class="action"><button class="btn btn-secondary">Lihat</button></td>
                        </tr>
                        <tr>
                            <td data-label="Judul">Tugas 3: Analisis Data Eksploratif</td>
                            <td data-label="Kelas">Data Science</td>
                            <td data-label="Batas Waktu">10 Des 2023, 23:59</td>
                            <td data-label="Pengumpulan">30/30</td>
                            <td data-label="Status"><span class="status-tag status-selesai">Selesai</span></td>
                            <td data-label="Aksi" class="action"><button class="btn btn-secondary">Lihat</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="js/navbar.js"></script>
</body>

</html>