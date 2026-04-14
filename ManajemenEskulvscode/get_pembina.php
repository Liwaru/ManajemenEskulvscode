<?php
header('Content-Type: application/json');
error_reporting(0);
include 'koneksi.php';

$query = "SELECT id_user, id_eskul, nis, nama, level FROM users WHERE level = 2 ORDER BY nama ASC";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
echo json_encode($data);
$conn->close();
?>
