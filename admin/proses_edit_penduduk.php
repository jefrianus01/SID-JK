<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

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

// Validasi NIK harus 16 digit angka
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    $err = urlencode("Format NIK salah! NIK harus terdiri dari persis 16 digit angka.");
    header("location: edit_penduduk.php?id_penduduk=$id_penduduk&error=$err");
    exit;
}

// Query Update
$query = "UPDATE tabel_penduduk SET 
            nik = '$nik',
            nama = '$nama',
            tempat_lahir = '$tempat_lahir',
            tanggal_lahir = '$tanggal_lahir',
            jenis_kelamin = '$jenis_kelamin',
            agama = '$agama',
            status_perkawinan = '$status_perkawinan',
            pendidikan = '$pendidikan',
            pekerjaan = '$pekerjaan',
            status = '$status'
          WHERE id_penduduk = '$id_penduduk'";

if (mysqli_query($koneksi, $query)) {
    $_SESSION['sukses'] = "Data penduduk berhasil diperbarui!";
    header("location: data_penduduk.php");
    exit;
} else {
    $db_error = mysqli_error($koneksi);
    $err = urlencode("Gagal memperbarui data: " . $db_error);
    header("location: edit_penduduk.php?id_penduduk=$id_penduduk&error=$err");
    exit;
}
?>