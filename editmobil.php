<?php
include "Koneksi.php";
header("Content-Type: application/json");

$kode_mobil = $_POST['kode_mobil'] ?? '';
$merk = $_POST['merk'] ?? '';
$type = $_POST['type'] ?? '';
$warna = $_POST['warna'] ?? '';
$harga = $_POST['harga'] ?? '';
$fotoBase64 = $_POST['foto'] ?? ''; // bisa kosong

$response = ["status" => 0, "message" => "Data tidak lengkap"];

if ($kode_mobil && $merk && $type && $warna && $harga) {

    // Ambil data lama dulu
    $q = mysqli_query($conn, "SELECT foto FROM mobil WHERE kode_mobil='$kode_mobil'");
    $old = mysqli_fetch_assoc($q);
    $oldFoto = $old ? $old['foto'] : '';

    $nama_file_final = $oldFoto; // default kalau user tak ganti foto

    // Jika user memilih foto baru
    if (!empty($fotoBase64)) {

        $folder = __DIR__ . "/fotomobil/";
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        // Buat nama file baru
        $nama_file_baru = $kode_mobil . "_" . time() . ".jpg";
        $path_file = $folder . $nama_file_baru;

        // Simpan gambar baru
        $save = file_put_contents($path_file, base64_decode($fotoBase64));

        if ($save !== false) {
            // Update DB pakai foto baru
            $nama_file_final = "fotomobil/" . $nama_file_baru;

            // Hapus foto lama jika ada
            if ($oldFoto && file_exists(__DIR__ . "/" . $oldFoto)) {
                unlink(__DIR__ . "/" . $oldFoto);
            }
        } 
        else {
            echo json_encode(["status" => 0, "message" => "Gagal menyimpan foto"]);
            exit;
        }
    }

    // UPDATE DATA
    $sql = "UPDATE mobil SET 
                merk='$merk',
                type='$type',
                warna='$warna',
                harga='$harga',
                foto='$nama_file_final'
            WHERE kode_mobil='$kode_mobil'";

    if (mysqli_query($conn, $sql)) {
        $response = ["status"=>1, "message"=>"Update berhasil"];
    } else {
        $response = ["status"=>0, "message"=>"Update gagal: " . mysqli_error($conn)];
    }
}

echo json_encode($response);
?>
