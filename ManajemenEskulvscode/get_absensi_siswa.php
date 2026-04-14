<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';

if ($id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "id_eskul wajib dikirim",
        "data" => []
    ]);
    exit();
}

$sql = "
    SELECT
        absensi.id_absensi,
        absensi.id_siswa,
        absensi.id_eskul,
        users.nis,
        users.nama,
        eskul.nama_eskul,
        eskul.nama_pembina,
        COALESCE(jadwal_info.hari, '') AS hari,
        absensi.tanggal_absensi
    FROM absensi
    JOIN users ON users.id_user = absensi.id_siswa
    JOIN eskul ON eskul.id_eskul = absensi.id_eskul
    LEFT JOIN (
        SELECT
            id_eskul,
            GROUP_CONCAT(hari ORDER BY id_jadwal SEPARATOR ', ') AS hari
        FROM jadwal
        GROUP BY id_eskul
    ) AS jadwal_info ON jadwal_info.id_eskul = eskul.id_eskul
    WHERE absensi.id_eskul = ?
";

if ($tanggal !== '') {
    $sql .= " AND DATE(absensi.tanggal_absensi) = ?";
}

$sql .= " ORDER BY absensi.tanggal_absensi DESC, users.nama ASC";

$stmt = $conn->prepare($sql);

if ($tanggal !== '') {
    $stmt->bind_param("is", $id_eskul, $tanggal);
} else {
    $stmt->bind_param("i", $id_eskul);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode([
    "success" => true,
    "id_eskul" => (int) $id_eskul,
    "tanggal" => $tanggal,
    "data" => $data
]);

$stmt->close();
$conn->close();
?>
