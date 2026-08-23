<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data
$id_penduduk      = mysqli_real_escape_string($koneksi, $_POST['id_penduduk']);
$tanggal_kematian = mysqli_real_escape_string($koneksi, $_POST['tanggal_kematian']);
$sebab            = mysqli_real_escape_string($koneksi, $_POST['sebab']);
$nama_pelapor     = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']);

// ========================================================
// TUGAS 1: Masukkan data ke tabel_kematian
// ========================================================
$query_insert = "INSERT INTO tabel_kematian (id_penduduk, tanggal_kematian, sebab, nama_pelapor) 
                 VALUES ('$id_penduduk', '$tanggal_kematian', '$sebab', '$nama_pelapor')";

if (mysqli_query($koneksi, $query_insert)) {
    
    // ========================================================
    // TUGAS 2: Jika insert berhasil, update status di tabel_penduduk
    // ========================================================
    $query_update = "UPDATE tabel_penduduk SET status = 'Meninggal' WHERE id_penduduk = '$id_penduduk'";
    mysqli_query($koneksi, $query_update);

    echo "<script>
            alert('Register Kematian berhasil dicatat! Status kependudukan telah diubah menjadi Meninggal.');
            window.location.href = 'kematian.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal mencatat kematian');
            window.location.href = 'tambah_kematian.php';
          </script>";
}
?>