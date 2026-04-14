<?php
header("Content-Type: application/json");
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? '';

if ($id_user === '') {
    echo json_encode([]);
    exit();
}

$query = "
SELECT eskul.id_eskul, eskul.nama_eskul
FROM pendaftaran
JOIN eskul ON eskul.id_eskul = pendaftaran.id_eskul
WHERE pendaftaran.id_user = '$id_user'
AND pendaftaran.status = 'diterima'
";

$result = $conn->query($query);

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
