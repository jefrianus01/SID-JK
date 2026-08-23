<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data
$id_kepala_desa    = mysqli_real_escape_string($koneksi, $_POST['id_kepala_desa']);
$nik               = mysqli_real_escape_string($koneksi, $_POST['nik']);
$nama              = mysqli_real_escape_string($koneksi, $_POST['nama']);
$tempat_lahir      = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
$tanggal_lahir     = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
$jenis_kelamin     = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$agama             = mysqli_real_escape_string($koneksi, $_POST['agama']);
$status_perkawinan = mysqli_real_escape_string($koneksi, $_POST['status_perkawinan']);
$pendidikan        = mysqli_real_escape_string($koneksi, $_POST['pendidikan']);
$pekerjaan         = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
$status            = mysqli_real_escape_string($koneksi, $_POST['status']);

// Query Insert
$query = "INSERT INTO tabel_penduduk 
          (id_kepala_desa, nik, nama, tanggal_lahir, jenis_kelamin, tempat_lahir, agama, status_perkawinan, pendidikan, pekerjaan, status) 
          VALUES 
          ('$id_kepala_desa', '$nik', '$nama', '$tanggal_lahir', '$jenis_kelamin', '$tempat_lahir', '$agama', '$status_perkawinan', '$pendidikan', '$pekerjaan', '$status')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data Penduduk berhasil ditambahkan!');
            window.location.href = 'data_penduduk.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menambahkan data');
            window.location.href = 'tambah_penduduk.php';
          </script>";
}
?>