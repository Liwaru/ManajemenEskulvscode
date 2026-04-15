<?php
header("Content-Type: application/json");
error_reporting(0);

include 'koneksi.php';

$nama = $_POST['nama'] ?? '';
$password = $_POST['password'] ?? '';

if ($nama == '' || $password == '') {
    echo json_encode([
        "success" => false,
        "message" => "Data kosong"
    ]);
    exit();
}

$stmt = $koneksi->prepare("SELECT * FROM users WHERE nama=? AND password=?");
$stmt->bind_param("ss", $nama, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idEskul = 0;

    // Untuk pembina, turunkan id_eskul dari relasi users.id_pembina -> eskul.id_pembina.
    if ((int) $row['level'] === 2 && !empty($row['id_pembina'])) {
        $stmtEskul = $koneksi->prepare("SELECT id_eskul FROM eskul WHERE id_pembina = ? LIMIT 1");
        $stmtEskul->bind_param("i", $row['id_pembina']);
        $stmtEskul->execute();
        $resultEskul = $stmtEskul->get_result();
        $eskul = $resultEskul->fetch_assoc();
        $idEskul = (int) ($eskul['id_eskul'] ?? 0);
        $stmtEskul->close();
    }

    echo json_encode([
        "success" => true,
        "level" => $row['level'],
        "id_user" => $row['id_user'],
        "id_pembina" => $row['id_pembina'],
        "id_eskul" => $idEskul,
        "message" => "Login berhasil"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Username atau password salah"
    ]);
}
?>
