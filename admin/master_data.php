<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 

$pesan = "";

// PROSES TAMBAH DATA MASTER
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah_agama'])) {
        $agama = mysqli_real_escape_string($koneksi, $_POST['nama_agama']);
        if(!empty($agama)) {
            mysqli_query($koneksi, "INSERT INTO tabel_master_agama (nama_agama) VALUES ('$agama')");
            header("location: master_data.php");
            exit;
        }
    }
    if (isset($_POST['tambah_pekerjaan'])) {
        $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['nama_pekerjaan']);
        if(!empty($pekerjaan)) {
            mysqli_query($koneksi, "INSERT INTO tabel_master_pekerjaan (nama_pekerjaan) VALUES ('$pekerjaan')");
            header("location: master_data.php");
            exit;
        }
    }
    if (isset($_POST['tambah_pendidikan'])) {
        $pendidikan = mysqli_real_escape_string($koneksi, $_POST['nama_pendidikan']);
        if(!empty($pendidikan)) {
            mysqli_query($koneksi, "INSERT INTO tabel_master_pendidikan (nama_pendidikan) VALUES ('$pendidikan')");
            header("location: master_data.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .submenu { display: none; list-style: none; padding-left: 20px; background: rgba(0, 0, 0, 0.05); margin-bottom: 5px; }
        .submenu.show { display: block; }
        .submenu li a { font-size: 14px; padding: 8px 15px; opacity: 0.8; }
        .submenu li a:hover { opacity: 1; padding-left: 20px; }
        .has-submenu > a { display: flex; justify-content: space-between; align-items: center; }
        .toggle-icon { font-size: 12px; transition: transform 0.3s ease; }
        .toggle-icon.rotate { transform: rotate(180deg); }
        .menu-divider { border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px; }
        
        /* Grid Layout untuk 3 Kotak Master */
        .master-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-top: 20px; }
        .master-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.08); border: 1px solid #e3e6ec; }
        .master-card h3 { font-size: 16px; margin-bottom: 15px; color: #333; border-bottom: 2px solid #f0f0f0; padding-bottom: 8px; }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2>
                <p>ADMIN</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>DASHBOARD</span></a></li>
                
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
                <li><a href="master_data.php" class="active"><i class="fas fa-server"></i> <span>MASTER DATA</span></a></li>
                <li><a href="data_dusun.php"><i class="fas fa-map-marker-alt"></i> <span>DATA DUSUN/RT</span></a></li>
                <li><a href="data_profil.php"><i class="fas fa-id-badge"></i> <span>DATA PROFIL</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>

                <li class="menu-divider"></li>

                <li><a href="setting.php"><i class="fas fa-cog"></i> <span>Setting</span></a></li>
                <li><a href="pengguna.php"><i class="fas fa-user-cog"></i> <span>PENGGUNA SISTEM</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="header">
                <h1>SISTEM INFORMASI DATA PENDUDUK DESA AS MANULEA</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=0061f2&color=fff" alt="Avatar">
                    <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-server"></i> Master Data Referensi</h2>
                    <p style="margin-top:5px;">Kelola data referensi dasar (Agama, Pekerjaan, Pendidikan) untuk sistem kependudukan desa.</p>
                </div>

                <div class="master-grid">
                    
                    <!-- 1. KOTAK AGAMA -->
                    <div class="master-card">
                        <h3><i class="fas fa-pray" style="color:#0061f2;"></i> Data Agama</h3>
                        <form action="" method="POST" style="margin-bottom: 15px; display: flex; gap: 5px;">
                            <input type="text" name="nama_agama" class="form-control" placeholder="Tambah agama..." required style="padding: 6px; font-size: 13px; flex: 1;">
                            <button type="submit" name="tambah_agama" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;"><i class="fas fa-plus"></i></button>
                        </form>
                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                            <table class="table" style="font-size: 13px;">
                                <thead>
                                    <tr><th>No</th><th>Nama Agama</th></tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q_ag = mysqli_query($koneksi, "SELECT * FROM tabel_master_agama ORDER BY id_agama ASC");
                                    if($q_ag && mysqli_num_rows($q_ag) > 0): $no=1; while($a = mysqli_fetch_assoc($q_ag)):
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($a['nama_agama']); ?></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="2" style="text-align:center;">Belum ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 2. KOTAK PEKERJAAN -->
                    <div class="master-card">
                        <h3><i class="fas fa-briefcase" style="color:#198754;"></i> Data Pekerjaan</h3>
                        <form action="" method="POST" style="margin-bottom: 15px; display: flex; gap: 5px;">
                            <input type="text" name="nama_pekerjaan" class="form-control" placeholder="Tambah pekerjaan..." required style="padding: 6px; font-size: 13px; flex: 1;">
                            <button type="submit" name="tambah_pekerjaan" class="btn" style="background:#198754; color:white; padding: 6px 12px; font-size: 13px;"><i class="fas fa-plus"></i></button>
                        </form>
                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                            <table class="table" style="font-size: 13px;">
                                <thead>
                                    <tr><th>No</th><th>Jenis Pekerjaan</th></tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q_pk = mysqli_query($koneksi, "SELECT * FROM tabel_master_pekerjaan ORDER BY id_pekerjaan ASC");
                                    if($q_pk && mysqli_num_rows($q_pk) > 0): $no=1; while($p = mysqli_fetch_assoc($q_pk)):
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($p['nama_pekerjaan']); ?></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="2" style="text-align:center;">Belum ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 3. KOTAK PENDIDIKAN -->
                    <div class="master-card">
                        <h3><i class="fas fa-graduation-cap" style="color:#ffc107;"></i> Tingkat Pendidikan</h3>
                        <form action="" method="POST" style="margin-bottom: 15px; display: flex; gap: 5px;">
                            <input type="text" name="nama_pendidikan" class="form-control" placeholder="Tambah pendidikan..." required style="padding: 6px; font-size: 13px; flex: 1;">
                            <button type="submit" name="tambah_pendidikan" class="btn" style="background:#ffc107; color:#333; padding: 6px 12px; font-size: 13px;"><i class="fas fa-plus"></i></button>
                        </form>
                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                            <table class="table" style="font-size: 13px;">
                                <thead>
                                    <tr><th>No</th><th>Jenjang Pendidikan</th></tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $q_pd = mysqli_query($koneksi, "SELECT * FROM tabel_master_pendidikan ORDER BY id_pendidikan ASC");
                                    if($q_pd && mysqli_num_rows($q_pd) > 0): $no=1; while($d = mysqli_fetch_assoc($q_pd)):
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($d['nama_pendidikan']); ?></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="2" style="text-align:center;">Belum ada data.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    
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