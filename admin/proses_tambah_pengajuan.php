<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
include '../backend/koneksi.php'; 

// Verifikasi CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: tambah_pengajuan.php?error=$err");
    exit;
}

// Memecah input 'pemohon' menjadi NIK dan Nama (karena dikirim gabung dengan #)
$pemohon_data = explode("#", $_POST['pemohon']);
$nik = trim($pemohon_data[0]);
$nama = trim($pemohon_data[1]);

$tanggal_pengajuan = $_POST['tanggal_pengajuan'];
$jenis_surat = trim($_POST['jenis_surat']);
$status = trim($_POST['status']);

// Pastikan kamu sudah membuat tabel_pengajuan dengan struktur (id_pengajuan, nik, nama, tanggal_pengajuan, jenis_surat, status)
$stmt = mysqli_prepare($koneksi, "INSERT INTO tabel_pengajuan (nik, nama, tanggal_pengajuan, jenis_surat, status) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sssss", $nik, $nama, $tanggal_pengajuan, $jenis_surat, $status);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['sukses'] = "Pengajuan Surat Berhasil Dibuat!";
    header("location: data_pengajuan.php");
} else {
    $err = urlencode("Gagal menyimpan data pengajuan.");
    header("location: tambah_pengajuan.php?error=$err");
}
exit;
?>