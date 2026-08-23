<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap ID dari URL
$id_penduduk = $_GET['id_penduduk'];

// Query untuk menghapus data
$query = "DELETE FROM tabel_penduduk WHERE id_penduduk='$id_penduduk'";

if (mysqli_query($koneksi, $query)) {
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