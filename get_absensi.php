<?php
header("Content-Type: application/json");
include 'koneksi.php';

$id_siswa = $_POST['id_siswa'] ?? ($_POST['id_user'] ?? '');

if ($id_siswa === '') {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT absensi.id_absensi, absensi.id_siswa, absensi.id_eskul, absensi.tanggal_absensi, eskul.nama_eskul
    FROM absensi
    JOIN eskul ON eskul.id_eskul = absensi.id_eskul
    WHERE absensi.id_siswa = ?
    ORDER BY absensi.tanggal_absensi DESC
");
$stmt->bind_param("i", $id_siswa);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
