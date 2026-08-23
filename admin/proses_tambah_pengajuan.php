<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data
$tanggal_pengajuan = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengajuan']);
$nik               = mysqli_real_escape_string($koneksi, $_POST['nik']);
$nama              = mysqli_real_escape_string($koneksi, $_POST['nama']);
$jenis_surat       = mysqli_real_escape_string($koneksi, $_POST['jenis_surat']);
$status            = 'Proses'; // Status default saat baru diajukan

// Query Insert
$query = "INSERT INTO tabel_pengajuan (tanggal_pengajuan, nik, nama, jenis_surat, status) 
          VALUES ('$tanggal_pengajuan', '$nik', '$nama', '$jenis_surat', '$status')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data Pengajuan berhasil ditambahkan!');
            window.location.href = 'data_pengajuan.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menambahkan data');
            window.location.href = 'tambah_pengajuan.php';
          </script>";
}
?>