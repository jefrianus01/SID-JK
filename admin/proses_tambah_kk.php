<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: tambah_kk.php?error=$err");
    exit;
}

$id_kepala_desa = (int)$_POST['id_kepala_desa'];
$no_kk          = trim($_POST['no_kk']);
$nama           = trim($_POST['nama']); // Nama Kepala Keluarga
$desa           = trim($_POST['desa']);
$kecamatan      = trim($_POST['kecamatan']);
$kabupaten      = trim($_POST['kabupaten']);
$provinsi       = trim($_POST['provinsi']);
$bantuan        = trim($_POST['bantuan']);

// Jumlah anggota otomatis 0 saat KK pertama kali dibuat (Sistem Pintar)
$jumlah_anggota = 0; 

$stmt_cek = mysqli_prepare($koneksi, "SELECT id_kk FROM tabel_kk WHERE no_kk = ?");
mysqli_stmt_bind_param($stmt_cek, "s", $no_kk);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);

if (mysqli_num_rows($result_cek) > 0) {
    $err = urlencode("Nomor KK " . $no_kk . " sudah terdaftar di sistem!");
    header("location: tambah_kk.php?error=$err");
    exit;
}

$stmt = mysqli_prepare($koneksi, "INSERT INTO tabel_kk (id_kepala_desa, no_kk, nama, jumlah_anggota, desa, kecamatan, kabupaten, provinsi, bantuan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ississsss", $id_kepala_desa, $no_kk, $nama, $jumlah_anggota, $desa, $kecamatan, $kabupaten, $provinsi, $bantuan);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['sukses'] = "Wadah Kartu Keluarga berhasil dibuat! Silakan klik 'Detail' untuk memasukkan anggota.";
    header("location: data_kk.php");
    exit;
} else {
    $err = urlencode("Gagal menambahkan data KK.");
    header("location: tambah_kk.php?error=$err");
    exit;
}
?>