<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$sql = "
SELECT 
    k.kode_kredit,
    k.ktp,
    k.bayar_kredit,
    k.tenor,
    k.totalcicil,
    COALESCE(COUNT(b.kode_cicilan), 0) AS jumlah_cicilan
FROM kredit k
LEFT JOIN bayar_cicilan b ON k.kode_kredit = b.kode_kredit
GROUP BY k.kode_kredit
";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $jumlah_cicilan = (int)$row['jumlah_cicilan'];

    $row['cicilan_ke'] = $jumlah_cicilan + 1;

    $row['sisa_ke'] = $row['tenor'] - $jumlah_cicilan;

    $row['sisa_harga'] = $row['totalcicil'] - ($row['bayar_kredit'] * $jumlah_cicilan);

    $data[] = $row;
}

echo json_encode($data);
?>
