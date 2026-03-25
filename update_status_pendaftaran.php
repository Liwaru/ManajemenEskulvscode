<?php
include 'Koneksi.php';

$id_daftar = $_POST['id_daftar'];
$status = $_POST['status']; // diterima / ditolak

$sql = "UPDATE daftar_eskul 
        SET status='$status' 
        WHERE id_daftar='$id_daftar'";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "success" => true,
        "message" => "Status berhasil diupdate"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal update"
    ]);
}
?>