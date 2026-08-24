<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
include '../backend/koneksi.php'; 

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: tambah_pendatang.php?error=$err");
    exit;
}

$nik            = trim($_POST['nik']);
$nama           = trim($_POST['nama']);
$asal_wilayah   = trim($_POST['asal_wilayah']);
$tanggal_datang = $_POST['tanggal_datang'];
$keterangan     = trim($_POST['keterangan']);

// Asumsi struktur kolom tabel_pendatang (sesuaikan nama kolom jika beda): nik, nama, asal_wilayah, tanggal_datang, keterangan
$stmt = mysqli_prepare($koneksi, "INSERT INTO tabel_pendatang (nik, nama, asal_wilayah, tanggal_datang, keterangan) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssss", $nik, $nama, $asal_wilayah, $tanggal_datang, $keterangan);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['sukses'] = "Data Pendatang berhasil dicatat!";
    header("location: pendatang.php");
} else {
    $err = urlencode("Gagal menyimpan data pendatang.");
    header("location: tambah_pendatang.php?error=$err");
}
exit;
?>