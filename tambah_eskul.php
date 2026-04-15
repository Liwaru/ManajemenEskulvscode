<?php
header("Content-Type: application/json");
include 'koneksi.php';

function simpanGambarEskul($gambar)
{
    $gambar = trim($gambar);
    if ($gambar === '') {
        return '';
    }

    $isUrl = stripos($gambar, 'http://') === 0 || stripos($gambar, 'https://') === 0;
    $isUploadPath = stripos($gambar, 'uploads/') === 0 || stripos($gambar, 'upload/') === 0;
    $isDataUri = stripos($gambar, 'data:image') === 0 || stripos($gambar, 'base64,') !== false;
    $isLikelyBase64 = !$isUrl && !$isUploadPath && preg_match('/^[A-Za-z0-9+\/=\r\n]+$/', $gambar) && strlen($gambar) > 100;

    // Jika sudah berupa path atau URL, simpan apa adanya.
    if ($isUrl || $isUploadPath) {
        return $gambar;
    }

    if (!$isDataUri && !$isLikelyBase64) {
        return '';
    }

    $rawBase64 = strpos($gambar, 'base64,') !== false ? substr($gambar, strpos($gambar, 'base64,') + 7) : $gambar;
    $binary = base64_decode($rawBase64, true);
    if ($binary === false) {
        return '';
    }

    $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $namaFile = time() . '_' . mt_rand(1000, 9999) . '.jpg';
    $fullPath = $uploadDir . DIRECTORY_SEPARATOR . $namaFile;

    if (file_put_contents($fullPath, $binary) === false) {
        return '';
    }

    return 'uploads/' . $namaFile;
}

$nama_eskul = trim($_POST['nama_eskul'] ?? '');
$nama_pembina = trim($_POST['nama_pembina'] ?? '');
$jam_mulai = trim($_POST['jam_mulai'] ?? '');
$jam_selesai = trim($_POST['jam_selesai'] ?? '');
$gambar = simpanGambarEskul($_POST['gambar'] ?? '');

if ($nama_eskul === '' || $nama_pembina === '' || $jam_mulai === '' || $jam_selesai === '') {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak lengkap"
    ]);
    exit();
}

$stmtCek = $conn->prepare("
    SELECT id_eskul
    FROM eskul
    WHERE nama_eskul = ? AND nama_pembina = ? AND jam_mulai = ? AND jam_selesai = ?
");
$stmtCek->bind_param("ssss", $nama_eskul, $nama_pembina, $jam_mulai, $jam_selesai);
$stmtCek->execute();
$resultCek = $stmtCek->get_result();

if ($resultCek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Data eskul sudah ada"
    ]);
    $stmtCek->close();
    exit();
}
$stmtCek->close();

$id_pembina = 0;
$stmtPembina = $conn->prepare("SELECT id_user FROM users WHERE nama = ? AND level = 2 LIMIT 1");
$stmtPembina->bind_param("s", $nama_pembina);
$stmtPembina->execute();
$resultPembina = $stmtPembina->get_result();
$pembina = $resultPembina->fetch_assoc();
if ($pembina) {
    $id_pembina = (int) $pembina['id_user'];
}
$stmtPembina->close();

$stmt = $conn->prepare("
    INSERT INTO eskul (id_pembina, nama_eskul, nama_pembina, jam_mulai, jam_selesai, gambar)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isssss", $id_pembina, $nama_eskul, $nama_pembina, $jam_mulai, $jam_selesai, $gambar);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Data eskul berhasil ditambahkan",
        "data" => [
            "id_eskul" => $conn->insert_id,
            "id_pembina" => $id_pembina,
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
        "message" => "Gagal menambahkan eskul"
    ]);
}

$stmt->close();
$conn->close();
?>
