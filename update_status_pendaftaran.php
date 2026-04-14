<?php
header("Content-Type: application/json");
include 'Koneksi.php';

$id_pendaftaran = $_POST['id_pendaftaran'] ?? ($_POST['id_daftar'] ?? '');
$status = $_POST['status'] ?? '';

if ($id_pendaftaran === '' || $status === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

if (!in_array($status, ['proses', 'diterima', 'ditolak'], true)) {
    echo json_encode([
        "success" => false,
        "message" => "Status tidak valid"
    ]);
    exit();
}

$stmt = $conn->prepare("
    UPDATE pendaftaran
    SET status = ?
    WHERE id_pendaftaran = ?
");
$stmt->bind_param("si", $status, $id_pendaftaran);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Status berhasil diupdate"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal update"
    ]);
}

$stmt->close();
$conn->close();
?>
