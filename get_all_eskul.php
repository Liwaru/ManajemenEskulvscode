<?php
header('Content-Type: application/json');
error_reporting(0);

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'manajemeneskul';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    echo json_encode(['error' => 'Koneksi gagal']);
    exit;
}

// Kolom jam di database bernama `jam mulai` (memakai spasi).
$query = "SELECT id_eskul, id_pembina, nama_eskul, nama_pembina, `jam mulai` AS jam_mulai, jam_selesai, gambar FROM eskul";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
mysqli_close($conn);
?>
