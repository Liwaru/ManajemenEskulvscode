<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_cash = $_POST['kode_cash'] ?? '';
    $ktp = $_POST['ktp'] ?? '';
    $kode_mobil = $_POST['kode_mobil'] ?? '';
    $cash_tgl = $_POST['cash_tgl'] ?? '';

    if ($kode_cash && $ktp && $kode_mobil && $cash_tgl) {
        $sql = "UPDATE beli_cash SET ktp='$ktp', kode_mobil='$kode_mobil', cash_tgl='$cash_tgl' WHERE kode_cash='$kode_cash'";
        if (mysqli_query($conn, $sql)) {
            $response['success'] = true;
            $response['message'] = 'Data berhasil diperbarui';
        } else {
            $response['message'] = 'Gagal memperbarui data: ' . mysqli_error($conn);
        }
    } else {
        $response['message'] = 'Semua data wajib diisi!';
    }
} else {
    $response['message'] = 'Metode request tidak valid!';
}

echo json_encode($response);
?>
