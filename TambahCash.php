<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$response = ['success'=>false,'message'=>''];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $kode_cash = $_POST['kode_cash'] ?? '';
    $ktp = $_POST['ktp'] ?? '';
    $kode_mobil = $_POST['kode_mobil'] ?? '';
    $cash_tgl = $_POST['cash_tgl'] ?? '';

    if($kode_cash && $ktp && $kode_mobil && $cash_tgl){

        $stmt = $conn->prepare("SELECT harga FROM mobil WHERE kode_mobil=?");
        $stmt->bind_param("s", $kode_mobil);
        $stmt->execute();
        $stmt->bind_result($harga);
        $stmt->fetch();
        $stmt->close();

        if($harga){
            $stmt2 = $conn->prepare("INSERT INTO beli_cash (kode_cash, ktp, kode_mobil, cash_tgl, cash_bayar) 
            VALUES (?,?,?,?,?)");
            $stmt2->bind_param("ssssi", $kode_cash, $ktp, $kode_mobil, $cash_tgl, $harga);
            $response['success'] = $stmt2->execute();
            $response['message'] = $response['success'] ? "Data berhasil disimpan" : "Gagal: ".$stmt2->error;
            $stmt2->close();
        } else {
            $response['message'] = "Harga mobil tidak ditemukan!";
        }
    } else {
        $response['message'] = "Semua data wajib diisi!";
    }
} else {
    $response['message'] = "Metode request tidak valid!";
}

echo json_encode($response);
?>
