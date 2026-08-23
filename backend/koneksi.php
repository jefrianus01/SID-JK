<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kependudukan_asmanulea"; // Pastikan namanya ini

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>