<?php
include 'Koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_kredit = $_POST['kode_kredit'];
    $ktp = $_POST['ktp'];
    $kode_paket = $_POST['kode_paket'];
    $kode_mobil = $_POST['kode_mobil'];
    $tanggal_kredit = $_POST['tanggal_kredit'];
    $bayar_kredit = $_POST['bayar_kredit'];
    $tenor = $_POST['tenor'];
    $totalcicil = $_POST['totalcicil'];

    $sql = "UPDATE kredit 
            SET ktp='$ktp', kode_paket='$kode_paket', kode_mobil='$kode_mobil',
                tanggal_kredit='$tanggal_kredit', bayar_kredit='$bayar_kredit',
                tenor='$tenor', totalcicil='$totalcicil'
            WHERE kode_kredit='$kode_kredit'";

    if ($conn->query($sql)) {
        echo json_encode(["status" => 1, "message" => "Data kredit berhasil diupdate"]);
    } else {
        echo json_encode(["status" => 0, "message" => "Gagal update data: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => 0, "message" => "Metode tidak valid"]);
}
?>
