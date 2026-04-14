<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? '';

if ($id_user == '') {
    echo json_encode([
        "success" => false,
        "message" => "id_user wajib dikirim"
    ]);
    exit();
}

$query = "SELECT nama, password, level, nis, id_pembina FROM users WHERE id_user='$id_user'";
$result = $conn->query($query);

if ($row = $result->fetch_assoc()) {

    echo json_encode([
        "success" => true,
        "nama" => $row['nama'],
        "password" => $row['password'],
        "level" => $row['level'],
        "nis" => $row['nis'],
        "id_pembina" => $row['id_pembina']
    ]);

} else {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak ditemukan"
    ]);
}

$conn->close();
?>
