<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

$nik            = mysqli_real_escape_string($koneksi, $_POST['nik']);
$nama           = mysqli_real_escape_string($koneksi, $_POST['nama']);
$jenis_kelamin  = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$tanggal_datang = mysqli_real_escape_string($koneksi, $_POST['tanggal_datang']);
$alamat_asal    = mysqli_real_escape_string($koneksi, $_POST['alamat_asal']);
$keterangan     = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

$query = "INSERT INTO tabel_pendatang (nik, nama, jenis_kelamin, tanggal_datang, alamat_asal, keterangan) 
          VALUES ('$nik', '$nama', '$jenis_kelamin', '$tanggal_datang', '$alamat_asal', '$keterangan')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data pendatang berhasil disimpan!');
            window.location.href = 'pendatang.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menyimpan data');
            window.location.href = 'tambah_pendatang.php';
          </script>";
}
?>