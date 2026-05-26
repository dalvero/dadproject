<?php
require_once '../controller/controlUser.php';
require_once '../koneksi/csrf.php';

$dataUser = query('SELECT * FROM user ORDER BY user_id DESC');

$nama_lengkap = $_SESSION['nama_lengkap'] ?? 'Admin';

$alert = "";
if (isset($_POST['tambahUser'])) {
    verify_csrf_token();

    if (create_user($_POST) > 0) {
        $alert = "
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'data telah berhasil ditambahkan.',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = 'manageUser.php'; 
    });
</script>";
    } else {
        $alert = "
<script>
    Swal.fire({
        title: 'Gagal!',
        text: 'gagal menambahkan data.',
        icon: 'error',
        confirmButtonText: 'Coba Lagi'
    });
</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../picture/logo.png" type="image/x-icon">
    <title>Kelola Pengguna - DadProject</title>
    <link rel="stylesheet" href="../css/admin/adminDashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?= $alert ?>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('components/sidebarAdmin.php'); ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Manajemen Pengguna</h1>
                <hr><br>
                <p>Selamat Datang, <?= htmlspecialchars($nama_lengkap); ?></p>
            </header>

            <div class="header-controls">
                <button id="openModalBtn" class="boxbtn">Tambah Pengguna</button>
                <input type="text" name="keywordUser" id="keywordUser" class="inputSearch" autocomplete="off"
                    placeholder="Cari User..">
            </div>

            <!-- Modal untuk Tambah Pengguna -->
            <div id="addUserModal" class="modal">
                <div class="modal-content">
                    <span class="close">×</span>
                    <h2>Form Tambah Pengguna</h2>
                    <form method="POST" class="form-container">
                        <?= csrf_field() ?>
                        <label for="firstName">Nama Depan:</label>
                        <input type="text" id="firstName" class="input" name="namaDepan" required>

                        <label for="lastName">Nama Belakang:</label>
                        <input type="text" id="lastName" class="input" name="namaBelakang" required>

                        <label for="username">Username:</label>
                        <input type="text" id="username" class="input" name="username" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" class="input" name="email" required>

                        <label for="password">Password:</label>
                        <input type="password" id="password" class="input" name="kataSandi" required>

                        <label for="role">Role:</label>
                        <select id="role" class="input" name="role" required onchange="updateFormByRole()">
                            <option value="student" selected>Student</option>
                            <option value="mentor">Mentor</option>
                            <option value="admin">Admin</option>
                        </select>

                        <div class="admin-fields" id="adminFields" style="display:none;"></div>

                        <div class="mentor-fields" id="mentorFields" style="display:none;">
                            <label for="expertise">Expertise:</label>
                            <select id="expertise" name="expertise">
                                <option value="Web Development">Web Development</option>
                                <option value="Backend Developer">Backend Developer</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                                <option value="Data Analyst">Data Analyst</option>
                            </select>
                        </div>

                        <div class="student-fields" id="studentFields">
                            <label for="status">Status:</label>
                            <select id="status" name="status">
                                <option value="Mahasiswa">Mahasiswa</option>
                                <option value="Siswa">Siswa</option>
                            </select>
                        </div>

                        <button type="submit" name="tambahUser">Tambah Pengguna</button>
                    </form>
                </div>
            </div>

            <div id="searchResult">
                <table class="custom-table" border="1" cellpadding="10" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Aksi</th>
                            <th>Nama Depan</th>
                            <th>Nama Belakang</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataUser)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">Belum ada pengguna yang terdaftar.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($dataUser as $user): ?>
                                <tr>
                                    <td><?= $user['user_id']; ?></td>
                                    <td class="actions">
                                        <button class="hapus" name="deleteUser"
                                            onclick="confirmDelete(<?= $user['user_id']; ?>, '<?= htmlspecialchars(addslashes($user['username'])); ?>')">Hapus</button>
                                        <button class="edit"><a
                                                href="editUser.php?user_id=<?= $user['user_id']; ?>">Edit</a></button>
                                    </td>
                                    <td><?= htmlspecialchars($user['nama_depan']); ?></td>
                                    <td><?= htmlspecialchars($user['nama_belakang']); ?></td>
                                    <td><?= htmlspecialchars($user['nama_lengkap']); ?></td>
                                    <td><?= htmlspecialchars($user['username']); ?></td>
                                    <td><?= htmlspecialchars($user['email']); ?></td>
                                    <td><?= htmlspecialchars($user['role']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const keywordInput = document.getElementById('keywordUser');
        const searchResultContainer = document.getElementById('searchResult');

        keywordInput.addEventListener('keyup', function () {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    searchResultContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'searchUser.php?keywordUser=' + keywordInput.value, true);
            xhr.send();
        });

        function confirmDelete(id, username) {
            Swal.fire({
                title: "Anda Yakin?",
                text: `Anda akan menghapus pengguna "${username}" secara permanen!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'deleteUser.php?user_id=' + id;
                }
            });
        }

        const modal = document.getElementById("addUserModal");
        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementsByClassName("close")[0];

        openModalBtn.onclick = () => modal.style.display = "block";
        closeModalBtn.onclick = () => modal.style.display = "none";
        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function updateFormByRole() {
            const role = document.getElementById("role").value;
            const mentorFields = document.getElementById("mentorFields");
            const studentFields = document.getElementById("studentFields");

            mentorFields.style.display = (role === "mentor") ? "block" : "none";
            studentFields.style.display = (role === "student") ? "block" : "none";
        }

        document.addEventListener('DOMContentLoaded', updateFormByRole);
    </script>
</body>

</html>