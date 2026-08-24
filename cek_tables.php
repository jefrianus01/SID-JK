<?php
$koneksi = new mysqli("localhost", "root", "", "kependudukan_asmanulea");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
$result = $koneksi->query("SHOW TABLES");
if (!$result) {
    echo "Query failed: " . $koneksi->error;
    exit;
}
while ($row = $result->fetch_assoc()) {
    echo $row['Tables_in_kependudukan_asmanulea'] . "\n";
}
?>