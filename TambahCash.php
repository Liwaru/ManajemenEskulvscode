<?php
include 'Koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$response = ['success'=>false,'message'=>''];

if($_SERVER['REQUEST_METHOD']==='POST'){

    $kode_cash = $_POST['kode_cash'] ?? '';
    $ktp = $_POST['ktp'] ?? '';
    $kode_mobil = $_POST['kode_mobil'] ?? '';
    $cash_tgl = $_POST['cash_tgl'] ?? '';

    if($kode_cash && $ktp && $kode_mobil && $cash_tgl){

        $stmt = $conn->prepare("SELECT harga FROM mobil WHERE kode_mobil=?");
        $stmt->bind_param("s",$kode_mobil);
        $stmt->execute();
        $stmt->bind_result($harga);
        $stmt->fetch();
        $stmt->close();

        if(!empty($harga)){

            $stmt2 = $conn->prepare("INSERT INTO belicash 
            (kode_cash, ktp, kode_mobil, cash_tgl, cash_bayar) 
            VALUES (?,?,?,?,?)");

            $stmt2->bind_param("ssssd",
                $kode_cash,
                $ktp,
                $kode_mobil,
                $cash_tgl,
                $harga
            );

            if($stmt2->execute()){
                $response['success'] = true;
                $response['message'] = "Data berhasil disimpan";
            }else{
                $response['message'] = $stmt2->error;
            }

            $stmt2->close();

        }else{
            $response['message'] = "Harga mobil tidak ditemukan";
        }

    }else{
        $response['message'] = "Semua data wajib diisi";
    }

}else{
    $response['message'] = "Request tidak valid";
}

echo json_encode($response);
?>