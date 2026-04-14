<?php
header("Content-Type: application/json");
error_reporting(0);
include 'Koneksi.php';

$id_user = $_POST['id_user'] ?? '';
$password = $_POST['password'] ?? '';

if ($id_user === '' || $password === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

// Profil siswa hanya mengizinkan perubahan password tanpa hash.
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE id_user = ?");
$stmt->bind_param("si", $password, $id_user);

if ($stmt->execute() && $stmt->affected_rows >= 0) {
    echo json_encode([
        "success" => true,
        "message" => "Password berhasil diupdate",
        "id_user" => (int) $id_user
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal update password"
    ]);
}

$stmt->close();
$conn->close();
?>
