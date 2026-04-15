<?php
header("Content-Type: application/json");
include 'koneksi.php';

function simpanGambarEskul($gambar, $gambarLama = '')
{
    $gambar = trim($gambar);
    if ($gambar === '') {
        return $gambarLama;
    }

    $isUrl = stripos($gambar, 'http://') === 0 || stripos($gambar, 'https://') === 0;
    $isUploadPath = stripos($gambar, 'uploads/') === 0 || stripos($gambar, 'upload/') === 0;
    $isDataUri = stripos($gambar, 'data:image') === 0 || stripos($gambar, 'base64,') !== false;
    $isLikelyBase64 = !$isUrl && !$isUploadPath && preg_match('/^[A-Za-z0-9+\/=\r\n]+$/', $gambar) && strlen($gambar) > 100;

    if ($isUrl || $isUploadPath) {
        return $gambar;
    }

    if (!$isDataUri && !$isLikelyBase64) {
        return $gambarLama;
    }

    $rawBase64 = strpos($gambar, 'base64,') !== false ? substr($gambar, strpos($gambar, 'base64,') + 7) : $gambar;
    $binary = base64_decode($rawBase64, true);
    if ($binary === false) {
        return $gambarLama;
    }

    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $namaFile = time() . '_' . mt_rand(1000, 9999) . '.jpg';
    $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $namaFile;

    if (file_put_contents($fullPath, $binary) === false) {
        return $gambarLama;
    }

    return 'uploads/' . $namaFile;
}

$id_eskul = $_POST['id_eskul'] ?? ($_POST['id'] ?? '');
$nama_eskul = trim($_POST['nama_eskul'] ?? '');
$nama_pembina = trim($_POST['nama_pembina'] ?? '');
$jam_mulai = trim($_POST['jam_mulai'] ?? '');
$jam_selesai = trim($_POST['jam_selesai'] ?? '');
$gambar = trim($_POST['gambar'] ?? '');

if ($id_eskul === '' || $nama_eskul === '' || $nama_pembina === '' || $jam_mulai === '' || $jam_selesai === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

$stmtCek = $conn->prepare("SELECT id_eskul, gambar FROM eskul WHERE id_eskul = ?");
$stmtCek->bind_param("i", $id_eskul);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();
$eskul = $resultCek->fetch_assoc();

if (!$eskul) {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul tidak ditemukan"
    ]);
    $stmtCek->close();
    exit();
}
$stmtCek->close();

$gambar = simpanGambarEskul($gambar, $eskul['gambar'] ?? '');

$stmt = $conn->prepare("
    UPDATE eskul
    SET nama_eskul = ?, nama_pembina = ?, jam_mulai = ?, jam_selesai = ?, gambar = ?
    WHERE id_eskul = ?
");
$stmt->bind_param("sssssi", $nama_eskul, $nama_pembina, $jam_mulai, $jam_selesai, $gambar, $id_eskul);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Data eskul berhasil diupdate",
        "data" => [
            "id_eskul" => (int) $id_eskul,
            "nama_eskul" => $nama_eskul,
            "nama_pembina" => $nama_pembina,
            "jam_mulai" => $jam_mulai,
            "jam_selesai" => $jam_selesai,
            "gambar" => $gambar
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal update eskul"
    ]);
}

$stmt->close();
$conn->close();
?>
