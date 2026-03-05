<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$periode = $_GET['periode'] ?? 'bulan'; 
$tanggal = $_GET['tanggal'] ?? date('Y-m'); 

if ($periode === 'tahun') {
    $sql = "SELECT b.kode_cash, p.nama_pembeli, m.merk, m.type, b.cash_tgl, b.cash_bayar 
            FROM beli_cash b 
            LEFT JOIN pembeli p ON b.ktp = p.ktp 
            LEFT JOIN mobil m ON b.kode_mobil = m.kode_mobil
            WHERE YEAR(b.cash_tgl) = '$tanggal'
            ORDER BY b.cash_tgl DESC";
} else {
    $sql = "SELECT b.kode_cash, p.nama_pembeli, m.merk, m.type, b.cash_tgl, b.cash_bayar 
            FROM beli_cash b 
            LEFT JOIN pembeli p ON b.ktp = p.ktp 
            LEFT JOIN mobil m ON b.kode_mobil = m.kode_mobil
            WHERE DATE_FORMAT(b.cash_tgl, '%Y-%m') = '$tanggal'
            ORDER BY b.cash_tgl DESC";
}

$result = $conn->query($sql);
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>