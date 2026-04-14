<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? '';

if ($id_user === '') {
    echo json_encode([
        "success" => false,
        "message" => "id_user wajib dikirim"
    ]);
    exit();
}

$stmt = $conn->prepare("SELECT id_user, id_eskul, nis, nama, password, level FROM users WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "id_user" => (int) $row['id_user'],
        "id_eskul" => (int) $row['id_eskul'],
        "nis" => $row['nis'],
        "nama" => $row['nama'],
        "password" => $row['password'],
        "level" => (int) $row['level']
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak ditemukan"
    ]);
}

$stmt->close();
$conn->close();
?>
