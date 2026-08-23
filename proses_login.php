<?php
// Mengaktifkan session PHP
session_start();

// Menghubungkan dengan koneksi
include 'backend/koneksi.php';

// Menangkap data yang dikirim dari form login (menggunakan pengamanan anti SQL Injection dasar)
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// Menyeleksi data pengguna dengan username dan password yang sesuai
$login = mysqli_query($koneksi, "SELECT * FROM tabel_pengguna WHERE username='$username' AND password='$password'");

// Menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($login);

// Cek apakah username dan password di temukan pada database
if($cek > 0){
    $data = mysqli_fetch_assoc($login);

    // Menyimpan session
    $_SESSION['username'] = $username;
    $_SESSION['nama_pengguna'] = $data['nama_pengguna'];
    $_SESSION['status_pengguna'] = $data['status_pengguna'];

    // Cek hak akses dan arahkan (redirect) sesuai peran pengguna
    if($data['status_pengguna'] == "Admin"){
        // Admin: akses kelola semua data
        header("location:admin/dashboard.php");
        
    } else if($data['status_pengguna'] == "Kepala Desa"){
        // Kepala Desa: menerima laporan
        header("location:kepaladesa/dashboard.php");
        
    } else if($data['status_pengguna'] == "Kepala Dusun"){
        // Kepala Dusun: informasi & validasi
        header("location:dusun/dashboard.php");
        
    } else if($data['status_pengguna'] == "RT" || $data['status_pengguna'] == "RW"){
        // RT/RW: informasi & validasi
        header("location:rtrw/dashboard.php");
        
    } else {
        // Jika status tidak dikenali
        header("location:index.php?pesan=gagal");
    }
} else {
    // Jika tidak ada data yang cocok (Username/Password salah)
    header("location:index.php?pesan=gagal");
}
?>