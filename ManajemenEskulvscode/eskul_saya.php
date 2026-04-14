<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = $_POST['id_user'] ?? '';

if ($id_user == '') {
    echo json_encode([]);
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
        eskul.gambar,
        pendaftaran.status,
        pendaftaran.tanggal_daftar
    FROM pendaftaran
    JOIN eskul ON eskul.id_eskul = pendaftaran.id_eskul
    LEFT JOIN (
        SELECT
            id_eskul,
            GROUP_CONCAT(hari ORDER BY id_jadwal SEPARATOR ', ') AS hari,
            MIN(jam_mulai) AS jam_mulai,
            MAX(jam_selesai) AS jam_selesai
        FROM jadwal
        GROUP BY id_eskul
    ) AS jadwal_info ON jadwal_info.id_eskul = eskul.id_eskul
    WHERE pendaftaran.id_user = ? AND pendaftaran.status = 'diterima'
    ORDER BY pendaftaran.tanggal_daftar DESC
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
