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
    SELECT
        absensi.id_absensi,
        absensi.id_siswa,
        absensi.id_eskul,
        absensi.tanggal_absensi,
        users.nama AS nama_siswa,
        users.nama,
        users.nis,
        eskul.nama_eskul
    FROM absensi
    JOIN users ON users.id_user = absensi.id_siswa
    JOIN eskul ON eskul.id_eskul = absensi.id_eskul
    WHERE absensi.id_eskul = ?
    AND users.level = 1
    ORDER BY absensi.tanggal_absensi DESC, users.nama ASC
");
$stmt->bind_param("i", $id_eskul);
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
