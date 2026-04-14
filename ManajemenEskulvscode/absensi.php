<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? '';
$id_eskul = $_POST['id_eskul'] ?? '';
$tanggal = date("Y-m-d H:i:s");

if ($id_user === '' || $id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

$stmtValidasi = $conn->prepare("
    SELECT id_pendaftaran
    FROM pendaftaran
    WHERE id_user = ? AND id_eskul = ? AND status = 'diterima'
");
$stmtValidasi->bind_param("ii", $id_user, $id_eskul);
$stmtValidasi->execute();
$resultValidasi = $stmtValidasi->get_result();

if (!$resultValidasi || $resultValidasi->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Kamu belum diterima di eskul ini"
    ]);
    $stmtValidasi->close();
    $conn->close();
    exit();
}
$stmtValidasi->close();

$stmtCek = $conn->prepare("
    SELECT id_absensi
    FROM absensi
    WHERE id_siswa = ? AND id_eskul = ? AND DATE(tanggal_absensi) = CURDATE()
");
$stmtCek->bind_param("ii", $id_user, $id_eskul);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek && $resultCek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Kamu sudah absen hari ini"
    ]);
    $stmtCek->close();
    $conn->close();
    exit();
}
$stmtCek->close();

$stmtInsert = $conn->prepare("
    INSERT INTO absensi (id_siswa, id_eskul, tanggal_absensi)
    VALUES (?, ?, ?)
");
$stmtInsert->bind_param("iis", $id_user, $id_eskul, $tanggal);

if ($stmtInsert->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Absensi berhasil",
        "id_user" => (int) $id_user,
        "id_eskul" => (int) $id_eskul,
        "tanggal_absensi" => $tanggal
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal absen"
    ]);
}

$stmtInsert->close();
$conn->close();
?>
