<?php
require 'koneksi.php';

// KONEKSI KE DATABASE
$conn = mysqli_connect("localhost", "root", "", "test", 3307);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$query = "SELECT user_id, password FROM user";
$result = mysqli_query($conn, $query);

while ($user = mysqli_fetch_assoc($result)) {
    $plainPassword = $user['password']; // PASSWORD POLOS
    $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT); // PASSWORD ENKRIPSI

    // DEBUG
    echo $plainPassword . "==//==" . $hashedPassword . "<br>";

    // MENGUBAH PASSWORD DI DATABASE DENGAN BENTUK YANG SUDAH DIENKRIPSI
    $updateQuery = "UPDATE user SET password = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'si', $hashedPassword, $user['user_id']);
    mysqli_stmt_execute($stmt);
    echo "Password untuk ID {$user['user_id']} telah dienkripsi.<br>";
}
