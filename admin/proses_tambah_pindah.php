<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap data dari form
$id_penduduk    = mysqli_real_escape_string($koneksi, $_POST['id_penduduk']);
$tanggal_pindah = mysqli_real_escape_string($koneksi, $_POST['tanggal_pindah']);
$alamat_tujuan  = mysqli_real_escape_string($koneksi, $_POST['alamat_tujuan']);
$alasan_pindah  = mysqli_real_escape_string($koneksi, $_POST['alasan_pindah']);

// Query Insert ke tabel_pindah
$query = "INSERT INTO tabel_pindah (id_penduduk, tanggal_pindah, alamat_tujuan, alasan_pindah) 
          VALUES ('$id_penduduk', '$tanggal_pindah', '$alamat_tujuan', '$alasan_pindah')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data kepindahan warga berhasil dicatat!');
            window.location.href = 'pindah.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal mencatat data pindah');
            window.location.href = 'tambah_pindah.php';
          </script>";
}
?>