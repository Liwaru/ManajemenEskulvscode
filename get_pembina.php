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

// Sesuaikan dengan struktur tabel users.
$query = "SELECT id_user, id_pembina, nis, nama, level FROM users WHERE level = 2";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
mysqli_close($conn);
?>
