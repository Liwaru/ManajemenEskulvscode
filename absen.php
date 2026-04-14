<?php
include 'koneksi.php';

$id_user = $_POST['id_user'];
$id_eskul = $_POST['id_eskul'];
$tanggal = date("Y-m-d");

$query = "INSERT INTO absensi (id_user, id_eskul, tanggal, status)
VALUES ('$id_user', '$id_eskul', '$tanggal', 'Hadir')";

if ($conn->query($query)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}