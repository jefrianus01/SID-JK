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

// =========================================================
// QUERY UNTUK MENGAMBIL TOTAL DATA DARI DATABASE (HANYA YANG AKTIF)
// =========================================================
$q_penduduk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk WHERE status='Aktif'");
$jml_penduduk = $q_penduduk ? mysqli_fetch_assoc($q_penduduk)['total'] : 0;

$q_kk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kk");
$jml_kk = $q_kk ? mysqli_fetch_assoc($q_kk)['total'] : 0;

$q_lk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk WHERE jenis_kelamin='Laki-laki' AND status='Aktif'");
$jml_lk = $q_lk ? mysqli_fetch_assoc($q_lk)['total'] : 0;

$q_pr = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk WHERE jenis_kelamin='Perempuan' AND status='Aktif'");
$jml_pr = $q_pr ? mysqli_fetch_assoc($q_pr)['total'] : 0;

$q_lahir = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kelahiran");
$jml_lahir = $q_lahir ? mysqli_fetch_assoc($q_lahir)['total'] : 0;

$q_mati = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kematian");
$jml_mati = $q_mati ? mysqli_fetch_assoc($q_mati)['total'] : 0;

$q_pindah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_pindah");
$jml_pindah = $q_pindah ? mysqli_fetch_assoc($q_pindah)['total'] : 0;

$q_pendatang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_pendatang");
$jml_pendatang = $q_pendatang ? mysqli_fetch_assoc($q_pendatang)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrator - Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* CSS KHUSUS UNTUK DROPDOWN SIDEBAR */
        .submenu { display: none; list-style: none; padding-left: 20px; background: rgba(0, 0, 0, 0.05); margin-bottom: 5px; }
        .submenu.show { display: block; }
        .submenu li a { font-size: 14px; padding: 8px 15px; opacity: 0.8; }
        .submenu li a:hover { opacity: 1; padding-left: 20px; }
        .has-submenu > a { display: flex; justify-content: space-between; align-items: center; }
        .toggle-icon { font-size: 12px; transition: transform 0.3s ease; }
        .toggle-icon.rotate { transform: rotate(180deg); }
        .menu-divider { border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px; }
        
        /* CSS KARTU STATISTIK PREMIUM */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border-left: 5px solid transparent;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .stat-info h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #888;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-info h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
            font-weight: 700;
        }
        .stat-info h2 span {
            font-size: 14px;
            color: #999;
            font-weight: normal;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }
        /* Banner Welcome */
        .welcome-banner {
            background: linear-gradient(135deg, #0061f2 0%, #6610f2 100%);
            border-radius: 12px;
            padding: 30px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0, 97, 242, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome-text h2 { margin: 0 0 10px 0; font-size: 24px; }
        .welcome-text p { margin: 0; font-size: 15px; opacity: 0.9; line-height: 1.5; }
        .welcome-date { background: rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 8px; font-weight: bold; backdrop-filter: blur(5px); }
    </style>
</head>
<body>
    
    <div class="layout-container">
        
        <!-- ================= SIDEBAR ================= -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2>
                <p>ADMINISTRATOR</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-desktop"></i> <span>DASHBOARD</span></a></li>
                
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-database"></i> <span>KELOLA DATA</span> <i class="fas fa-chevron-down toggle-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="data_penduduk.php"><i class="fas fa-users"></i> Data Penduduk</a></li>
                        <li><a href="data_kk.php"><i class="fas fa-id-card"></i> Data KK</a></li>
                    </ul>
                </li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-sync-alt"></i> <span>SIRKULASI PENDUDUK</span> <i class="fas fa-chevron-down toggle-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="kelahiran.php"><i class="fas fa-baby"></i> Data Kelahiran</a></li>
                        <li><a href="kematian.php"><i class="fas fa-book-dead"></i> Data Kematian</a></li>
                        <li><a href="pindah.php"><i class="fas fa-truck-moving"></i> Data Pindah</a></li>
                        <li><a href="pendatang.php"><i class="fas fa-suitcase-rolling"></i> Data Pendatang</a></li>
                    </ul>
                </li>

                <li><a href="data_pengajuan.php"><i class="fas fa-file-signature"></i> <span>DATA PENGAJUAN</span></a></li>
                <li><a href="master_data.php"><i class="fas fa-server"></i> <span>MASTER DATA</span></a></li>
                <li><a href="data_dusun.php"><i class="fas fa-map-marker-alt"></i> <span>DATA DUSUN/RT</span></a></li>
                <li><a href="data_profil.php"><i class="fas fa-id-badge"></i> <span>DATA PROFIL</span></a></li>
                
                <li><a href="laporan.php"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>

                <li class="menu-divider"></li>

                <li><a href="setting.php"><i class="fas fa-cog"></i> <span>Setting</span></a></li>
                <li><a href="pengguna.php"><i class="fas fa-user-cog"></i> <span>PENGGUNA SISTEM</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span></a></li>
            </ul>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="main-content">
            
            <div class="header">
                <h1>SISTEM INFORMASI DATA PENDUDUK DESA AS MANULEA</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>&background=0061f2&color=fff" alt="User Avatar">
                    <span>Halo, <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                
                <!-- BANNER SELAMAT DATANG -->
                <div class="welcome-banner">
                    <div class="welcome-text">
                        <h2>Selamat Datang di Panel Admin, <?php echo isset($_SESSION['username']) ? ucfirst($_SESSION['username']) : 'Admin'; ?>!</h2>
                        <p>Berikut adalah ringkasan data kependudukan dan sirkulasi warga Desa As Manulea secara *real-time*.</p>
                    </div>
                    <div class="welcome-date">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d F Y'); ?>
                    </div>
                </div>

                <!-- 8 KOTAK DATA (GRID SYSTEM) -->
                <div class="dashboard-grid">
                    
                    <!-- KARTU 1: PENDUDUK -->
                    <div class="stat-card" style="border-left-color: #0061f2;">
                        <div class="stat-info">
                            <h4>Total Penduduk</h4>
                            <h2><?php echo $jml_penduduk; ?> <span>Jiwa</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(0, 97, 242, 0.1); color: #0061f2;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    
                    <!-- KARTU 2: KK -->
                    <div class="stat-card" style="border-left-color: #6610f2;">
                        <div class="stat-info">
                            <h4>Kepala Keluarga</h4>
                            <h2><?php echo $jml_kk; ?> <span>KK</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(102, 16, 242, 0.1); color: #6610f2;">
                            <i class="fas fa-id-card"></i>
                        </div>
                    </div>

                    <!-- KARTU 3: LAKI-LAKI -->
                    <div class="stat-card" style="border-left-color: #0dcaf0;">
                        <div class="stat-info">
                            <h4>Laki-laki</h4>
                            <h2><?php echo $jml_lk; ?> <span>Jiwa</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(13, 202, 240, 0.1); color: #0dcaf0;">
                            <i class="fas fa-male"></i>
                        </div>
                    </div>

                    <!-- KARTU 4: PEREMPUAN -->
                    <div class="stat-card" style="border-left-color: #d63384;">
                        <div class="stat-info">
                            <h4>Perempuan</h4>
                            <h2><?php echo $jml_pr; ?> <span>Jiwa</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(214, 51, 132, 0.1); color: #d63384;">
                            <i class="fas fa-female"></i>
                        </div>
                    </div>

                    <!-- KARTU 5: KELAHIRAN -->
                    <div class="stat-card" style="border-left-color: #198754;">
                        <div class="stat-info">
                            <h4>Kelahiran</h4>
                            <h2><?php echo $jml_lahir; ?> <span>Data</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(25, 135, 84, 0.1); color: #198754;">
                            <i class="fas fa-baby"></i>
                        </div>
                    </div>
                    
                    <!-- KARTU 6: KEMATIAN -->
                    <div class="stat-card" style="border-left-color: #dc3545;">
                        <div class="stat-info">
                            <h4>Kematian</h4>
                            <h2><?php echo $jml_mati; ?> <span>Data</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                            <i class="fas fa-book-dead"></i>
                        </div>
                    </div>

                    <!-- KARTU 7: PINDAH -->
                    <div class="stat-card" style="border-left-color: #fd7e14;">
                        <div class="stat-info">
                            <h4>Pindah Keluar</h4>
                            <h2><?php echo $jml_pindah; ?> <span>Data</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(253, 126, 20, 0.1); color: #fd7e14;">
                            <i class="fas fa-truck-moving"></i>
                        </div>
                    </div>

                    <!-- KARTU 8: PENDATANG -->
                    <div class="stat-card" style="border-left-color: #20c997;">
                        <div class="stat-info">
                            <h4>Pendatang Masuk</h4>
                            <h2><?php echo $jml_pendatang; ?> <span>Data</span></h2>
                        </div>
                        <div class="stat-icon" style="background: rgba(32, 201, 151, 0.1); color: #20c997;">
                            <i class="fas fa-suitcase-rolling"></i>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- SCRIPT UNTUK MENGAKTIFKAN MENU DROPDOWN -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toggles = document.querySelectorAll('.submenu-toggle');
            toggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    var submenu = this.nextElementSibling;
                    var icon = this.querySelector('.toggle-icon');
                    
                    submenu.classList.toggle('show');
                    icon.classList.toggle('rotate');
                });
            });
        });
    </script>
</body>
</html>