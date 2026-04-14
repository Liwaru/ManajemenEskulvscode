<?php
header("Content-Type: application/json");
error_reporting(0);
include 'Koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? '';
$status = $_POST['status'] ?? 'diterima';

if (!in_array($status, ['menunggu', 'diterima', 'ditolak'], true)) {
    $status = 'diterima';
}

if ($id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "id_eskul wajib dikirim",
        "data" => []
    ]);
    exit();
}

$stmt = $conn->prepare("
    SELECT
        users.id_user,
        users.nis,
        users.nama,
        pendaftaran.id_pendaftaran,
        pendaftaran.id_eskul,
        pendaftaran.status,
        pendaftaran.tanggal_daftar,
        eskul.nama_eskul,
        COALESCE(jadwal_info.hari, '') AS hari
    FROM pendaftaran
    JOIN users ON users.id_user = pendaftaran.id_user
    JOIN eskul ON eskul.id_eskul = pendaftaran.id_eskul
    LEFT JOIN (
        SELECT
            id_eskul,
            GROUP_CONCAT(hari ORDER BY id_jadwal SEPARATOR ', ') AS hari
        FROM jadwal
        GROUP BY id_eskul
    ) AS jadwal_info ON jadwal_info.id_eskul = eskul.id_eskul
    WHERE pendaftaran.id_eskul = ?
    AND pendaftaran.status = ?
    ORDER BY users.nama ASC
");
$stmt->bind_param("is", $id_eskul, $status);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    "success" => true,
    "id_eskul" => (int) $id_eskul,
    "status" => $status,
    "data" => $data
]);

$stmt->close();
$conn->close();
?>
