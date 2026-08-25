<?php
session_start();
if (!isset($_SESSION['username'])) { 
    header("location: ../index.php"); 
    exit; 
}
include '../backend/koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_dusun = trim($_POST['nama_dusun']);
    $nama_kadus = trim($_POST['nama_kadus']);
    $jumlah_rt  = (int)$_POST['jumlah_rt'];

    if (!empty($nama_dusun)) {
        // PERHATIKAN: Ganti 'nama_dusun', 'nama_kadus', 'jumlah_rt' di bawah ini 
        // jika kolom di phpMyAdminmu menggunakan nama lain (misal: 'nama', 'kepala_dusun', dll)
        $stmt = mysqli_prepare($koneksi, "INSERT INTO tabel_dusun (nama_dusun, nama_kadus, jumlah_rt) VALUES (?, ?, ?)");
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssi", $nama_dusun, $nama_kadus, $jumlah_rt);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['sukses'] = "Data Dusun berhasil ditambahkan!";
                header("location: data_dusun.php");
                exit;
            } else {
                $err = urlencode("Gagal menyimpan data dusun ke database.");
                header("location: data_dusun.php?error=$err");
                exit;
            }
        } else {
            $err = urlencode("Error Query: " . mysqli_error($koneksi));
            header("location: data_dusun.php?error=$err");
            exit;
        }
    } else {
        $err = urlencode("Nama dusun tidak boleh kosong!");
        header("location: data_dusun.php?error=$err");
        exit;
    }
} else {
    header("location: data_dusun.php");
    exit;
}
?>