<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "manajemeneskul";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Koneksi gagal: " . $koneksi->connect_error
    ]);
    exit();
}

// Beberapa file masih memakai nama variabel $conn.
// Samakan keduanya agar endpoint lama tetap berjalan.
$conn = $koneksi;
$koneksi->set_charset("utf8mb4");
?>
