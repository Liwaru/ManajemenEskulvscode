<?php
include "Koneksi.php";

$ktp   = $_POST['ktp'] ?? '';
$nama  = $_POST['nama_pembeli'] ?? '';
$alamat= $_POST['alamat_pembeli'] ?? '';
$telp  = $_POST['telp_pembeli'] ?? '';

$response = ["status"=>0,"message"=>"data tidak lengkap"];

if($ktp != '' && $nama != '' && $alamat != '' && $telp != ''){

    $sql = "UPDATE pembeli SET
            nama_pembeli='$nama',
            alamat_pembeli='$alamat',
            telp_pembeli='$telp'
            WHERE ktp='$ktp'";

    $query = mysqli_query($conn,$sql);

    if($query){
        if(mysqli_affected_rows($conn) > 0){
            $response = ["status"=>1,"message"=>"update berhasil"];
        }else{
            $response = ["status"=>0,"message"=>"data tidak ditemukan / tidak berubah"];
        }
    }else{
        $response = ["status"=>0,"message"=>"query gagal: ".mysqli_error($conn)];
    }

}

echo json_encode($response);
?>