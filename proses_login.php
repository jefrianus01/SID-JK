<?php
session_start();
include 'backend/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Menggunakan prepared statement untuk keamanan login
    // Pastikan tabel database untuk login bernama 'tabel_pengguna' atau 'user' 
    // dan kolomnya bernama 'username' serta 'password'
    $stmt = mysqli_prepare($koneksi, "SELECT * FROM tabel_pengguna WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Cek password (bisa menggunakan password_verify jika di-hash, atau perbandingan biasa jika plain text)
        if ($password === $row['password'] || password_verify($password, $row['password'])) {
            
            // Set session login
            $_SESSION['username'] = $row['username'];
            $_SESSION['status_pengguna'] = isset($row['status_pengguna']) ? $row['status_pengguna'] : 'Admin';
            
            // Arahkan berdasarkan peran (Opsional)
            if($_SESSION['status_pengguna'] == 'Kepala Desa') {
                header("location: kepaladesa/dashboard.php");
            } else {
                header("location: admin/dashboard.php");
            }
            exit;
        } else {
            $err = urlencode("Password salah!");
            header("location: index.php?error=$err");
            exit;
        }
    } else {
        $err = urlencode("Username tidak ditemukan!");
        header("location: index.php?error=$err");
        exit;
    }
}
?>