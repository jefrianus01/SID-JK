<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pendatang - Admin Desa As Manulea</title>
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
    </style>
</head>
<body>
    <div class="layout-container">
        
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
                    <a href="#" class="submenu-toggle"><i class="fas fa-sync-alt"></i> <span>SIRKULASI PENDUDUK</span> <i class="fas fa-chevron-down toggle-icon rotate"></i></a>
                    <ul class="submenu show">
                        <li><a href="kelahiran.php"><i class="fas fa-baby"></i> Data Kelahiran</a></li>
                        <li><a href="kematian.php"><i class="fas fa-book-dead"></i> Data Kematian</a></li>
                        <li><a href="pindah.php"><i class="fas fa-truck-moving"></i> Data Pindah</a></li>
                        <li><a href="pendatang.php" class="active"><i class="fas fa-suitcase-rolling"></i> Data Pendatang</a></li>
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
                    <h2><i class="fas fa-user-plus"></i> Form Tambah Data Pendatang</h2>
                </div>

                <div class="card-table" style="padding: 30px; max-width: 700px;">
                    <form action="proses_tambah_pendatang.php" method="POST">
                        
                        <div class="form-group">
                            <label>NIK Pendatang *</label>
                            <input type="number" name="nik" class="form-control" placeholder="Masukkan 16 digit NIK" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama lengkap sesuai identitas" required>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin *</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Datang *</label>
                            <input type="date" name="tanggal_datang" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat Asal *</label>
                            <textarea name="alamat_asal" class="form-control" rows="3" placeholder="Alamat lengkap asal daerah" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Bekerja, Menumpang, Pindah Domisili">
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-info" style="color:white;"><i class="fas fa-save"></i> Simpan Data Pendatang</button>
                            <a href="pendatang.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Batal</a>
                        </div>
                    </form>
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