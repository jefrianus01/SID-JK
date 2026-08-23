<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data
$id_penduduk   = mysqli_real_escape_string($koneksi, $_POST['id_penduduk']);
$nama          = mysqli_real_escape_string($koneksi, $_POST['nama']);
$jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
$tempat_lahir  = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);

// Query Insert ke tabel_kelahiran
$query = "INSERT INTO tabel_kelahiran (id_penduduk, nama, jenis_kelamin, tanggal_lahir, tempat_lahir) 
          VALUES ('$id_penduduk', '$nama', '$jenis_kelamin', '$tanggal_lahir', '$tempat_lahir')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Register Kelahiran berhasil dicatat!');
            window.location.href = 'kelahiran.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal mencatat kelahiran');
            window.location.href = 'tambah_kelahiran.php';
          </script>";
}
?>