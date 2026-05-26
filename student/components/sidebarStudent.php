<?php
$user_name = $_SESSION['nama_lengkap'] ?? 'Nama Siswa';
$user_role = $_SESSION['role'] ?? 'student';
$email = $_SESSION['email'] ?? 'email@siswa.com';

$student_id = 0;
$status = 'Tidak Diketahui'; 

if (isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    
    $stmt_student = $conn->prepare("SELECT student_id, status FROM students WHERE user_id = ?");
    $stmt_student->bind_param("i", $user_id);
    $stmt_student->execute();
    $student_result = $stmt_student->get_result();
    
    if ($student_result->num_rows > 0) {
        $student_data = $student_result->fetch_assoc();
        $student_id = $student_data['student_id'];
        $status = $student_data['status'];
    }
    $stmt_student->close();
}

$stmt_count = $conn->prepare("SELECT COUNT(*) as total_kelas FROM kelas_student WHERE student_id = ?");
$stmt_count->bind_param("i", $student_id);
$stmt_count->execute();
$total_kelas = $stmt_count->get_result()->fetch_assoc()['total_kelas'] ?? 0;
$stmt_count->close();
?>

<div class="sidebar-content">
    <div class="profile-header">
         <div class="profile-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
            </svg>
        </div>
        <div class="profile-info">
            <h3 class="profile-name"><?= htmlspecialchars($user_name) ?></h3>
            <p class="profile-role"><?= htmlspecialchars(ucfirst($user_role)) ?></p>
        </div>
    </div>

    <div class="separator"></div>

    <div class="profile-details">
        <h4 class="details-title">Detail Akun</h4>
        <div class="detail-item">
            <i class="fas fa-envelope"></i>
            <span><?= htmlspecialchars($email) ?></span>
        </div>
        <div class="detail-item">
            <i class="fas fa-user-graduate"></i> 
            <span><?= htmlspecialchars($status) ?></span>
        </div>
        <div class="detail-item">
            <i class="fas fa-book-open"></i>
            <span>Mengikuti <strong><?= $total_kelas ?></strong> Kelas</span>
        </div>
    </div>
    
    <div class="separator"></div>

    <div class="sidebar-actions">
        <a href="pengaturanStudent.php" class="btn-sidebar">
            <i class="fas fa-cog"></i>
            <span>Pengaturan Akun</span>
        </a>
        <a href="../logout.php" class="btn-sidebar btn-logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <p>© <?= date('Y') ?> Dad Project</p>
    </div>
</div>