<?php
header('Content-Type: application/json');
error_reporting(0);
include 'koneksi.php';

$query = "SELECT id_eskul, id_pembina, nama_eskul, nama_pembina, jam_mulai, jam_selesai, gambar FROM eskul";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal mengambil data eskul'
    ]);
    $conn->close();
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
