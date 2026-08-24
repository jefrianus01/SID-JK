<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Verifikasi CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: edit_kk.php?id_kk=" . (int)$_POST['id_kk'] . "&error=$err");
    exit;
}

// Menangkap data
$id_kk          = (int)$_POST['id_kk'];
$id_kepala_desa = (int)$_POST['id_kepala_desa'];
$no_kk          = trim($_POST['no_kk']);
$nama           = trim($_POST['nama']);
$jumlah_anggota = (int)$_POST['jumlah_anggota'];
$desa           = trim($_POST['desa']);
$kecamatan      = trim($_POST['kecamatan']);
$kabupaten      = trim($_POST['kabupaten']);
$provinsi       = trim($_POST['provinsi']);
$bantuan        = trim($_POST['bantuan']);

// Cek apakah Nomor KK sudah digunakan oleh data KK lain
$stmt_cek = mysqli_prepare($koneksi, "SELECT id_kk FROM tabel_kk WHERE no_kk = ? AND id_kk != ?");
mysqli_stmt_bind_param($stmt_cek, "si", $no_kk, $id_kk);
mysqli_stmt_execute($stmt_cek);
$result_cek = mysqli_stmt_get_result($stmt_cek);

if (mysqli_num_rows($result_cek) > 0) {
    $err = urlencode("Nomor KK " . $no_kk . " sudah terdaftar pada data keluarga lain!");
    header("location: edit_kk.php?id_kk=$id_kk&error=$err");
    exit;
}

// Query Update menggunakan prepared statement
$stmt = mysqli_prepare($koneksi, "UPDATE tabel_kk SET id_kepala_desa = ?, no_kk = ?, nama = ?, jumlah_anggota = ?, desa = ?, kecamatan = ?, kabupaten = ?, provinsi = ?, bantuan = ? WHERE id_kk = ?");
mysqli_stmt_bind_param($stmt, "ississsssi", $id_kepala_desa, $no_kk, $nama, $jumlah_anggota, $desa, $kecamatan, $kabupaten, $provinsi, $bantuan, $id_kk);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) >= 0) {
    $_SESSION['sukses'] = "Data Keluarga berhasil diperbarui!";
    header("location: data_kk.php");
    exit;
} else {
    $err = urlencode("Gagal memperbarui data Keluarga.");
    header("location: edit_kk.php?id_kk=$id_kk&error=$err");
    exit;
}
?>