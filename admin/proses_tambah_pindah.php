<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Verifikasi CSRF token untuk keamanan form
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $err = urlencode("Validasi keamanan gagal (Token CSRF tidak valid).");
    header("location: tambah_pindah.php?error=$err");
    exit;
}

// Menangkap data dari form
$id_penduduk    = (int)$_POST['id_penduduk'];
$tanggal_pindah = $_POST['tanggal_pindah'];
$alamat_tujuan  = trim($_POST['alamat_tujuan']);
$alasan_pindah  = trim($_POST['alasan_pindah']);

// Mulai Transaksi SQL (Memastikan 3 proses di bawah dieksekusi bersamaan dengan aman)
mysqli_begin_transaction($koneksi);

try {
    // 1. Insert ke tabel_pindah (sesuai dengan kolom alamat_tujuan milikmu)
    $stmt1 = mysqli_prepare($koneksi, "INSERT INTO tabel_pindah (id_penduduk, tanggal_pindah, alamat_tujuan, alasan_pindah) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt1, "isss", $id_penduduk, $tanggal_pindah, $alamat_tujuan, $alasan_pindah);
    mysqli_stmt_execute($stmt1);

    // 2. Ubah status warga di tabel_penduduk menjadi 'Pindah'
    $stmt2 = mysqli_prepare($koneksi, "UPDATE tabel_penduduk SET status = 'Pindah' WHERE id_penduduk = ?");
    mysqli_stmt_bind_param($stmt2, "i", $id_penduduk);
    mysqli_stmt_execute($stmt2);

    // 3. Hapus warga tersebut dari Kartu Keluarga (tabel_anggota)
    $stmt3 = mysqli_prepare($koneksi, "DELETE FROM tabel_anggota WHERE id_penduduk = ?");
    mysqli_stmt_bind_param($stmt3, "i", $id_penduduk);
    mysqli_stmt_execute($stmt3);

    // Simpan semua perubahan ke database secara permanen
    mysqli_commit($koneksi);

    // Kirim pesan sukses ke pop-up modal di pindah.php
    $_SESSION['sukses'] = "Data kepindahan berhasil dicatat! Status warga berubah jadi 'Pindah' dan otomatis dikeluarkan dari KK.";
    header("location: pindah.php");
    exit;

} catch (Exception $e) {
    // Jika ada satu saja proses yang gagal, batalkan semuanya
    mysqli_rollback($koneksi);
    $err = urlencode("Gagal mencatat data pindah: " . $e->getMessage());
    header("location: tambah_pindah.php?error=$err");
    exit;
}
?>