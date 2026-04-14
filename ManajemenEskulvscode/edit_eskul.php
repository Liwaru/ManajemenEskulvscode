<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_eskul = $_POST['id_eskul'] ?? ($_POST['id'] ?? '');
$nama_eskul = trim($_POST['nama_eskul'] ?? '');
$nama_pembina = trim($_POST['nama_pembina'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');
$hari = trim($_POST['hari'] ?? '');
$jam_mulai = trim($_POST['jam_mulai'] ?? '');
$jam_selesai = trim($_POST['jam_selesai'] ?? '');
$gambar = trim($_POST['gambar'] ?? '');

if ($id_eskul === '' || $nama_eskul === '' || $nama_pembina === '' || $gambar === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul belum lengkap"
    ]);
    exit();
}

$stmtCek = $conn->prepare("SELECT id_eskul FROM eskul WHERE id_eskul = ?");
$stmtCek->bind_param("i", $id_eskul);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if (!$resultCek || $resultCek->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul tidak ditemukan"
    ]);
    $stmtCek->close();
    $conn->close();
    exit();
}
$stmtCek->close();

$stmtNama = $conn->prepare("SELECT id_eskul FROM eskul WHERE nama_eskul = ? AND id_eskul <> ?");
$stmtNama->bind_param("si", $nama_eskul, $id_eskul);
$stmtNama->execute();
$resultNama = $stmtNama->get_result();

if ($resultNama && $resultNama->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Nama eskul sudah dipakai"
    ]);
    $stmtNama->close();
    $conn->close();
    exit();
}
$stmtNama->close();

$stmt = $conn->prepare("
    UPDATE eskul
    SET nama_eskul = ?, nama_pembina = ?, deskripsi = ?, jam_mulai = ?, jam_selesai = ?, gambar = ?
    WHERE id_eskul = ?
");
$stmt->bind_param("ssssssi", $nama_eskul, $nama_pembina, $deskripsi, $jam_mulai, $jam_selesai, $gambar, $id_eskul);

if ($stmt->execute()) {
    if ($hari !== '' && $jam_mulai !== '' && $jam_selesai !== '') {
        $stmtJadwalCek = $conn->prepare("SELECT id_jadwal FROM jadwal WHERE id_eskul = ? ORDER BY id_jadwal ASC LIMIT 1");
        $stmtJadwalCek->bind_param("i", $id_eskul);
        $stmtJadwalCek->execute();
        $resultJadwalCek = $stmtJadwalCek->get_result();

        if ($resultJadwalCek && $resultJadwalCek->num_rows > 0) {
            $rowJadwal = $resultJadwalCek->fetch_assoc();
            $stmtJadwalUpdate = $conn->prepare("UPDATE jadwal SET hari = ?, jam_mulai = ?, jam_selesai = ? WHERE id_jadwal = ?");
            $stmtJadwalUpdate->bind_param("sssi", $hari, $jam_mulai, $jam_selesai, $rowJadwal['id_jadwal']);
            $stmtJadwalUpdate->execute();
            $stmtJadwalUpdate->close();
        } else {
            $stmtJadwalInsert = $conn->prepare("INSERT INTO jadwal (id_eskul, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?)");
            $stmtJadwalInsert->bind_param("isss", $id_eskul, $hari, $jam_mulai, $jam_selesai);
            $stmtJadwalInsert->execute();
            $stmtJadwalInsert->close();
        }

        $stmtJadwalCek->close();
    }

    echo json_encode([
        "success" => true,
        "message" => "Eskul berhasil diupdate",
        "id_eskul" => (int) $id_eskul
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal mengupdate eskul"
    ]);
}

$stmt->close();
$conn->close();
?>
