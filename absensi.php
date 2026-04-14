<?php
include 'koneksi.php';

$id_siswa = $_POST['id_siswa'];
$id_eskul = $_POST['id_eskul'];
$tanggal = date("Y-m-d H:i:s");

// 🔥 CEK: sudah absen hari ini belum?
$query_cek = "
SELECT * FROM absensi 
WHERE id_siswa='$id_siswa' 
AND id_eskul='$id_eskul' 
AND DATE(tanggal_absensi)=CURDATE()
";

$result = $conn->query($query_cek);

if ($result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "Kamu sudah absen hari ini!"
    ]);
} else {

    $query = "
    INSERT INTO absensi (id_siswa, id_eskul, tanggal_absensi)
    VALUES ('$id_siswa', '$id_eskul', '$tanggal')
    ";

    if ($conn->query($query) === TRUE) {
        echo json_encode([
            "success" => true,
            "message" => "Absensi berhasil"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Gagal absen"
        ]);
    }
}

$conn->close();
?>