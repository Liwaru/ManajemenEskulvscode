<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = isset($_POST['id_user']) ? $_POST['id_user'] : '';

if ($id_user == '') {
    echo json_encode([]);
    exit();
}
$query = "
SELECT eskul.id_eskul, eskul.nama_eskul, eskul.nama_pembina, eskul.gambar
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
?>
