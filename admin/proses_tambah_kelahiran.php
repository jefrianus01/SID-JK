<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
include '../backend/koneksi.php'; 

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: tambah_kelahiran.php?error=$err");
    exit;
}

$nama_bayi     = trim($_POST['nama_bayi']);
$jenis_kelamin = trim($_POST['jenis_kelamin']);
$tanggal_lahir = $_POST['tanggal_lahir'];
$nama_ortu     = trim($_POST['nama_ortu']);

// Asumsi struktur tabel_kelahiran: nama_bayi, jenis_kelamin, tanggal_lahir, nama_ortu
$stmt = mysqli_prepare($koneksi, "INSERT INTO tabel_kelahiran (nama_bayi, jenis_kelamin, tanggal_lahir, nama_ortu) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $nama_bayi, $jenis_kelamin, $tanggal_lahir, $nama_ortu);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['sukses'] = "Data Kelahiran berhasil ditambahkan!";
    header("location: kelahiran.php");
} else {
    $err = urlencode("Gagal menyimpan data kelahiran.");
    header("location: tambah_kelahiran.php?error=$err");
}
exit;
?>