<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Menghubungkan dengan database
include '../backend/koneksi.php';

// Verifikasi CSRF token untuk keamanan
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: data_kk.php?error=$err");
    exit;
}

// Menangkap ID KK yang akan dihapus
$id_kk = (int)$_POST['id_kk'];

// Cek apakah KK masih memiliki anggota terkait di tabel_anggota
$stmt_cek = mysqli_prepare($koneksi, "SELECT COUNT(*) as jml FROM tabel_anggota WHERE id_kk = ?");
mysqli_stmt_bind_param($stmt_cek, "i", $id_kk);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);
$cek_data = mysqli_fetch_assoc($result_cek);

// Jika masih ada anggota, tolak penghapusan!
if ($cek_data['jml'] > 0) {
    $err = urlencode("Gagal! Tidak dapat menghapus KK karena masih memiliki " . $cek_data['jml'] . " anggota. Silakan pindahkan atau hapus data anggota terlebih dahulu.");
    header("location: data_kk.php?error=$err");
    exit;
}

// Jika aman (tidak ada anggota), lakukan proses Delete menggunakan prepared statement
$stmt = mysqli_prepare($koneksi, "DELETE FROM tabel_kk WHERE id_kk = ?");
mysqli_stmt_bind_param($stmt, "i", $id_kk);
mysqli_stmt_execute($stmt);

// Cek apakah data berhasil dihapus
if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['sukses'] = "Data Kartu Keluarga berhasil dihapus!";
    header("location: data_kk.php");
    exit;
} else {
    $err = urlencode("Terjadi kesalahan sistem, gagal menghapus data Keluarga.");
    header("location: data_kk.php?error=$err");
    exit;
}
?>