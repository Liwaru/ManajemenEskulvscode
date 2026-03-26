<?php
include 'Koneksi.php';

$nama = $_POST['nama'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE nama='$nama' AND password='$password'";
$result = mysqli_query($conn, $sql);

$response = array();

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $response['success'] = true;
    $response['level'] = $row['level'];
    $response['id_user'] = $row['id_user']; // 🔥 TAMBAHAN
    $response['message'] = "Login berhasil";

} else {
    $response['success'] = false;
    $response['message'] = "Username atau password salah";
}

echo json_encode($response);
?>
