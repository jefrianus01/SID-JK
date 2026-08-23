<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data
$id_penduduk       = mysqli_real_escape_string($koneksi, $_POST['id_penduduk']);
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

// Query Update
$query = "UPDATE tabel_penduduk SET 
          nik = '$nik', 
          nama = '$nama', 
          tanggal_lahir = '$tanggal_lahir', 
          jenis_kelamin = '$jenis_kelamin', 
          tempat_lahir = '$tempat_lahir', 
          agama = '$agama', 
          status_perkawinan = '$status_perkawinan', 
          pendidikan = '$pendidikan', 
          pekerjaan = '$pekerjaan', 
          status = '$status' 
          WHERE id_penduduk = '$id_penduduk'";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data Penduduk berhasil diperbarui!');
            window.location.href = 'data_penduduk.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal memperbarui data');
            window.location.href = 'edit_penduduk.php?id_penduduk=$id_penduduk';
          </script>";
}
?>