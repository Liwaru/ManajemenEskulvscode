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
    echo json_encode([
        "success" => true,
        "level" => $row['level'],
        "id_user" => $row['id_user'],
        "message" => "Login berhasil"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Username atau password salah"
    ]);
}
?>