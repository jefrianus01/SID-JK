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
    header("location: tambah_kematian.php?error=$err");
    exit;
}

$id_penduduk      = (int)$_POST['id_penduduk'];
$tanggal_kematian = $_POST['tanggal_kematian'];
$penyebab         = trim($_POST['penyebab']);
$tempat_kematian  = trim($_POST['tempat_kematian']);

// Mulai Transaksi SQL (Agar jika satu gagal, gagal semua. Menjaga data tetap akurat)
mysqli_begin_transaction($koneksi);

try {
    // 1. Masukkan ke tabel_kematian
    $stmt1 = mysqli_prepare($koneksi, "INSERT INTO tabel_kematian (id_penduduk, tanggal_kematian, penyebab, tempat_kematian) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt1, "isss", $id_penduduk, $tanggal_kematian, $penyebab, $tempat_kematian);
    mysqli_stmt_execute($stmt1);

    // 2. Ubah status di tabel_penduduk menjadi 'Meninggal'
    $stmt2 = mysqli_prepare($koneksi, "UPDATE tabel_penduduk SET status = 'Meninggal' WHERE id_penduduk = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id_penduduk);
    mysqli_stmt_execute($stmt2);

    // 3. Hapus data orang ini dari Kartu Keluarga (tabel_anggota) agar tidak muncul lagi di detail KK
    $stmt3 = mysqli_prepare($koneksi, "DELETE FROM tabel_anggota WHERE id_penduduk = ?");
    mysqli_stmt_bind_param($stmt3, "i", $id_penduduk);
    mysqli_stmt_execute($stmt3);

    // Simpan semua perubahan secara permanen
    mysqli_commit($koneksi);

    $_SESSION['sukses'] = "Data kematian berhasil disimpan. Status penduduk telah diperbarui dan data dikeluarkan dari Kartu Keluarga.";
    header("location: kematian.php");
    exit;

} catch (Exception $e) {
    // Jika ada error di tengah jalan, batalkan semua perintah!
    mysqli_rollback($koneksi);
    $err = urlencode("Gagal memproses data: " . $e->getMessage());
    header("location: tambah_kematian.php?error=$err");
    exit;
}
?>