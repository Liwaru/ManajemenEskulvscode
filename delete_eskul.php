<?php
header("Content-Type: application/json");
include 'koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? ($_POST['id'] ?? '');

if ($id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "id_eskul wajib dikirim"
    ]);
    exit();
}

$stmtEskul = $conn->prepare("SELECT id_eskul, nama_eskul FROM eskul WHERE id_eskul = ?");
$stmtEskul->bind_param("i", $id_eskul);
$stmtEskul->execute();
$resultEskul = $stmtEskul->get_result();
$eskul = $resultEskul->fetch_assoc();

if (!$eskul) {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul tidak ditemukan"
    ]);
    $stmtEskul->close();
    exit();
}
$stmtEskul->close();

$stmtPendaftaran = $conn->prepare("SELECT COUNT(*) AS total FROM pendaftaran WHERE id_eskul = ?");
$stmtPendaftaran->bind_param("i", $id_eskul);
$stmtPendaftaran->execute();
$totalPendaftaran = (int) ($stmtPendaftaran->get_result()->fetch_assoc()['total'] ?? 0);
$stmtPendaftaran->close();

$stmtAbsensi = $conn->prepare("SELECT COUNT(*) AS total FROM absensi WHERE id_eskul = ?");
$stmtAbsensi->bind_param("i", $id_eskul);
$stmtAbsensi->execute();
$totalAbsensi = (int) ($stmtAbsensi->get_result()->fetch_assoc()['total'] ?? 0);
$stmtAbsensi->close();

if ($totalPendaftaran > 0 || $totalAbsensi > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Eskul tidak bisa dihapus karena sudah memiliki data pendaftaran atau absensi",
        "data" => [
            "id_eskul" => (int) $id_eskul,
            "nama_eskul" => $eskul['nama_eskul'],
            "total_pendaftaran" => $totalPendaftaran,
            "total_absensi" => $totalAbsensi
        ]
    ]);
    exit();
}

$stmtDelete = $conn->prepare("DELETE FROM eskul WHERE id_eskul = ?");
$stmtDelete->bind_param("i", $id_eskul);

if ($stmtDelete->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Eskul berhasil dihapus",
        "data" => [
            "id_eskul" => (int) $id_eskul,
            "nama_eskul" => $eskul['nama_eskul']
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menghapus eskul"
    ]);
}

$stmtDelete->close();
$conn->close();
?>
