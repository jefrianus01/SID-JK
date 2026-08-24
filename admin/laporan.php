<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Laporan - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .report-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 5px solid #0061f2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: transform 0.2s;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        .report-info h3 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 18px;
        }
        .report-info p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }
        .btn-print {
            background-color: #0061f2;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s;
        }
        .btn-print:hover {
            background-color: #004ecc;
        }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header"><div class="logo"><i class="fas fa-landmark"></i></div><h2>Desa As Manulea</h2><p>Kab. Malaka</p></div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>Dashboard</span></a></li>
                <li><a href="data_penduduk.php"><i class="fas fa-users"></i> <span>Data Penduduk</span></a></li>
                <li><a href="data_kk.php"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
                <li><a href="kelahiran.php"><i class="fas fa-baby"></i> <span>Data Kelahiran</span></a></li>
                <li><a href="kematian.php"><i class="fas fa-book-dead"></i> <span>Data Kematian</span></a></li>
                <li><a href="pindah.php"><i class="fas fa-truck-moving"></i> <span>Data Pindah</span></a></li>
                <li><a href="pendatang.php"><i class="fas fa-suitcase-rolling"></i> <span>Data Pendatang</span></a></li>
                <li><a href="laporan.php" class="active"><i class="fas fa-file-alt"></i> <span>Laporan</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>SISTEM INFORMASI DATA KEPENDUDUKAN</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=0061f2&color=fff" alt="Avatar">
                    <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-file-alt"></i> Pusat Cetak Laporan Desa</h2>
                    <p style="color: #666; margin-top: 5px;">Pilih jenis laporan yang ingin Anda cetak atau simpan sebagai PDF.</p>
                </div>

                <div class="report-grid">
                    <!-- Card Laporan Penduduk -->
                    <div class="report-card">
                        <div class="report-info">
                            <h3>Laporan Data Penduduk</h3>
                            <p>Seluruh daftar warga berstatus aktif.</p>
                        </div>
                        <!-- target="_blank" agar terbuka di tab baru saat di-print -->
                        <a href="cetak_laporan_penduduk.php" target="_blank" class="btn-print"><i class="fas fa-print"></i> Cetak</a>
                    </div>

                    <!-- Card Laporan KK -->
                    <div class="report-card">
                        <div class="report-info">
                            <h3>Laporan Kartu Keluarga</h3>
                            <p>Daftar seluruh Kepala Keluarga & No KK.</p>
                        </div>
                        <a href="cetak_laporan_kk.php" target="_blank" class="btn-print"><i class="fas fa-print"></i> Cetak</a>
                    </div>

                    <!-- Card Laporan Kelahiran -->
                    <div class="report-card" style="border-left-color: #28a745;">
                        <div class="report-info">
                            <h3>Laporan Kelahiran</h3>
                            <p>Data bayi lahir di Desa As Manulea.</p>
                        </div>
                        <a href="cetak_laporan_kelahiran.php" target="_blank" class="btn-print" style="background-color: #28a745;"><i class="fas fa-print"></i> Cetak</a>
                    </div>

                    <!-- Card Laporan Kematian -->
                    <div class="report-card" style="border-left-color: #dc3545;">
                        <div class="report-info">
                            <h3>Laporan Kematian</h3>
                            <p>Data warga yang telah meninggal dunia.</p>
                        </div>
                        <a href="cetak_laporan_kematian.php" target="_blank" class="btn-print" style="background-color: #dc3545;"><i class="fas fa-print"></i> Cetak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>