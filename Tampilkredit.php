<?php
include 'Koneksi.php';

$sql = "SELECT 
            k.kode_kredit,
            k.tanggal_kredit,
            k.bayar_kredit,
            
            p.ktp,
            p.nama_pembeli,
            
            m.kode_mobil,
            m.merk,
            m.type,
            m.warna,
            m.harga,
            
            pk.kode_paket,
            pk.uang_muka,
            pk.tenor,
            pk.bunga_cicilan
            
        FROM kredit k
        JOIN pembeli p ON k.ktp = p.ktp
        JOIN mobil m ON k.kode_mobil = m.kode_mobil
        JOIN paket pk ON k.kode_paket = pk.kode_paket
        ORDER BY k.kode_kredit DESC";

$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
