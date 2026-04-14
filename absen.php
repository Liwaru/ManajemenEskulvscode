<?php
header("Content-Type: application/json");
include 'koneksi.php';

$id_siswa = $_POST['id_siswa'] ?? ($_POST['id_user'] ?? '');
$id_eskul = $_POST['id_eskul'] ?? '';
$tanggal = $_POST['tanggal_absensi'] ?? date("Y-m-d H:i:s");

if ($id_siswa === '' || $id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

$stmtCek = $conn->prepare("
    SELECT id_absensi
    FROM absensi
    WHERE id_siswa = ? AND id_eskul = ? AND DATE(tanggal_absensi) = CURDATE()
");
$stmtCek->bind_param("ii", $id_siswa, $id_eskul);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Kamu sudah absen hari ini"
    ]);
    $stmtCek->close();
    exit();
}
$stmtCek->close();

$stmt = $conn->prepare("
    INSERT INTO absensi (id_siswa, id_eskul, tanggal_absensi)
    VALUES (?, ?, ?)
");
$stmt->bind_param("iis", $id_siswa, $id_eskul, $tanggal);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Absensi berhasil",
        "id_absensi" => $conn->insert_id
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal absen"
    ]);
}

$stmt->close();
$conn->close();
?>
