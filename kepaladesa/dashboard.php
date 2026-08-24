<?php
session_start();
// Cek apakah sudah login dan rolenya Kepala Desa
if (!isset($_SESSION['username']) || $_SESSION['status_pengguna'] != 'Kepala Desa') {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php';

// Mengambil ringkasan data untuk Kepala Desa
// Hanya menghitung penduduk yang masih 'Aktif' di desa
$q_penduduk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk WHERE status = 'Aktif'");
$d_penduduk = mysqli_fetch_assoc($q_penduduk);

$q_kk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kk");
$d_kk = mysqli_fetch_assoc($q_kk);

$q_lahir = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kelahiran");
$d_lahir = mysqli_fetch_assoc($q_lahir);

$q_mati = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kematian");
$d_mati = mysqli_fetch_assoc($q_mati);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Desa - Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/dashboard.css">
    <style>
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .stat-card { 
            background: white; 
            padding: 25px; 
            border-radius: 10px; 
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.08); 
            border-left: 5px solid #0061f2; 
            position: relative;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card h4 { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 10px; 
            position: relative;
            z-index: 2;
        }
        .stat-card h2 { 
            font-size: 28px; 
            color: #333; 
            margin: 0;
            position: relative;
            z-index: 2;
        }
        .stat-icon-bg {
            position: absolute;
            right: 15px;
            bottom: 10px;
            font-size: 60px;
            opacity: 0.1;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- SIDEBAR KEPALA DESA -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2>
                <p>KEPALA DESA</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-desktop"></i> <span>DASHBOARD</span></a></li>
                <li><a href="rekap_penduduk.php"><i class="fas fa-chart-bar"></i> <span>REKAPITULASI DATA</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>
                
                <li style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px;"></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="header">
                <h1>PANEL KEPALA DESA - AS MANULEA</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=198754&color=fff" alt="Avatar">
                    <span>Selamat Datang, <strong><?php echo isset($_SESSION['nama_pengguna']) ? htmlspecialchars($_SESSION['nama_pengguna']) : htmlspecialchars($_SESSION['username']); ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 20px 30px; margin-bottom: 25px;">
                    <h2><i class="fas fa-home"></i> Beranda Kepala Desa</h2>
                    <p style="margin-top:5px;">Ringkasan eksekutif dan statistik data kependudukan Desa As Manulea.</p>
                </div>

                <!-- KARTU STATISTIK -->
                <div class="stats-grid">
                    <div class="stat-card" style="border-left-color: #0061f2;">
                        <h4>Total Penduduk Aktif</h4>
                        <h2><?php echo isset($d_penduduk['total']) ? $d_penduduk['total'] : 0; ?> Jiwa</h2>
                        <i class="fas fa-users stat-icon-bg" style="color: #0061f2;"></i>
                    </div>
                    
                    <div class="stat-card" style="border-left-color: #198754;">
                        <h4>Kepala Keluarga (KK)</h4>
                        <h2><?php echo isset($d_kk['total']) ? $d_kk['total'] : 0; ?> KK</h2>
                        <i class="fas fa-id-card stat-icon-bg" style="color: #198754;"></i>
                    </div>
                    
                    <div class="stat-card" style="border-left-color: #ffc107;">
                        <h4>Data Kelahiran</h4>
                        <h2><?php echo isset($d_lahir['total']) ? $d_lahir['total'] : 0; ?> Bayi</h2>
                        <i class="fas fa-baby stat-icon-bg" style="color: #ffc107;"></i>
                    </div>
                    
                    <div class="stat-card" style="border-left-color: #dc3545;">
                        <h4>Data Kematian</h4>
                        <h2><?php echo isset($d_mati['total']) ? $d_mati['total'] : 0; ?> Jiwa</h2>
                        <i class="fas fa-book-dead stat-icon-bg" style="color: #dc3545;"></i>
                    </div>
                </div>

                <div class="card-table" style="padding: 25px;">
                    <h3><i class="fas fa-info-circle" style="color: #0061f2;"></i> Informasi Sistem</h3>
                    <p style="margin-top: 10px; color: #555; line-height: 1.6;">
                        Sebagai Kepala Desa, Anda memiliki akses untuk memantau rekapitulasi data kependudukan secara keseluruhan serta mencetak laporan rekapitulasi bulanan atau tahunan guna keperluan administrasi pemerintahan Desa As Manulea, Kecamatan Sasitamean, Kabupaten Malaka.
                    </p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>