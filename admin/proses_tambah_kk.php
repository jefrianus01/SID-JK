<?php
session_start();

// Pengecekan sesi login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Menghubungkan ke database
$koneksi_path = __DIR__ . '/../koneksi.php';
if (file_exists($koneksi_path)) {
    include $koneksi_path;
} else {
    include 'backend/koneksi.php'; 
}

// Menangkap data yang dikirim dari form (dilengkapi dengan pengamanan dasar string)
$id_kepala_desa = mysqli_real_escape_string($koneksi, $_POST['id_kepala_desa']);
$no_kk          = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
$nama           = mysqli_real_escape_string($koneksi, $_POST['nama']);
$desa           = mysqli_real_escape_string($koneksi, $_POST['desa']);
$kecamatan      = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
$kabupaten      = mysqli_real_escape_string($koneksi, $_POST['kabupaten']);
$provinsi       = mysqli_real_escape_string($koneksi, $_POST['provinsi']);
$bantuan        = mysqli_real_escape_string($koneksi, $_POST['bantuan']);

// Query untuk memasukkan data ke tabel_kk sesuai kamus data
$query = "INSERT INTO tabel_kk (id_kepala_desa, no_kk, nama, desa, kecamatan, kabupaten, provinsi, bantuan) 
          VALUES ('$id_kepala_desa', '$no_kk', '$nama', '$desa', '$kecamatan', '$kabupaten', '$provinsi', '$bantuan')";

// Eksekusi query
if (mysqli_query($koneksi, $query)) {
    // Jika berhasil, arahkan kembali ke halaman data_kk.php
    echo "<script>
            alert('Data Keluarga berhasil ditambahkan!');
            window.location.href = 'data_kk.php';
          </script>";
} else {
    // Jika gagal, tampilkan pesan error SQL-nya untuk proses debugging
    echo "<script>
            alert('Gagal menambahkan data');
            window.location.href = 'tambah_kk.php';
          </script>";
}
?>