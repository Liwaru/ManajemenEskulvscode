<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? '';

if ($id_user === '') {
    echo json_encode([]);
    exit();
}

$stmt = $conn->prepare("
    SELECT
        absensi.id_absensi,
        absensi.id_siswa,
        absensi.id_eskul,
        users.nama,
        eskul.nama_eskul,
        eskul.nama_pembina,
        eskul.gambar,
        absensi.tanggal_absensi
    FROM absensi
    JOIN users ON users.id_user = absensi.id_siswa
    JOIN eskul ON eskul.id_eskul = absensi.id_eskul
    WHERE absensi.id_siswa = ?
    ORDER BY absensi.tanggal_absensi DESC
");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
