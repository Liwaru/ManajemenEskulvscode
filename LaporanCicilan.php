<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$periode = $_GET['periode'] ?? 'bulan';
$tanggal = $_GET['tanggal'] ?? date('Y-m');

if ($periode === 'tahun') {
    $sql = "SELECT b.kode_cicilan, b.kode_kredit, p.nama_pembeli, 
                   m.merk, m.type, 
                   b.tanggal_cicilan, b.cicilanke, b.jumlah_cicilan, b.sisa_cicilan
            FROM bayar_cicilan b
            LEFT JOIN kredit k ON b.kode_kredit = k.kode_kredit
            LEFT JOIN pembeli p ON k.ktp = p.ktp
            LEFT JOIN mobil m ON k.kode_mobil = m.kode_mobil
            WHERE YEAR(b.tanggal_cicilan) = '$tanggal'
            ORDER BY b.tanggal_cicilan DESC";
} else {
    $sql = "SELECT b.kode_cicilan, b.kode_kredit, p.nama_pembeli, 
                   m.merk, m.type, 
                   b.tanggal_cicilan, b.cicilanke, b.jumlah_cicilan, b.sisa_cicilan
            FROM bayar_cicilan b
            LEFT JOIN kredit k ON b.kode_kredit = k.kode_kredit
            LEFT JOIN pembeli p ON k.ktp = p.ktp
            LEFT JOIN mobil m ON k.kode_mobil = m.kode_mobil
            WHERE DATE_FORMAT(b.tanggal_cicilan, '%Y-%m') = '$tanggal'
            ORDER BY b.tanggal_cicilan DESC";
}

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
