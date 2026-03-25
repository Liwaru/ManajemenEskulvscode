<?php
include 'koneksi.php';

$id_user = $_POST['id_user'];
$id_eskul = $_POST['id_eskul'];
$tanggal = date("Y-m-d H:i:s");

// 🔥 CEK SUDAH DAFTAR ATAU BELUM
$cek = $conn->query("SELECT * FROM pendaftaran 
    WHERE id_user='$id_user' AND id_siswa='$id_eskul'");

if ($cek->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Sudah daftar eskul ini"
    ]);
    exit();
}

// INSERT
$sql = "INSERT INTO pendaftaran (id_user, id_siswa, status, tanggal_daftar)
        VALUES ('$id_user', '$id_eskul', 'proses', '$tanggal')";

if ($conn->query($sql) === TRUE) {
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

$conn->close();
?>