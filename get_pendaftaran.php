<?php
header("Content-Type: application/json");
include 'koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? '';
$id_pembina = $_POST['id_pembina'] ?? '';

if ($id_eskul === '' && $id_pembina !== '') {
    $stmtEskul = $conn->prepare("SELECT id_eskul FROM eskul WHERE id_pembina = ? LIMIT 1");
    $stmtEskul->bind_param("i", $id_pembina);
    $stmtEskul->execute();
    $resultEskul = $stmtEskul->get_result();
    $eskul = $resultEskul->fetch_assoc();
    $id_eskul = $eskul['id_eskul'] ?? '';
    $stmtEskul->close();
}

if ($id_eskul === '') {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT users.id_user, users.nis, users.nama, pendaftaran.status, pendaftaran.id_pendaftaran, pendaftaran.tanggal_daftar
    FROM pendaftaran
    JOIN users ON users.id_user = pendaftaran.id_user
    WHERE pendaftaran.id_eskul = ? AND pendaftaran.status = 'proses' AND users.level = 1
    ORDER BY pendaftaran.tanggal_daftar DESC
");
$stmt->bind_param("i", $id_eskul);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
$stmt->close();
$conn->close();
?>
