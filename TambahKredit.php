<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_kredit   = $_POST['kode_kredit'] ?? '';
    $ktp           = $_POST['ktp'] ?? '';
    $kode_paket    = $_POST['kode_paket'] ?? '';
    $kode_mobil    = $_POST['kode_mobil'] ?? '';
    $tanggal       = $_POST['tanggal_kredit'] ?? '';
    $bayar_kredit  = $_POST['bayar_kredit'] ?? '';
    $tenor         = $_POST['tenor'] ?? '';
    $totalcicil    = $_POST['totalcicil'] ?? '';

    if ($kode_kredit && $ktp && $kode_paket && $kode_mobil && $tanggal && $bayar_kredit && $tenor && $totalcicil) {
        $stmt = $conn->prepare("INSERT INTO kredit 
            (kode_kredit, ktp, kode_paket, kode_mobil, tanggal_kredit, bayar_kredit, tenor, totalcicil) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssiii", $kode_kredit, $ktp, $kode_paket, $kode_mobil, $tanggal, $bayar_kredit, $tenor, $totalcicil);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Data berhasil disimpan";
        } else {
            $response['message'] = "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Semua data wajib diisi!";
    }
} else {
    $response['message'] = "Metode request tidak valid!";
}

echo json_encode($response);
?>
