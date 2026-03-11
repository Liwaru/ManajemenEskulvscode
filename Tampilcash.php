<?php
include 'Koneksi.php';

$result=$conn->query("select * from belicash");
$mobil=array();
while($row=$result->fetch_assoc()){
    $mobil[]=$row;
}
echo json_encode($mobil);
?>