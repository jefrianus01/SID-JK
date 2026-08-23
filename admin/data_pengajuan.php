<?php
session_start();
// Cek login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Panggil koneksi
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengajuan - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* CSS DROPDOWN SIDEBAR */
        .submenu {
            display: none;
            list-style: none;
            padding-left: 20px;
            background: rgba(0, 0, 0, 0.05);
            margin-bottom: 5px;
        }
        .submenu.show { display: block; }
        .submenu li a { font-size: 14px; padding: 8px 15px; opacity: 0.8; }
        .submenu li a:hover { opacity: 1; padding-left: 20px; }
        .has-submenu > a { display: flex; justify-content: space-between; align-items: center; }
        .toggle-icon { font-size: 12px; transition: transform 0.3s ease; }
        .toggle-icon.rotate { transform: rotate(180deg); }
        .menu-divider { border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- ================= SIDEBAR ================= -->
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

                <li><a href="data_pengajuan.php" class="active"><i class="fas fa-file-signature"></i> <span>DATA PENGAJUAN</span></a></li>
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
                
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-file-signature"></i> Kelola Data Pengajuan Surat</h2>
                    <p style="margin-top:5px;">Halaman ini digunakan untuk mengelola permohonan surat administrasi dari masyarakat.</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <a href="tambah_pengajuan.php" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Pengajuan Baru</a>
                </div>

                <div class="card-table">
                    <div class="card-header">
                        <h3>Daftar Pengajuan Masuk</h3>
                    </div>

                    <?php
                    // Mengeksekusi Query
                    $query = "SELECT * FROM tabel_pengajuan ORDER BY tanggal_pengajuan DESC";
                    $result = mysqli_query($koneksi, $query);
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tgl Pengajuan</th>
                                    <th>NIK Pemohon</th>
                                    <th>Nama Pemohon</th>
                                    <th>Jenis Surat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // PERBAIKAN: Menambahkan $result && agar lebih aman dari error TypeError
                                if($result && mysqli_num_rows($result) > 0): 
                                    $no=1; 
                                    while($row = mysqli_fetch_assoc($result)): 
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['nik']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_surat']); ?></td>
                                    <td>
                                        <?php 
                                        if($row['status'] == 'Selesai') {
                                            echo '<span class="badge" style="background:#d4edda; color:#155724;">Selesai</span>';
                                        } else {
                                            echo '<span class="badge" style="background:#fff3cd; color:#856404;">Proses</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-edit"><i class="fas fa-print"></i> Cetak</a>
                                        <a href="#" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                elseif(!$result): 
                                ?>
                                <!-- Jika query gagal, tampilkan pesan error dari SQL -->
                                <tr>
                                    <td colspan="7" style="text-align:center; padding: 20px; color: red;">
                                        Error Query
                                    </td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding: 20px;">Belum ada data pengajuan.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- SCRIPT DROPDOWN -->
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