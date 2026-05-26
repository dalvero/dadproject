<?php
require '../koneksi/koneksi.php';

$keyword = $_GET["keywordUser"] ?? '';
$likeKeyword = "%$keyword";

$stmt = $conn->prepare("SELECT * FROM user
          WHERE
              user_id LIKE ?
              OR nama_depan LIKE ?
              OR nama_belakang LIKE ?
              OR CONCAT(nama_depan, ' ', nama_belakang) LIKE ?
              OR nama_lengkap LIKE ?
              OR username LIKE ?
              OR email LIKE ?
              OR `role` LIKE ?
          ORDER BY user_id DESC");
$stmt->bind_param("ssssssss", $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword);
$stmt->execute();
$result = $stmt->get_result();
$dataUser = [];
while ($row = $result->fetch_assoc()) {
    $dataUser[] = $row;
}
$stmt->close();
?>

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
                <td colspan="8" style="text-align: center;">User tidak ditemukan untuk keyword "<?= htmlspecialchars($keyword) ?>".</td>
            </tr>
        <?php else: ?>
            <?php foreach ($dataUser as $user): ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td class="actions">
                        <button class="hapus" onclick="confirmDelete(<?= $user['user_id'] ?>, '<?= htmlspecialchars(addslashes($user['username'])) ?>')">Hapus</button>
                        <button class="edit"><a href="editUser.php?user_id=<?= $user['user_id'] ?>">Edit</a></button>
                    </td>
                    <td><?= htmlspecialchars($user['nama_depan']) ?></td>
                    <td><?= htmlspecialchars($user['nama_belakang']) ?></td>
                    <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>