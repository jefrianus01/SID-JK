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
// QUERY UNTUK MENGAMBIL TOTAL DATA DARI DATABASE
// =========================================================
$q_penduduk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk");
$jml_penduduk = mysqli_fetch_assoc($q_penduduk)['total'];

$q_kk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kk");
$jml_kk = mysqli_fetch_assoc($q_kk)['total'];

$q_lk = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk WHERE jenis_kelamin='Laki-laki'");
$jml_lk = mysqli_fetch_assoc($q_lk)['total'];

$q_pr = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_penduduk WHERE jenis_kelamin='Perempuan'");
$jml_pr = mysqli_fetch_assoc($q_pr)['total'];

$q_lahir = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kelahiran");
$jml_lahir = $q_lahir ? mysqli_fetch_assoc($q_lahir)['total'] : 0;

$q_mati = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_kematian");
$jml_mati = $q_mati ? mysqli_fetch_assoc($q_mati)['total'] : 0;

// Data Pindah
$q_pindah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tabel_pindah");
$jml_pindah = $q_pindah ? mysqli_fetch_assoc($q_pindah)['total'] : 0;

// Data Pendatang
$jml_pendatang = 0; 
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
        .submenu {
            display: none;
            list-style: none;
            padding-left: 20px;
            background: rgba(0, 0, 0, 0.05);
            margin-bottom: 5px;
        }
        .submenu.show {
            display: block;
        }
        .submenu li a {
            font-size: 14px;
            padding: 8px 15px;
            opacity: 0.8;
        }
        .submenu li a:hover {
            opacity: 1;
            padding-left: 20px;
        }
        .has-submenu > a {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .toggle-icon {
            font-size: 12px;
            transition: transform 0.3s ease;
        }
        .toggle-icon.rotate {
            transform: rotate(180deg);
        }
        /* Pemisah menu bawah */
        .menu-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 15px 0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    
    <div class="layout-container">
        
        <!-- ================= SIDEBAR ================= -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <!-- Sesuai gambar: LOGO DESA -->
                <h2>Desa As Manulea</h2>
                <p>ADMIN</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-desktop"></i> <span>DASHBOARD</span></a></li>
                
                <!-- MENU 1: KELOLA DATA (Dropdown) -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-database"></i> <span>KELOLA DATA</span> <i class="fas fa-chevron-down toggle-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="data_penduduk.php"><i class="fas fa-users"></i> Data Penduduk</a></li>
                        <li><a href="data_kk.php"><i class="fas fa-id-card"></i> Data KK</a></li>
                    </ul>
                </li>

                <!-- MENU 2: SIRKULASI PENDUDUK (Dropdown) -->
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-sync-alt"></i> <span>SIRKULASI PENDUDUK</span> <i class="fas fa-chevron-down toggle-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="kelahiran.php"><i class="fas fa-baby"></i> Data Kelahiran</a></li>
                        <li><a href="kematian.php"><i class="fas fa-book-dead"></i> Data Kematian</a></li>
                        <li><a href="pindah.php"><i class="fas fa-truck-moving"></i> Data Pindah</a></li>
                        <li><a href="pendatang.php"><i class="fas fa-suitcase-rolling"></i> Data Pendatang</a></li>
                    </ul>
                </li>

                <!-- MENU 3: LAINNYA -->
                <li><a href="data_pengajuan.php"><i class="fas fa-file-signature"></i> <span>DATA PENGAJUAN</span></a></li>
                <li><a href="master_data.php"><i class="fas fa-server"></i> <span>MASTER DATA</span></a></li>
                <li><a href="data_dusun.php"><i class="fas fa-map-marker-alt"></i> <span>DATA DUSUN/RT</span></a></li>
                <li><a href="data_profil.php"><i class="fas fa-id-badge"></i> <span>DATA PROFIL</span></a></li>
                
                <!-- PUSAT LAPORAN (Sesuai yg kita buat tadi, ditaruh di bawah) -->
                <li><a href="laporan.php"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>

                <li class="menu-divider"></li>

                <!-- MENU 4: PENGATURAN & LOGOUT -->
                <li><a href="setting.php"><i class="fas fa-cog"></i> <span>Setting</span></a></li>
                <li><a href="pengguna.php"><i class="fas fa-user-cog"></i> <span>PENGGUNA SISTEM</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span></a></li>
            </ul>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="main-content">
            
            <div class="header">
                <!-- Sesuai dengan Header Atas di Gambar -->
                <h1>SISTEM INFORMASI DATA PENDUDUK DESA AS MANULEA</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>&background=0061f2&color=fff" alt="User Avatar">
                    <span>Halo, <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-desktop"></i> Halaman Dashboard Admin</h2>
                </div>

                <!-- 8 KOTAK DATA (Sesuai Gambar) -->
                <div class="quick-actions">
                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Penduduk</h3>
                            <p><?php echo $jml_penduduk; ?></p>
                        </div>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah KK</h3>
                            <p><?php echo $jml_kk; ?></p>
                        </div>
                    </div>

                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Laki-laki</h3>
                            <p><?php echo $jml_lk; ?></p>
                        </div>
                    </div>

                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Perempuan</h3>
                            <p><?php echo $jml_pr; ?></p>
                        </div>
                    </div>
                </div>

                <div class="quick-actions">
                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Lahir</h3>
                            <p><?php echo $jml_lahir; ?></p>
                        </div>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Kematian</h3>
                            <p><?php echo $jml_mati; ?></p>
                        </div>
                    </div>

                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Kematian</h3> <!-- Typo bawaan dari gambar tetap dipertahankan / bisa diubah ke Pindah -->
                            <p><?php echo $jml_pindah; ?></p>
                        </div>
                    </div>

                    <div class="quick-action-card">
                        <div class="card-info">
                            <h3>Jumlah Pendatang</h3>
                            <p><?php echo $jml_pendatang; ?></p>
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
                    // Ambil elemen submenu di bawahnya
                    var submenu = this.nextElementSibling;
                    var icon = this.querySelector('.toggle-icon');
                    
                    // Toggle class untuk menampilkan/menyembunyikan
                    submenu.classList.toggle('show');
                    icon.classList.toggle('rotate');
                });
            });
        });
    </script>
</body>
</html>