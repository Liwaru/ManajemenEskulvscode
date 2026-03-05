<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'Koneksi.php';
header('Content-Type: application/json');


$response=['success'=>false,'message'=>''];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $noktp= $_POST['ktp'] ?? '';
    $nama= $_POST['nama_pembeli'] ?? '';
    $alamat= $_POST['alamat_pembeli'] ?? '';
    $telepon= $_POST['telp_pembeli'] ?? '';
    if($noktp && $nama && $alamat && $telepon){
        
            $stmt = $conn->prepare("INSERT INTO 
            pembeli (ktp,nama_pembeli,alamat_pembeli,telp_pembeli) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss",$noktp,$nama,$alamat,$telepon);
            $response['success']=$stmt->execute();
            $response['message']=$response['success']?"Data berhasil disimpan":"Gagal menyimpan data: ".$stmt->error;
            $stmt->close();
    }
    else{
        $response['message']="Semua data wajib diisi!";

    }
}
else{
    $response['message']="Metode Request Tidak Valid";

}
ob_end_clean();
echo json_encode($response);

?>