<?php
include 'Koneksi.php';

$result=$conn->query("select * from beli_cash");
$mobil=array();
while($row=$result->fetch_assoc()){
    $mobil[]=$row;
}
echo json_encode($mobil);
?>