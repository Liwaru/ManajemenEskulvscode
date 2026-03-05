<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$response = ["status" => 0, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_cash = $_POST['kode_cash'] ?? '';

    if ($kode_cash) {
        $sql = "DELETE FROM beli_cash WHERE kode_cash='$kode_cash'";
        if (mysqli_query($conn, $sql)) {
            $response = ["status" => 1, "message" => "Data berhasil dihapus"];
        } else {
            $response["message"] = "Gagal menghapus: " . mysqli_error($conn);
        }
    } else {
        $response["message"] = "Kode cash tidak ditemukan!";
    }
} else {
    $response["message"] = "Metode request tidak valid!";
}

echo json_encode($response);
?>
