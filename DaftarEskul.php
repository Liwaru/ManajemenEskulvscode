<?php
header("Content-Type: application/json");
error_reporting(0);
include 'koneksi.php';

// Dukung payload form-data maupun JSON body.
$input = json_decode(file_get_contents("php://input"), true);
if (!is_array($input)) {
    $input = [];
}

$id_user = $_POST['id_user'] ?? ($input['id_user'] ?? '');
$id_eskul = $_POST['id_eskul'] ?? ($input['id_eskul'] ?? '');
$tanggal = date("Y-m-d H:i:s");

if ($id_user === '' || $id_eskul === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

$stmtEskul = $conn->prepare("SELECT id_eskul FROM eskul WHERE id_eskul = ?");
$stmtEskul->bind_param("i", $id_eskul);
$stmtEskul->execute();
$resultEskul = $stmtEskul->get_result();

if ($resultEskul->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Eskul tidak ditemukan"
    ]);
    $stmtEskul->close();
    exit();
}
$stmtEskul->close();

$stmtCek = $conn->prepare("SELECT id_pendaftaran FROM pendaftaran WHERE id_user = ? AND id_eskul = ?");
$stmtCek->bind_param("ii", $id_user, $id_eskul);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Sudah daftar eskul ini"
    ]);
    $stmtCek->close();
    exit();
}
$stmtCek->close();

$stmtInsert = $conn->prepare("INSERT INTO pendaftaran (id_user, id_eskul, status, tanggal_daftar) VALUES (?, ?, 'proses', ?)");
$stmtInsert->bind_param("iis", $id_user, $id_eskul, $tanggal);

if ($stmtInsert->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Berhasil daftar eskul",
        "id_pendaftaran" => $conn->insert_id
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal daftar",
        "error" => $conn->error
    ]);
}

$stmtInsert->close();
$conn->close();
?>
