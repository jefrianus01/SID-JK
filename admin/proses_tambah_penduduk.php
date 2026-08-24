<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Menangkap dan mengamankan data
$id_kepala_desa    = mysqli_real_escape_string($koneksi, $_POST['id_kepala_desa']);
$nik               = mysqli_real_escape_string($koneksi, $_POST['nik']);
$nama              = mysqli_real_escape_string($koneksi, $_POST['nama']);
$tempat_lahir      = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
$tanggal_lahir     = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
$jenis_kelamin     = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
$agama             = mysqli_real_escape_string($koneksi, $_POST['agama']);
$status_perkawinan = mysqli_real_escape_string($koneksi, $_POST['status_perkawinan']);
$pendidikan        = mysqli_real_escape_string($koneksi, $_POST['pendidikan']);
$pekerjaan         = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
$status            = mysqli_real_escape_string($koneksi, $_POST['status']);

// Validasi: Cek apakah NIK sudah terdaftar
$cek_nik = mysqli_query($koneksi, "SELECT nik FROM tabel_penduduk WHERE nik = '$nik'");
if ($cek_nik !== false && mysqli_num_rows($cek_nik) > 0) {
    echo "<script>
            alert('NIK " . addslashes($nik) . " sudah terdaftar di sistem! Silakan gunakan NIK lain.');
            window.location.href = 'tambah_penduduk.php';
          </script>";
    exit;
}

// Validasi: Cek format NIK (harus 16 digit)
if (!preg_match('/^[0-9]{16}$/', $nik)) {
    echo "<script>
            alert('Format NIK salah! NIK harus terdiri dari 16 digit angka.');
            window.location.href = 'tambah_penduduk.php';
          </script>";
    exit;
}

// Validasi: Cek data kepala desa valid
$cek_kades = mysqli_query($koneksi, "SELECT id FROM tabel_kepala_desa WHERE id = '$id_kepala_desa'");
if ($cek_kades !== false && mysqli_num_rows($cek_kades) == 0) {
    echo "<script>
            alert('Data Kepala Desa tidak valid! ID: " . addslashes($id_kepala_desa) . " tidak ditemukan.');
            window.location.href = 'tambah_penduduk.php';
          </script>";
    exit;
}

// Query Insert
$query = "INSERT INTO tabel_penduduk 
          (id_kepala_desa, nik, nama, tanggal_lahir, jenis_kelamin, tempat_lahir, agama, status_perkawinan, pendidikan, pekerjaan, status) 
          VALUES 
          ('$id_kepala_desa', '$nik', '$nama', '$tanggal_lahir', '$jenis_kelamin', '$tempat_lahir', '$agama', '$status_perkawinan', '$pendidikan', '$pekerjaan', '$status')";

if (mysqli_query($koneksi, $query)) {
    echo "<script>
            alert('Data Penduduk berhasil ditambahkan!');
            window.location.href = 'data_penduduk.php';
          </script>";
} else {
    // Debug: catat error untuk pengembang (tidak dilihat user akhir)
    error_log("Gagal insert penduduk: " . mysqli_error($koneksi));
    echo "<script>
            alert('Gagal menambahkan data ke database');
            window.location.href = 'tambah_penduduk.php';
          </script>";
}
?>