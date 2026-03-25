<?php
include 'koneksi.php';

$id_user = $_POST['id_user'];

$query = "SELECT username, password FROM users WHERE id_user='$id_user'";
$result = $conn->query($query);

if ($row = $result->fetch_assoc()) {

    echo json_encode([
        "success" => true,
        "username" => $row['username'],
        "password" => $row['password']
    ]);

} else {
    echo json_encode([
        "success" => false,
        "message" => "Data tidak ditemukan"
    ]);
}

$conn->close();
?>