<?php
header("Content-Type: application/json");
include 'Koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? '';

if ($id_eskul === '') {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT users.nama
    FROM pendaftaran
    JOIN users ON users.id_user = pendaftaran.id_user
    WHERE pendaftaran.id_eskul = ?
    AND pendaftaran.status = 'diterima'
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
