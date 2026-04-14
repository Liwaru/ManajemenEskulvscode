<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

$id_user = isset($_POST['id_user']) ? $_POST['id_user'] : '';
$id_eskul = isset($_POST['id_eskul']) ? $_POST['id_eskul'] : '';
$tanggal = date("Y-m-d H:i:s");

if ($id_user == '' || $id_eskul == '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

// Cek agar user tidak mendaftar eskul yang sama dua kali.
$stmtCek = $conn->prepare("SELECT id_pendaftaran FROM pendaftaran WHERE id_user = ? AND id_eskul = ?");
$stmtCek->bind_param("ii", $id_user, $id_eskul);
$stmtCek->execute();
$cek = $stmtCek->get_result();

if ($cek && $cek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Sudah daftar eskul ini"
    ]);
    $stmtCek->close();
    exit();
}
$stmtCek->close();

$stmtInsert = $conn->prepare("INSERT INTO pendaftaran (id_user, id_eskul, status, tanggal_daftar) VALUES (?, ?, 'menunggu', ?)");
$stmtInsert->bind_param("iis", $id_user, $id_eskul, $tanggal);

if ($stmtInsert->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Berhasil daftar eskul"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal daftar"
    ]);
}

$stmtInsert->close();
$conn->close();
?>
