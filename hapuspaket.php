<?php
include "koneksi.php";
$kode_mobil = $_POST['kode_paket'];

$response = ["status"=>0,"message"=>"Gagal menghapus"];

if(!empty($kode_mobil)){
    $query = "DELETE FROM paket WHERE kode_paket='$kode_mobil'";
    if(mysqli_query($conn, $query)){
        $response["status"] = 1;
        $response["message"] = "Berhasil dihapus";
    }
}
echo json_encode($response);
?>