<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 

$pesan = "";

// PROSES UPDATE PROFIL
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profil'])) {
    $nama_desa     = mysqli_real_escape_string($koneksi, $_POST['nama_desa']);
    $nama_kades    = mysqli_real_escape_string($koneksi, $_POST['nama_kades']);
    $kecamatan     = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $kabupaten     = mysqli_real_escape_string($koneksi, $_POST['kabupaten']);
    $provinsi      = mysqli_real_escape_string($koneksi, $_POST['provinsi']);
    $kode_pos      = mysqli_real_escape_string($koneksi, $_POST['kode_pos']);
    $alamat_kantor = mysqli_real_escape_string($koneksi, $_POST['alamat_kantor']);

    $query = "UPDATE tabel_profil SET 
                nama_desa='$nama_desa', 
                nama_kades='$nama_kades', 
                kecamatan='$kecamatan', 
                kabupaten='$kabupaten', 
                provinsi='$provinsi', 
                kode_pos='$kode_pos', 
                alamat_kantor='$alamat_kantor' 
              WHERE id_profil=1";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Profil desa berhasil diperbarui!'); window.location.href='data_profil.php';</script>";
    } else {
        $pesan = "Gagal memperbarui profil";
    }
}

// Ambil data profil saat ini
$q_profil = mysqli_query($koneksi, "SELECT * FROM tabel_profil WHERE id_profil=1");
$profil = mysqli_fetch_assoc($q_profil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Profil Desa - Admin Desa As Manulea</title>
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
        
        .profile-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.08); border: 1px solid #e3e6ec; max-width: 800px; }
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
                <li><a href="master_data.php"><i class="fas fa-server"></i> <span>MASTER DATA</span></a></li>
                <li><a href="data_dusun.php"><i class="fas fa-map-marker-alt"></i> <span>DATA DUSUN/RT</span></a></li>
                <li><a href="data_profil.php" class="active"><i class="fas fa-id-badge"></i> <span>DATA PROFIL</span></a></li>
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
                    <h2><i class="fas fa-id-badge"></i> Profil Wilayah Desa As Manulea</h2>
                    <p style="margin-top:5px;">Perbarui informasi identitas resmi wilayah dan aparatur pemerintahan desa.</p>
                </div>

                <?php if(!empty($pesan)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
                        <?php echo $pesan; ?>
                    </div>
                <?php endif; ?>

                <div class="profile-card">
                    <form action="" method="POST">
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="font-weight: bold; font-size: 13px;">Nama Desa</label>
                            <input type="text" name="nama_desa" class="form-control" value="<?php echo htmlspecialchars($profil['nama_desa']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>

                        <div class="form-group" style="margin-bottom: 15px;">
                            <label style="font-weight: bold; font-size: 13px;">Nama Kepala Desa</label>
                            <input type="text" name="nama_kades" class="form-control" value="<?php echo htmlspecialchars($profil['nama_kades']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-weight: bold; font-size: 13px;">Kecamatan</label>
                                <input type="text" name="kecamatan" class="form-control" value="<?php echo htmlspecialchars($profil['kecamatan']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-weight: bold; font-size: 13px;">Kabupaten</label>
                                <input type="text" name="kabupaten" class="form-control" value="<?php echo htmlspecialchars($profil['kabupaten']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-weight: bold; font-size: 13px;">Provinsi</label>
                                <input type="text" name="provinsi" class="form-control" value="<?php echo htmlspecialchars($profil['provinsi']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-weight: bold; font-size: 13px;">Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control" value="<?php echo htmlspecialchars($profil['kode_pos']); ?>" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 20px;">
                            <label style="font-weight: bold; font-size: 13px;">Alamat Kantor Desa</label>
                            <textarea name="alamat_kantor" class="form-control" rows="3" required style="width: 100%; padding: 8px; margin-top: 5px;"><?php echo htmlspecialchars($profil['alamat_kantor']); ?></textarea>
                        </div>

                        <button type="submit" name="update_profil" class="btn btn-primary" style="padding: 10px 25px; font-weight: bold; border-radius: 6px;">
                            <i class="fas fa-save"></i> Simpan Perubahan Profil
                        </button>
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