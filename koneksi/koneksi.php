<?php

// KONEKSI DATABASE 1
// $db = mysqli_connect("localhost", "root", "", "");
// if(!$db){
//     die("error koneksi :" . mysqli_connect_errno());
// }

// KONEKSI DATABASE 2 (DANIEL)

// $conn = mysqli_connect("localhost", "root", "", "dadproject", 3307);
// if (!$conn) {
//     die("Koneksi gagal: " . mysqli_connect_error());
// }

$conn = mysqli_connect("localhost", "root", "", "dadproject");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}
