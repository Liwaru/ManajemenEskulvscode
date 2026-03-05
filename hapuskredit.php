<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$kode_kredit = $_POST['kode_kredit'] ?? '';

if ($kode_kredit) {
    $sql = "DELETE FROM kredit WHERE kode_kredit='$kode_kredit'";
    if ($conn->query($sql)) {
        echo json_encode(["status"=>1, "message"=>"Data berhasil dihapus"]);
    } else {
        echo json_encode(["status"=>0, "message"=>"Gagal menghapus: ".$conn->error]);
    }
} else {
    echo json_encode(["status"=>0, "message"=>"Parameter tidak lengkap"]);
}
?>
