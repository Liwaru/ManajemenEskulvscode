<?php
include 'Koneksi.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        isset($_POST['kode_cicilan']) &&
        isset($_POST['kode_kredit']) &&
        isset($_POST['ktp']) &&
        isset($_POST['tanggal_cicilan']) &&
        isset($_POST['cicilanke']) &&
        isset($_POST['jumlah_cicilan']) &&
        isset($_POST['sisacicilke']) &&
        isset($_POST['sisa_cicilan'])
    ) {

        $kode_cicilan = $_POST['kode_cicilan'];
        $kode_kredit = $_POST['kode_kredit'];
        $ktp = $_POST['ktp'];
        $tanggal = $_POST['tanggal_cicilan'];
        $cicilanke = $_POST['cicilanke'];
        $jumlah = $_POST['jumlah_cicilan'];
        $sisa_ke = $_POST['sisacicilke'];
        $sisa_harga = $_POST['sisa_cicilan'];

        // UPDATE ke tabel bayar_cicilan
        $sql = "UPDATE bayar_cicilan SET
                    kode_kredit='$kode_kredit',
                    tanggal_cicilan='$tanggal',
                    cicilanke='$cicilanke',
                    jumlah_cicilan='$jumlah',
                    sisacicilke='$sisa_ke',
                    sisa_cicilan='$sisa_harga'
                WHERE kode_cicilan='$kode_cicilan'";

        if ($conn->query($sql)) {
            echo json_encode(["status" => 1, "message" => "Data cicilan berhasil diupdate"]);
        } else {
            echo json_encode(["status" => 0, "message" => "Gagal update data: " . $conn->error]);
        }

    } else {
        echo json_encode(["status" => 0, "message" => "Parameter tidak lengkap"]);
    }

} else {
    echo json_encode(["status" => 0, "message" => "Metode tidak valid"]);
}
?>
