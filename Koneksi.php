<?php
// 1234567890 Nofiyani

$host = "localhost";
$user = "root"; 
$pass = ""; 
$dbName = "db_2311500173";
$conn = mysqli_connect($host, $user, $pass);


if (!$conn) {
    die("Koneksi MySql Gagal !!<br>" . mysqli_connect_error());
}
echo "<h6>Koneksi MySql Berhasil !!<br></h6>";

$sql = mysqli_select_db($conn, $dbName);
if (!$sql) {
    die ("Koneksi Database Gagal !!".mysqli_error($conn));
}
echo ("<h6>Koneksi Database Berhasil !!</h6>");
?>