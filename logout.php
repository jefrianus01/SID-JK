<?php
// Memulai session
session_start();

// Menghapus semua variabel session
session_unset();

// Menghancurkan session
session_destroy();

// Mengarahkan kembali ke halaman utama / login
header("location: index.php");
exit;
?>