<?php
header("Content-Type: application/json");
error_reporting(0);
include 'Koneksi.php';

$id_pendaftaran = $_POST['id_pendaftaran'] ?? ($_POST['id_daftar'] ?? ($_POST['id'] ?? ''));
$status = $_POST['status'] ?? '';
$alasan = trim($_POST['alasan'] ?? ($_POST['reason'] ?? ''));

if ($id_pendaftaran === '' || !in_array($status, ['diterima', 'ditolak'], true)) {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak valid"
    ]);
    exit();
}

if ($status === 'ditolak' && $alasan === '') {
    echo json_encode([
        "success" => false,
        "message" => "Alasan penolakan wajib diisi"
    ]);
    exit();
}

// Cek dulu data pendaftaran agar respons lebih jelas untuk Android.
$stmtCek = $conn->prepare("SELECT id_pendaftaran, status, alasan FROM pendaftaran WHERE id_pendaftaran = ?");
$stmtCek->bind_param("i", $id_pendaftaran);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if (!$resultCek || $resultCek->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Data pendaftaran tidak ditemukan"
    ]);
    $stmtCek->close();
    $conn->close();
    exit();
}

$dataPendaftaran = $resultCek->fetch_assoc();
$stmtCek->close();

if ($dataPendaftaran['status'] === $status) {
    echo json_encode([
        "success" => true,
        "message" => "Status pendaftaran sudah $status",
        "alasan" => $dataPendaftaran['alasan']
    ]);
    $conn->close();
    exit();
}

$alasanSimpan = $status === 'ditolak' ? $alasan : null;
$stmt = $conn->prepare("UPDATE pendaftaran SET status = ?, alasan = ? WHERE id_pendaftaran = ?");
$stmt->bind_param("ssi", $status, $alasanSimpan, $id_pendaftaran);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Status berhasil diupdate",
        "id_pendaftaran" => (int) $id_pendaftaran,
        "status" => $status,
        "alasan" => $alasanSimpan
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
