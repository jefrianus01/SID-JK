<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 

$pesan = "";

// PROSES TAMBAH DUSUN
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_dusun'])) {
    $nama_dusun = mysqli_real_escape_string($koneksi, $_POST['nama_dusun']);
    $nama_kadus = mysqli_real_escape_string($koneksi, $_POST['nama_kadus']);
    $jumlah_rt  = (int)$_POST['jumlah_rt'];

    if (!empty($nama_dusun)) {
        $query = "INSERT INTO tabel_dusun (nama_dusun, nama_kadus, jumlah_rt) VALUES ('$nama_dusun', '$nama_kadus', $jumlah_rt)";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Data dusun berhasil ditambahkan!'); window.location.href='data_dusun.php';</script>";
        } else {
            $pesan = "Gagal menyimpan data";
        }
    }
}

// PROSES HAPUS DUSUN
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tabel_dusun WHERE id_dusun = $id");
    header("location: data_dusun.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dusun/RT - Admin Desa As Manulea</title>
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
        
        /* Layout Grid: Kiri Form Tambah, Kanan Tabel Data */
        .dusun-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
        @media(max-width: 992px) { .dusun-grid { grid-template-columns: 1fr; } }
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
                <li><a href="data_dusun.php" class="active"><i class="fas fa-map-marker-alt"></i> <span>DATA DUSUN/RT</span></a></li>
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
                    <h2><i class="fas fa-map-marker-alt"></i> Wilayah Administrasi Dusun & RT/RW</h2>
                    <p style="margin-top:5px;">Kelola pembagian wilayah dusun dan kepala dusun di lingkungan Desa As Manulea.</p>
                </div>

                <?php if(!empty($pesan)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
                        <?php echo $pesan; ?>
                    </div>
                <?php endif; ?>

                <div class="dusun-grid">
                    
                    <!-- KOTAK KIRI: FORM TAMBAH DUSUN -->
                    <div class="card-table" style="padding: 25px; height: fit-content;">
                        <div class="card-header" style="margin-bottom: 15px;">
                            <h3><i class="fas fa-plus-circle"></i> Tambah Dusun Baru</h3>
                        </div>
                        <form action="" method="POST">
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 13px; font-weight: bold;">Nama Dusun *</label>
                                <input type="text" name="nama_dusun" class="form-control" placeholder="Contoh: Dusun A / Manusak" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 13px; font-weight: bold;">Nama Kepala Dusun (Kadus)</label>
                                <input type="text" name="nama_kadus" class="form-control" placeholder="Nama lengkap Kadus" style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-size: 13px; font-weight: bold;">Jumlah RT *</label>
                                <input type="number" name="jumlah_rt" class="form-control" value="1" min="1" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <button type="submit" name="tambah_dusun" class="btn btn-primary" style="width: 100%; padding: 10px; font-weight: bold; border-radius: 6px;">
                                <i class="fas fa-save"></i> Simpan Dusun
                            </button>
                        </form>
                    </div>

                    <!-- KOTAK KANAN: TABEL DAFTAR DUSUN -->
                    <div class="card-table">
                        <div class="card-header">
                            <h3>Daftar Dusun & Wilayah</h3>
                        </div>
                        <?php
                        $query = "SELECT * FROM tabel_dusun ORDER BY id_dusun ASC";
                        $result = mysqli_query($koneksi, $query);
                        ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dusun</th>
                                        <th>Kepala Dusun (Kadus)</th>
                                        <th>Jumlah RT</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($result && mysqli_num_rows($result) > 0): 
                                        $no=1; 
                                        while($row = mysqli_fetch_assoc($result)): 
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['nama_dusun']); ?></strong></td>
                                        <td><?php echo !empty($row['nama_kadus']) ? htmlspecialchars($row['nama_kadus']) : '-'; ?></td>
                                        <td><?php echo $row['jumlah_rt']; ?> RT</td>
                                        <td>
                                            <a href="data_dusun.php?hapus=<?php echo $row['id_dusun']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus data dusun ini?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php 
                                        endwhile; 
                                    else: 
                                    ?>
                                    <tr>
                                        <td colspan="5" style="text-align:center; padding: 20px;">Belum ada data dusun tercatat.</td>
                                    </tr>
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