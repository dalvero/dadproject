<?php
require_once '../controller/controlUser.php';
require_once '../koneksi/csrf.php';

$user_id = (int) $_GET['user_id'];

$stmt = $conn->prepare("
         SELECT user.*, mentors.expertise, students.status
         FROM user
         LEFT JOIN mentors ON user.user_id = mentors.user_id
         LEFT JOIN students ON user.user_id = students.user_id
         WHERE user.user_id = ?
     ");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$dataUser = $result->fetch_assoc();
$stmt->close();

$alert = "";
if (isset($_POST['editUser'])) {
    verify_csrf_token();

    if (update_user($_POST) > 0) {
        $alert = "
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: 'data telah berhasil diupdate.',
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
    <link rel="shortcut icon" href="../picture/logo.png" type="image/x-icon">
    <title>Form Edit Pengguna - DadProject</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    hr {
        border-color: #9333EA;
    }
</style>

<body class="bg-[#0F172A] text-white">
    <?= $alert ?>
    <div class="mx-10 mt-5">
        <h1 class="text-3xl font-semibold">Form Edit Pengguna</h1>
        <br>
        <hr>
    </div>

    <div class="flex p-10 gap-10">
        <div class="flex justify-center items-center w-[30%] h-180 bg-[#1C2435] border-2 border-[#9333EA] rounded-xl">
            <img class="h-30" src="../picture/logo1.png" alt="Logo DadProject">
        </div>

        <form action="" method="POST"
            class="flex flex-col p-10 w-[70%] h-180 bg-[#1C2435] border-2 border-[#9333EA] rounded-xl"
            enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="flex flex-col text-xl">
                <input type="hidden" name="userId" value="<?= $dataUser['user_id']; ?>">

                <ul>
                    <!-- Nama Depan -->
                    <li class="flex flex-col text-xl mb-4">
                        <label for="namaDepan">Nama Depan:</label>
                        <input
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="text" id="namaDepan" name="namaDepan"
                            value="<?= htmlspecialchars($dataUser['nama_depan']); ?>" required>
                    </li>

                    <!-- Nama Belakang -->
                    <li class="flex flex-col text-xl mb-4">
                        <label for="namaBelakang">Nama Belakang:</label>
                        <input
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="text" id="namaBelakang" name="namaBelakang"
                            value="<?= htmlspecialchars($dataUser['nama_belakang']); ?>" required>
                    </li>

                    <!-- Username -->
                    <li class="flex flex-col text-xl mb-4">
                        <label for="username">Username:</label>
                        <input
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="text" id="username" name="username"
                            value="<?= htmlspecialchars($dataUser['username']); ?>" required>
                    </li>

                    <!-- Email -->
                    <li class="flex flex-col text-xl mb-4">
                        <label for="email">Email:</label>
                        <input
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md"
                            type="email" id="email" name="email" value="<?= htmlspecialchars($dataUser['email']); ?>"
                            required>
                    </li>

                    <!-- Role -->
                    <li class="flex flex-col text-xl mb-4">
                        <label for="role">Role:</label>
                        <select id="role" name="role" required onchange="updateFormByRole()"
                            class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md">
                            <option value="admin" <?= ($dataUser['role'] == 'admin' ? 'selected' : '') ?>>Admin</option>
                            <option value="mentor" <?= ($dataUser['role'] == 'mentor' ? 'selected' : '') ?>>Mentor</option>
                            <option value="student" <?= ($dataUser['role'] == 'student' ? 'selected' : '') ?>>Student
                            </option>
                        </select>
                    </li>

                    <!-- Mentor Spesifik -->
                    <div class="mentor-fields" id="mentorFields">
                        <li class="flex flex-col text-xl mb-4">
                            <label for="expertise">Expertise:</label>
                            <select id="expertise" name="expertise"
                                class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md">
                                <option value="Web Development" <?= ($dataUser['expertise'] == 'Web Development' ? 'selected' : '') ?>>Web Development</option>
                                <option value="Backend Developer" <?= ($dataUser['expertise'] == 'Backend Developer' ? 'selected' : '') ?>>Backend Developer</option>
                                <option value="UI/UX Design" <?= ($dataUser['expertise'] == 'UI/UX Design' ? 'selected' : '') ?>>UI/UX Design</option>
                                <option value="Data Analyst" <?= ($dataUser['expertise'] == 'Data Analyst' ? 'selected' : '') ?>>Data Analyst</option>
                            </select>
                        </li>
                    </div>

                    <!-- Student Spesifik -->
                    <div class="student-fields" id="studentFields">
                        <li class="flex flex-col text-xl mb-4">
                            <label for="status">Status:</label>
                            <select id="status" name="status"
                                class="bg-[#323A4C] p-2 text-white border border-[#323A4C] focus:border-[#9333EA] focus:ring-[#9333EA] focus:outline-none rounded-md">
                                <option value="Mahasiswa" <?= ($dataUser['status'] == 'Mahasiswa' ? 'selected' : '') ?>>
                                    Mahasiswa</option>
                                <option value="Siswa" <?= ($dataUser['status'] == 'Siswa' ? 'selected' : '') ?>>Siswa
                                </option>
                            </select>
                        </li>
                    </div>

                    <!-- Admin Spesifik -->
                    <div class="admin-fields" id="adminFields"></div>

                </ul>
                <div class="flex justify-between mt-5">
                    <a class="bg-[#C084FC] p-2 rounded-md hover:bg-[#9333EA] hover:scale-102 duration-200"
                        href="manageUser.php">Kembali</a>
                    <button class="bg-[#C084FC] p-2 rounded-md hover:bg-[#9333EA] hover:scale-102 duration-200"
                        type="submit" name="editUser">Edit Pengguna</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateFormByRole() {
            var role = document.getElementById("role").value;
            document.getElementById("mentorFields").style.display = "none";
            document.getElementById("studentFields").style.display = "none";
            document.getElementById("adminFields").style.display = "none";

            if (role === "admin") {
                document.getElementById("adminFields").style.display = "block";
            } else if (role === "mentor") {
                document.getElementById("mentorFields").style.display = "block";
            } else if (role === "student") {
                document.getElementById("studentFields").style.display = "block";
            }
        }
        window.onload = updateFormByRole;
    </script>
</body>

</html>