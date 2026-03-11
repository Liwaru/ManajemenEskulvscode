<?php
include 'Koneksi.php';

$sql = "SELECT * FROM mobil ORDER BY kode_mobil ASC";
$result = $conn->query($sql);

$mobil = [];
$server_url = "http://192.168.0.15/mobil/";

// default foto jika kosong
$default_foto = "uploads/default.jpg";

while ($row = $result->fetch_assoc()) {
    // pastikan key 'foto' ada dan tidak kosong
    $row['foto'] = !empty($row['foto']) ? $server_url . $row['foto'] : $server_url . $default_foto;
    $mobil[] = $row;
}

header('Content-Type: application/json');
echo json_encode($mobil);
?>