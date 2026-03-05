<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_cicilan   = $_POST['kode_cicilan'] ?? '';
    $kode_kredit    = $_POST['kode_kredit'] ?? '';
    $tanggal        = $_POST['tanggal_cicilan'] ?? '';
    $cicilanke      = $_POST['cicilanke'] ?? '';
    $jumlah_cicilan = $_POST['jumlah_cicilan'] ?? '';
    $sisacicilke    = $_POST['sisacicilke'] ?? '';
    $sisa_cicilan   = $_POST['sisa_cicilan'] ?? '';

    if ($kode_cicilan && $kode_kredit && $tanggal && $cicilanke && $jumlah_cicilan && $sisacicilke && $sisa_cicilan) {

        $cek = $conn->prepare("SELECT COUNT(*) AS jml FROM kredit WHERE kode_kredit=?");
        $cek->bind_param("s", $kode_kredit);
        $cek->execute();
        $result = $cek->get_result()->fetch_assoc();
        if ($result['jml'] == 0) {
            $response['message'] = "Kode kredit tidak ditemukan!";
            echo json_encode($response);
            exit;
        }
        $cek->close();

        $stmt = $conn->prepare("INSERT INTO bayar_cicilan 
            (kode_cicilan, kode_kredit, tanggal_cicilan, cicilanke, jumlah_cicilan, sisacicilke, sisa_cicilan)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiiii", 
            $kode_cicilan, 
            $kode_kredit, 
            $tanggal, 
            $cicilanke, 
            $jumlah_cicilan, 
            $sisacicilke, 
            $sisa_cicilan
        );

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
