<?php
include 'Koneksi.php';
header('Content-Type: application/json');

$sql = "
SELECT 
    k.kode_kredit,
    p.nama_pembeli,
    b.tanggal_cicilan,
    b.cicilanke,
    b.jumlah_cicilan,
    b.sisa_cicilan
FROM kredit k
JOIN pembeli p ON k.ktp = p.ktp
LEFT JOIN bayar_cicilan b 
ON b.kode_kredit = k.kode_kredit
AND b.cicilanke = (
    SELECT MAX(cicilanke) 
    FROM bayar_cicilan 
    WHERE kode_kredit = k.kode_kredit
)
ORDER BY k.kode_kredit ASC
";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // jika belum ada cicilan → isi field jadi NULL atau default
        $row['tanggal_cicilan'] = $row['tanggal_cicilan'] ?? "-";
        $row['cicilanke']       = $row['cicilanke']       ?? 0;
        $row['jumlah_cicilan']  = $row['jumlah_cicilan']  ?? 0;
        $row['sisacicilke']     = $row['sisacicilke']     ?? 0;
        $row['sisa_cicilan']    = $row['sisa_cicilan']    ?? 0;

        $data[] = $row;
    }
}

echo json_encode($data);
?>
