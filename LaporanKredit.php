<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$periode = $_GET['periode'] ?? 'bulan';
$tanggal = $_GET['tanggal'] ?? date('Y-m');

if ($periode === 'tahun') {
    $sql = "SELECT k.kode_kredit, p.nama_pembeli, m.merk, m.type, 
                   k.tanggal_kredit, k.totalcicil AS bayar_kredit
            FROM kredit k 
            LEFT JOIN pembeli p ON k.ktp = p.ktp 
            LEFT JOIN mobil m ON k.kode_mobil = m.kode_mobil
            WHERE YEAR(k.tanggal_kredit) = '$tanggal'
            ORDER BY k.tanggal_kredit DESC";
} else {
    $sql = "SELECT k.kode_kredit, p.nama_pembeli, m.merk, m.type, 
                   k.tanggal_kredit, k.totalcicil AS bayar_kredit
            FROM kredit k 
            LEFT JOIN pembeli p ON k.ktp = p.ktp 
            LEFT JOIN mobil m ON k.kode_mobil = m.kode_mobil
            WHERE DATE_FORMAT(k.tanggal_kredit, '%Y-%m') = '$tanggal'
            ORDER BY k.tanggal_kredit DESC";
}

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
