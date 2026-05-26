<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat - Dashboard Mentor</title>
    <link rel="stylesheet" href="css/mentorDashboard.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include 'components/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Sertifikat Kelulusan</h1>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tgl. Kelulusan</th>
                            <th>Status Sertifikat</th>
                            <th class="action">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Nama Siswa">Dewi Anggraini</td>
                            <td data-label="Kelas">Data Science</td>
                            <td data-label="Tgl. Lulus">20 Des 2023</td>
                            <td data-label="Status"><span class="status-tag status-diterbitkan">Diterbitkan</span></td>
                            <td data-label="Aksi" class="action"><button class="btn btn-secondary">Lihat</button></td>
                        </tr>
                        <tr>
                            <td data-label="Nama Siswa">Eko Prasetyo</td>
                            <td data-label="Kelas">Data Science</td>
                            <td data-label="Tgl. Lulus">20 Des 2023</td>
                            <td data-label="Status"><span class="status-tag status-belum-diterbitkan">Belum
                                    Diterbitkan</span></td>
                            <td data-label="Aksi" class="action"><button class="btn btn-primary">Terbitkan</button></td>
                        </tr>
                        <tr>
                            <td data-label="Nama Siswa">Fajar Nugroho</td>
                            <td data-label="Kelas">Data Science</td>
                            <td data-label="Tgl. Lulus">20 Des 2023</td>
                            <td data-label="Status"><span class="status-tag status-diterbitkan">Diterbitkan</span></td>
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