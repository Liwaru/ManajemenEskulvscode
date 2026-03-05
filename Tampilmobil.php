<?php
include 'Koneksi.php';

$sql = "SELECT * FROM mobil ORDER BY kode_mobil ASC";
$result = $conn->query($sql);

$mobil = [];

$server_url = "http://192.168.0.15/mobil/";

while ($row = $result->fetch_assoc()) {
    $row['foto'] = $server_url . $row['foto'];
    $mobil[] = $row;
}

header('Content-Type: application/json');
echo json_encode($mobil);
?>