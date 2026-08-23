<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Menghubungkan ke database
$koneksi_path = __DIR__ . '/../koneksi.php';
if (file_exists($koneksi_path)) {
    include $koneksi_path;
} else {
    include '../backend/koneksi.php'; 
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
        /* Tambahan CSS khusus untuk Card Menu Laporan */
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        .report-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 30px 25px;
            text-align: center;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.08);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(33, 40, 50, 0.15);
            border-color: var(--primary);
        }
        .report-icon {
            width: 70px;
            height: 70px;
            background: rgba(0, 97, 242, 0.1);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }
        .report-card h3 {
            font-size: 18px;
            color: var(--text-main);
            margin-bottom: 10px;
        }
        .report-card p {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 25px;
        }
        .btn-print {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 10px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-print:hover {
            background: var(--primary-hover);
        }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- ================= SIDEBAR ================= -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2>
                <p>Kabupaten Malaka</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>Dashboard</span></a></li>
                <li><a href="data_penduduk.php"><i class="fas fa-users"></i> <span>Data Penduduk</span></a></li>
                <li><a href="data_kk.php"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
                <li><a href="kelahiran.php"><i class="fas fa-baby"></i> <span>Data Kelahiran</span></a></li>
                <li><a href="kematian.php"><i class="fas fa-book-dead"></i> <span>Data Kematian</span></a></li>
                <li><a href="laporan.php" class="active"><i class="fas fa-file-alt"></i> <span>Laporan</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="main-content">
            
            <!-- HEADER -->
            <div class="header">
                <h1>SISTEM INFORMASI DATA KEPENDUDUKAN</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>&background=0061f2&color=fff" alt="User Avatar">
                    <span>Halo, <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></strong></span>
                </div>
            </div>

            <!-- KONTEN UTAMA -->
            <div class="content-wrapper">
                
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-print"></i> Pusat Cetak Laporan Desa</h2>
                    <p style="margin-top: 5px;">Pilih jenis data yang ingin Anda cetak atau simpan sebagai dokumen fisik (PDF/Kertas).</p>
                </div>

                <div class="report-grid">
                    
                    <!-- Laporan Data Penduduk -->
                    <div class="report-card">
                        <div class="report-icon"><i class="fas fa-users"></i></div>
                        <h3>Data Penduduk</h3>
                        <p>Cetak daftar seluruh penduduk yang terdaftar aktif di desa.</p>
                        <!-- Mengarah ke file cetak yang sudah kita buat sebelumnya -->
                        <a href="cetak_laporan_penduduk.php" target="_blank" class="btn-print"><i class="fas fa-print"></i> Cetak Laporan</a>
                    </div>

                    <!-- Laporan Data Keluarga (KK) -->
                    <div class="report-card">
                        <div class="report-icon" style="background: rgba(0, 172, 105, 0.1); color: var(--success);"><i class="fas fa-id-card"></i></div>
                        <h3>Data Kartu Keluarga</h3>
                        <p>Cetak rekapitulasi daftar Kepala Keluarga beserta jumlah anggota.</p>
                        <!-- File ini belum kita buat, nanti akan kita kerjakan -->
                        <a href="cetak_laporan_kk.php" target="_blank" class="btn-print" style="background: var(--success);"><i class="fas fa-print"></i> Cetak Laporan</a>
                    </div>

                    <!-- Laporan Data Kelahiran -->
                    <div class="report-card">
                        <div class="report-icon" style="background: rgba(244, 161, 0, 0.1); color: var(--warning);"><i class="fas fa-baby"></i></div>
                        <h3>Data Kelahiran</h3>
                        <p>Cetak daftar catatan kelahiran bayi berdasarkan rentang waktu.</p>
                        <a href="cetak_laporan_kelahiran.php" target="_blank" class="btn-print" style="background: var(--warning);"><i class="fas fa-print"></i> Cetak Laporan</a>
                    </div>

                    <!-- Laporan Data Kematian -->
                    <div class="report-card">
                        <div class="report-icon" style="background: rgba(232, 21, 0, 0.1); color: var(--danger);"><i class="fas fa-book-dead"></i></div>
                        <h3>Data Kematian</h3>
                        <p>Cetak daftar catatan atau register kematian penduduk desa.</p>
                        <a href="cetak_laporan_kematian.php" target="_blank" class="btn-print" style="background: var(--danger);"><i class="fas fa-print"></i> Cetak Laporan</a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>
</html>