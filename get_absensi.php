<?php
include 'koneksi.php';

$id_siswa = $_POST['id_siswa'];

$query = "
SELECT absensi.*, eskul.nama_eskul 
FROM absensi
JOIN eskul ON eskul.id_eskul = absensi.id_eskul
WHERE absensi.id_siswa = '$id_siswa'
ORDER BY tanggal_absensi DESC
";

$result = $conn->query($query);

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$conn->close();
?>