<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'Koneksi.php';
header('Content-Type: application/json');


$response=['success'=>false,'message'=>''];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $kode= $_POST['kode_paket'] ?? '';
    $depe= $_POST['uang_muka'] ?? '';
    $tenor= $_POST['tenor'] ?? '';
    $bunga= $_POST['bunga_cicilan'] ?? '';
    if($kode && $depe && $tenor && $bunga){
        if(!is_numeric($depe) || !is_numeric($tenor) || !is_numeric($bunga)){
            $response['message']="Data uang muka,tenor dan bunga harus berupa angka!";
        }
        else{
            $stmt = $conn->prepare("INSERT INTO paket (kode_paket,uang_muka,tenor,bunga_cicilan) 
            VALUES (?,?,?,?)");
            $stmt->bind_param("siii",$kode,$depe,$tenor,$bunga);
            $response['success']=$stmt->execute();
            $response['message']=$response['success']?"Data berhasil disimpan":"Gagal menyimpan data: ".$stmt->error;
            $stmt->close();
        }
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