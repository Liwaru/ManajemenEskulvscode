<?php
header('Content-Type: application/json');
error_reporting(0);
include 'koneksi.php'; // sesuaikan

$id_user = $_POST['id_user'] ?? '';

// Jika id_user adalah id dari tabel users, maka cari data pembina berdasarkan level=2
$query = "SELECT * FROM users WHERE level = 2";
$result = mysqli_query($conn, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
?>