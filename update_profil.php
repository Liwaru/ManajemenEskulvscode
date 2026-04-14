<?php
header("Content-Type: application/json");
include 'Koneksi.php';

$id_user = $_POST['id_user'] ?? '';
$nama = $_POST['nama'] ?? '';
$password = $_POST['password'] ?? '';

if ($id_user === '' || $password === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

if ($nama === '') {
    $stmtCurrent = $conn->prepare("SELECT nama FROM users WHERE id_user = ?");
    $stmtCurrent->bind_param("i", $id_user);
    $stmtCurrent->execute();
    $resultCurrent = $stmtCurrent->get_result();
    $current = $resultCurrent->fetch_assoc();
    $nama = $current['nama'] ?? '';
    $stmtCurrent->close();
}

$stmt = $conn->prepare("
    UPDATE users
    SET nama = ?, password = ?
    WHERE id_user = ?
");
$stmt->bind_param("ssi", $nama, $password, $id_user);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Profil berhasil diupdate"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal update profil"
    ]);
}

$stmt->close();
$conn->close();
?>
