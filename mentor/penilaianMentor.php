<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penilaian - Dashboard Mentor</title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Penilaian Siswa</h1>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Tugas/Kuis</th>
                            <th>Kelas</th>
                            <th>Tgl. Pengumpulan</th>
                            <th>Status</th>
                            <th class="action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Nama Siswa">Ahmad Subarjo</td>
                            <td data-label="Tugas/Kuis">Proyek Akhir: Membuat Landing Page</td>
                            <td data-label="Kelas">Web Development</td>
                            <td data-label="Tgl. Kumpul">28 Des 2023, 14:30</td>
                            <td data-label="Status"><span class="status-tag status-perlu-dinilai">Perlu Dinilai</span>
                            </td>
                            <td data-label="Aksi" class="action"><button class="btn btn-primary">Beri Nilai</button>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Nama Siswa">Budi Santoso</td>
                            <td data-label="Tugas/Kuis">Proyek Akhir: Membuat Landing Page</td>
                            <td data-label="Kelas">Web Development</td>
                            <td data-label="Tgl. Kumpul">27 Des 2023, 20:15</td>
                            <td data-label="Status"><span class="status-tag status-sudah-dinilai">Sudah Dinilai</span>
                            </td>
                            <td data-label="Aksi" class="action"><button class="btn btn-secondary">Edit Nilai</button>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Nama Siswa">Citra Lestari</td>
                            <td data-label="Tugas/Kuis">Tugas 2: Desain Mockup Aplikasi</td>
                            <td data-label="Kelas">Mobile App</td>
                            <td data-label="Tgl. Kumpul">26 Des 2023, 11:00</td>
                            <td data-label="Status"><span class="status-tag status-perlu-dinilai">Perlu Dinilai</span>
                            </td>
                            <td data-label="Aksi" class="action"><button class="btn btn-primary">Beri Nilai</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="js/navbar.js"></script>
</body>

</html>