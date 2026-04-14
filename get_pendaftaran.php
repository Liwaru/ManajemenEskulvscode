<?php
include 'Koneksi.php';

$id_eskul = $_POST['id_eskul'];

$sql = "SELECT user.username, daftar_eskul.status, daftar_eskul.id_daftar
        FROM daftar_eskul
        JOIN user ON user.id_user = daftar_eskul.id_user
        WHERE daftar_eskul.id_eskul='$id_eskul' AND status='pending'";

$result = mysqli_query($conn, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>