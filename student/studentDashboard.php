<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../component/login.php'); 
    exit(); 
}

include '../koneksi/koneksi.php';
require_once '../koneksi/csrf.php';
require_once '../koneksi/validasi.php';
$currentPage = $_GET['page'] ?? 'kelas';
$user_id = (int) $_SESSION['user_id'];
$sweetAlertScript = ''; 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_enroll'])) {
    verify_csrf_token();

    $enrollKey = trim($_POST['enrollment_key'] ?? '');
    $error = validate_required($enrollKey, 'Enrollment key') ?: validate_max_length($enrollKey, 50, 'Enrollment key');
    if ($error) {
        $sweetAlertScript = "<script>Swal.fire('Perhatian!', '" . addslashes($error) . "', 'warning');</script>";
    } elseif (!empty($enrollKey)) {

        $stmt_student = $conn->prepare("SELECT student_id FROM students WHERE user_id = ?");
        $stmt_student->bind_param("i", $user_id);
        $stmt_student->execute();
        $studentResult = $stmt_student->get_result();

        if ($studentResult->num_rows > 0) {
            $student_id = $studentResult->fetch_assoc()['student_id'];

            $stmt_key = $conn->prepare("SELECT kelas_id FROM enrollment_key WHERE enrollment_key = ?");
            $stmt_key->bind_param("s", $enrollKey);
            $stmt_key->execute();
            $keyResult = $stmt_key->get_result();

            if ($keyResult->num_rows > 0) {
                $kelas_id = $keyResult->fetch_assoc()['kelas_id'];

                $stmt_check = $conn->prepare("SELECT kelas_student_id FROM kelas_student WHERE student_id = ? AND course_id = ?");
                $stmt_check->bind_param("ii", $student_id, $kelas_id);
                $stmt_check->execute();

                if ($stmt_check->get_result()->num_rows == 0) {
                    $stmt_insert = $conn->prepare("INSERT INTO kelas_student (student_id, course_id) VALUES (?, ?)");
                    $stmt_insert->bind_param("ii", $student_id, $kelas_id);
                    if ($stmt_insert->execute()) {
                        $sweetAlertScript = "<script>
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Anda telah berhasil terdaftar di kelas baru.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => { window.location.href = 'studentDashboard.php?page=kelas'; });
                        </script>";
                    }
                    $stmt_insert->close();
                } else {
                    $sweetAlertScript = "<script>Swal.fire('Info', 'Anda sudah terdaftar di kelas ini.', 'info');</script>";
                }
                $stmt_check->close();
            } else {
                $sweetAlertScript = "<script>Swal.fire('Gagal!', 'Enrollment key yang Anda masukkan tidak valid.', 'error');</script>";
            }
            $stmt_key->close();
        }
        $stmt_student->close();
    } else {
        $sweetAlertScript = "<script>Swal.fire('Perhatian!', 'Harap masukkan enrollment key.', 'warning');</script>";
    }
}

$stmt_get_student_id = $conn->prepare("SELECT student_id FROM students WHERE user_id = ?");
$stmt_get_student_id->bind_param("i", $user_id);
$stmt_get_student_id->execute();
$student_id_result = $stmt_get_student_id->get_result();
$student_id = ($student_id_result->num_rows > 0) ? $student_id_result->fetch_assoc()['student_id'] : 0;
$stmt_get_student_id->close();

$limit = 4;
$page = isset($_GET['p']) ? (int) $_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM kelas_student WHERE student_id = ?");
$stmt_total->bind_param("i", $student_id);
$stmt_total->execute();
$totalData = $stmt_total->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalData / $limit);
$stmt_total->close();

$query = "
    SELECT 
        k.kelas_id, k.title_kelas, k.foto_kelas, kk.jenis AS kategori
    FROM kelas_student ks
    JOIN kelas k ON ks.course_id = k.kelas_id
    JOIN kategori_kelas kk ON k.kategori_id = kk.kategori_kelas_id
    WHERE ks.student_id = ? ORDER BY k.title_kelas ASC LIMIT ? OFFSET ?";
$stmt_data = $conn->prepare($query);
$stmt_data->bind_param("iii", $student_id, $limit, $offset);
$stmt_data->execute();
$result = $stmt_data->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <aside class="sidebar-section">
            <?php include 'components/sidebarStudent.php'; ?>
        </aside>

        <main class="main-content-area">
            <nav class="main-navbar">
                <ul class="nav-links">
                    <li><a href="../index.php">Home</a></li>
                    <li><a href="studentDashboard.php?page=notifikasi"
                            class="<?= ($currentPage == 'notifikasi') ? 'active' : '' ?>">Notifikasi</a></li>
                    <li><a href="studentDashboard.php?page=kelas"
                            class="<?= ($currentPage == 'kelas') ? 'active' : '' ?>">Kelas Saya</a></li>
                </ul>
            </nav>

            <div class="content-wrapper">
                <?php if ($currentPage == 'notifikasi'): ?>
                    <header class="content-header">
                        <h1>Notifikasi</h1>
                        <p>Daftar pemberitahuan penting untuk Anda.</p>
                    </header>
                    <div class="notification-list">
                        <div class="notification-item">
                            <p>Fitur notifikasi akan segera hadir. Pantau terus ya!</p>
                        </div>
                    </div>
                <?php elseif ($currentPage == 'kelas'): ?>
                    <header class="content-header">
                        <h1>Kelas yang Anda Ikuti</h1>
                        <form action="studentDashboard.php?page=kelas" method="POST" class="enroll-form">
                            <?= csrf_field() ?>
                            <input class="enroll-key-input" type="text" name="enrollment_key"
                                placeholder="Masukkan Enrollment Key..." required>
                            <button class="btnEnroll" type="submit" name="submit_enroll">Daftar</button>
                        </form>
                    </header>

                    <div class="kelas-container">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <div class="kelas-card">
                                    <div class="kelas-card-banner"
                                        style="background-image: url('../picture/<?= htmlspecialchars($row['foto_kelas']) ?>');">
                                    </div>
                                    <div class="kelas-card-body">
                                        <span class="kategori-chip"><?= htmlspecialchars($row['kategori']) ?></span>
                                        <h3><?= htmlspecialchars($row['title_kelas']) ?></h3>
                                    </div>
                                    <div class="kelas-card-footer">
                                        <a href="detailMateri.php?id=<?= $row['kelas_id'] ?>" class="btn-lanjutkan">
                                            Lanjutkan Belajar
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <img src="../picture/empty-box.svg" alt="Kotak Kosong" class="empty-state-img">
                                <h2>Anda Belum Mengambil Kelas Apapun</h2>
                                <p>Gunakan enrollment key yang Anda dapatkan untuk mendaftar ke kelas baru.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="pagination">
                        <?php if ($totalPages > 1): ?>
                            <?php if ($page > 1): ?><a href="studentDashboard.php?page=kelas&p=<?= $page - 1 ?>">«
                                    Prev</a><?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?><a href="studentDashboard.php?page=kelas&p=<?= $i ?>"
                                    class="<?= ($i == $page) ? 'active-page' : '' ?>"><?= $i ?></a><?php endfor; ?>
                            <?php if ($page < $totalPages): ?><a href="studentDashboard.php?page=kelas&p=<?= $page + 1 ?>">Next
                                    »</a><?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <header class="content-header">
                        <h1>Halaman Tidak Ditemukan</h1>
                    </header>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php echo $sweetAlertScript; ?>
</body>

</html>