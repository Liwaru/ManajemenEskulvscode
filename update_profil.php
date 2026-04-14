<?php
include 'Koneksi.php';

$id_user = $_POST['id_user'];
$nama = $_POST['nama'];
$password = $_POST['password'];

$sql = "UPDATE user SET 
        username='$nama',
        password='$password'
        WHERE id_user='$id_user'";

if (mysqli_query($conn, $sql)) {
    echo json_encode([
        "success" => true,
        "message" => "Profil berhasil diupdate"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Gagal update"
    ]);
}
?>