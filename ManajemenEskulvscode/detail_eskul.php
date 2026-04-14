<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? ($_POST['id'] ?? '');

if ($id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "id_eskul wajib dikirim"
    ]);
    exit();
}

$stmt = $conn->prepare("
    SELECT
        eskul.id_eskul,
        eskul.nama_eskul,
        eskul.nama_pembina,
        eskul.deskripsi,
        COALESCE(jadwal_info.hari, '') AS hari,
        COALESCE(eskul.jam_mulai, jadwal_info.jam_mulai) AS jam_mulai,
        COALESCE(eskul.jam_selesai, jadwal_info.jam_selesai) AS jam_selesai,
        eskul.gambar
    FROM eskul
    LEFT JOIN (
        SELECT
            id_eskul,
            GROUP_CONCAT(hari ORDER BY id_jadwal SEPARATOR ', ') AS hari,
            MIN(jam_mulai) AS jam_mulai,
            MAX(jam_selesai) AS jam_selesai
        FROM jadwal
        GROUP BY id_eskul
    ) AS jadwal_info ON jadwal_info.id_eskul = eskul.id_eskul
    WHERE id_eskul = ?
");
$stmt->bind_param("i", $id_eskul);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "data" => $row
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul tidak ditemukan"
    ]);
}

$stmt->close();
$conn->close();
?>
