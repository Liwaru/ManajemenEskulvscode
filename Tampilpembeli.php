<?php
include 'Koneksi.php';

$result=$conn->query("select * from pembeli");
$mobil=array();
while($row=$result->fetch_assoc()){
    $mobil[]=$row;
}
echo json_encode($mobil);
?>