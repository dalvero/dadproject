<?php
require '../koneksi/koneksi.php';

$keyword = $_GET["keywordKelas"] ?? '';
$likeKeyword = "%$keyword";

$stmt = $conn->prepare("SELECT kelas.kelas_id, kelas.title_kelas, kelas.foto_kelas, kelas.desk_kelas,
                 mentors.nama_depan, mentors.nama_belakang, kategori_kelas.jenis,
                 enrollment_key.enrollment_key
          FROM kelas
          JOIN mentors ON kelas.mentor_id = mentors.mentor_id
          JOIN kategori_kelas ON kelas.kategori_id = kategori_kelas.kategori_kelas_id
          LEFT JOIN enrollment_key ON kelas.kelas_id = enrollment_key.kelas_id
          WHERE
              kelas.title_kelas LIKE ?
              OR mentors.nama_depan LIKE ?
              OR mentors.nama_belakang LIKE ?
              OR CONCAT(mentors.nama_depan, ' ', mentors.nama_belakang) LIKE ?
              OR kategori_kelas.jenis LIKE ?
          ORDER BY kelas.kelas_id DESC");
$stmt->bind_param("sssss", $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword, $likeKeyword);
$stmt->execute();
$result = $stmt->get_result();
$dataKelas = [];
while ($row = $result->fetch_assoc()) {
    $dataKelas[] = $row;
}
$stmt->close();
?>
<table class="custom-table" border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>NO.</th>
            <th>Aksi</th>
            <th>Nama Kelas</th>
            <th>Foto</th>
            <th>Deskripsi Kelas</th>
            <th>Nama Mentor</th>
            <th>Kategori</th>
            <th>Enrollment</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($dataKelas)): ?>
            <tr>
                <td colspan="8" style="text-align: center;">Data tidak ditemukan untuk keyword
                    "<?= htmlspecialchars($keyword) ?>".</td>
            </tr>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php foreach ($dataKelas as $dt): ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td class="actions">
                        <button class="hapus"><a href="#" onclick="confirmDelete(<?= $dt['kelas_id'] ?>)">Hapus</a></button>
                        <button class="edit"><a href="editKelas.php?id=<?= $dt['kelas_id']; ?>">Edit</a></button>
                    </td>
                    <td><?= htmlspecialchars($dt["title_kelas"]) ?></td>
                    <td><img src="../picture/<?= htmlspecialchars($dt["foto_kelas"]) ?>" alt="Foto Kelas" width="100"></td>
                    <td><?= htmlspecialchars($dt["desk_kelas"]) ?></td>
                    <td><?= htmlspecialchars($dt["nama_depan"] . " " . $dt["nama_belakang"]) ?></td>
                    <td><?= htmlspecialchars($dt["jenis"]) ?></td>
                    <td><?= htmlspecialchars($dt['enrollment_key']) ?></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>