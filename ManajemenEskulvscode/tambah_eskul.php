<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$nama_eskul = trim($_POST['nama_eskul'] ?? '');
$nama_pembina = trim($_POST['nama_pembina'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$hari = trim($_POST['hari'] ?? '');
$jam_mulai = trim($_POST['jam_mulai'] ?? '');
$jam_selesai = trim($_POST['jam_selesai'] ?? '');
$gambar = trim($_POST['gambar'] ?? '');

if ($nama_eskul === '' || $nama_pembina === '' || $gambar === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul belum lengkap"
    ]);
    exit();
}

$stmtCek = $conn->prepare("SELECT id_eskul FROM eskul WHERE nama_eskul = ?");
$stmtCek->bind_param("s", $nama_eskul);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek && $resultCek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Nama eskul sudah ada"
    ]);
    $stmtCek->close();
    $conn->close();
    exit();
}
$stmtCek->close();

$stmt = $conn->prepare("
    INSERT INTO eskul (nama_eskul, nama_pembina, deskripsi, jam_mulai, jam_selesai, gambar)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssss", $nama_eskul, $nama_pembina, $deskripsi, $jam_mulai, $jam_selesai, $gambar);

if ($stmt->execute()) {
    $idEskulBaru = $stmt->insert_id;

    if ($hari !== '' && $jam_mulai !== '' && $jam_selesai !== '') {
        $stmtJadwal = $conn->prepare("INSERT INTO jadwal (id_eskul, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?)");
        $stmtJadwal->bind_param("isss", $idEskulBaru, $hari, $jam_mulai, $jam_selesai);
        $stmtJadwal->execute();
        $stmtJadwal->close();
    }

    echo json_encode([
        "success" => true,
        "message" => "Eskul berhasil ditambahkan",
        "id_eskul" => $idEskulBaru
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal menambah eskul"
    ]);
}

$stmt->close();
$conn->close();
?>
