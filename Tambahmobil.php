<?php
include 'Koneksi.php';
header('Content-Type: text/plain');

if($_SERVER['REQUEST_METHOD']=='POST'){
$kode  = $_POST['kode_mobil']?? '';
$merk  = $_POST['merk']?? '';
$type  = $_POST['type']??'';
$warna = $_POST['warna']??'';
$harga = $_POST['harga']??'';
$foto  = $_POST['foto']??'';

if(empty($kode) || empty($merk)|| empty($foto)){
        echo "Data tidak lengkap!";
        exit;
}
$folder = __DIR__ . "/fotomobil/";
if (!file_exists($folder)){
        mkdir($folder,0777,true);
}

$nama_file = $kode . "_" . time() . ".jpg";
$path = $folder . $nama_file;

$fileSaved = file_put_contents($path,base64_decode($foto));

if($fileSaved === false){
        echo "Gagal menyimpan gambar ke folder!";
        exit;
}

$relativePath ="fotomobil/" . $nama_file;
$sql = "INSERT INTO mobil (kode_mobil, merk, type, warna, harga, foto) 
        VALUES ('$kode', '$merk', '$type', '$warna', '$harga', '$relativePath')";

        if(mysqli_query($conn,$sql)){
                echo "Data berhasil disimpan";
        } else{
                echo "Gagal menyimpan ke database:" .mysqli_error($conn);
        }
}else{
        echo "Metode tidak valid";
}
?>