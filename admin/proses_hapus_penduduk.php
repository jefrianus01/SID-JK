<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verifikasi CSRF token
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token invalid!");
}

include '../backend/koneksi.php';

// Menangkap ID dari POST
$id_penduduk = $_POST['id_penduduk'];

// Query untuk menghapus data menggunakan prepared statement
$stmt = mysqli_prepare($koneksi, "DELETE FROM tabel_penduduk WHERE id_penduduk = ?");
mysqli_stmt_bind_param($stmt, "i", $id_penduduk);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = 'data_penduduk.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus data');
            window.location.href = 'data_penduduk.php';
          </script>";
}
?>