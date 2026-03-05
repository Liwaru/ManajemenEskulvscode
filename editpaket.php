<?php
include "Koneksi.php";
header("Content-Type: application/json");

$kode_paket     = $_POST['kode_paket'] ?? '';
$uang_muka      = $_POST['uang_muka'] ?? '';
$tenor          = $_POST['tenor'] ?? '';
$bunga_cicilan  = $_POST['bunga_cicilan'] ?? '';

$response = ["status" => 0, "message" => "Data tidak lengkap"];

if ($kode_paket && $uang_muka && $tenor && $bunga_cicilan) {

    $sql = "UPDATE paket 
            SET uang_muka = ?, tenor = ?, bunga_cicilan = ?
            WHERE kode_paket = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiis", $uang_muka, $tenor, $bunga_cicilan, $kode_paket);

    if (mysqli_stmt_execute($stmt)) {
        $response = ["status" => 1, "message" => "Update berhasil"];
    } else {
        $response = ["status" => 0, "message" => "Update gagal: " . mysqli_error($conn)];
    }
}

echo json_encode($response);
?>
