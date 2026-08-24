<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data dari form
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

// Validasi 1: Cek apakah NIK sudah terdaftar di database
$cek_nik = mysqli_query($koneksi, "SELECT nik FROM tabel_penduduk WHERE nik = '$nik'");
if ($cek_nik && mysqli_num_rows($cek_nik) > 0) {
    $err = urlencode("NIK " . $nik . " sudah terdaftar di sistem! Silakan gunakan NIK lain.");
    header("location: tambah_penduduk.php?error=$err");
    exit;
}

// Validasi 2: Cek format NIK (harus 16 digit angka)
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    $err = urlencode("Format NIK salah! NIK harus terdiri dari persis 16 digit angka.");
    header("location: tambah_penduduk.php?error=$err");
    exit;
}

// Query Insert (Kolom ID diserahkan sepenuhnya ke Auto Increment database agar tidak terjadi error duplicate entry)
$query = "INSERT INTO tabel_penduduk 
          (id_kepala_desa, nik, nama, tanggal_lahir, jenis_kelamin, tempat_lahir, agama, status_perkawinan, pendidikan, pekerjaan, status) 
          VALUES 
          ('$id_kepala_desa', '$nik', '$nama', '$tanggal_lahir', '$jenis_kelamin', '$tempat_lahir', '$agama', '$status_perkawinan', '$pendidikan', '$pekerjaan', '$status')";

if (mysqli_query($koneksi, $query)) {
    // Jika berhasil, kembali ke halaman data penduduk
    header("location: data_penduduk.php");
    exit;
} else {
    // Jika gagal, kembalikan pesan error database ke halaman tambah penduduk
    $db_error = mysqli_error($koneksi);
    $err = urlencode("Gagal menyimpan ke database: " . $db_error);
    header("location: tambah_penduduk.php?error=$err");
    exit;
}
?>