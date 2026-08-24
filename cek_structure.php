<?php
$koneksi = new mysqli("localhost", "root", "", "kependudukan_asmanulea");
$result = $koneksi->query("DESCRIBE tabel_penduduk");
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . " " . ($row['Null'] ?? '') . " " . ($row['Key'] ?? '') . "\n";
}
?>