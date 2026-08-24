<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 

// Generate CSRF token untuk tombol hapus
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Menangkap Notifikasi
$pesan_sukses = isset($_SESSION['sukses']) ? $_SESSION['sukses'] : "";
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
unset($_SESSION['sukses']); 
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
        .submenu { display: none; list-style: none; padding-left: 20px; background: rgba(0, 0, 0, 0.05); margin-bottom: 5px; }
        .submenu.show { display: block; }
        .submenu li a { font-size: 14px; padding: 8px 15px; opacity: 0.8; }
        .submenu li a:hover { opacity: 1; padding-left: 20px; }
        .has-submenu > a { display: flex; justify-content: space-between; align-items: center; }
        .toggle-icon { font-size: 12px; transition: transform 0.3s ease; }
        .toggle-icon.rotate { transform: rotate(180deg); }
        .menu-divider { border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px; }
        
        /* CSS MODAL NOTIFIKASI */
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; animation: fadeIn 0.2s ease; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-success { font-size: 50px; color: #198754; margin-bottom: 15px; }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2><p>ADMIN</p>
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
                <li><a href="laporan.php"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>
                <li class="menu-divider"></li>
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
                                <?php if($result && mysqli_num_rows($result) > 0): $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['nik']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jenis_surat']); ?></td>
                                    <td>
                                        <?php if($row['status'] == 'Selesai'): ?>
                                            <span class="badge" style="background:#d4edda; color:#155724; padding: 5px 10px; border-radius: 4px;">Selesai</span>
                                        <?php else: ?>
                                            <span class="badge" style="background:#fff3cd; color:#856404; padding: 5px 10px; border-radius: 4px;">Proses</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="cetak_surat.php?id=<?php echo $row['id_pengajuan']; ?>" target="_blank" class="btn btn-sm" style="background-color: #0061f2; color: white;"><i class="fas fa-print"></i> Cetak</a>
                                        <!-- Tombol Hapus Menggunakan Form Aman (CSRF) -->
                                        <form action="proses_hapus_pengajuan.php" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data pengajuan ini?');">
                                            <input type="hidden" name="id_pengajuan" value="<?php echo $row['id_pengajuan']; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; elseif(!$result): ?>
                                <tr><td colspan="7" style="text-align:center; padding: 20px; color: red;">Error Database Database</td></tr>
                                <?php else: ?>
                                <tr><td colspan="7" style="text-align:center; padding: 20px;">Belum ada data pengajuan surat.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL POP-UP NOTIFIKASI -->
    <div class="modal-notif-overlay" id="modalNotif" style="display: <?php echo (!empty($pesan_sukses) || !empty($pesan_error)) ? 'flex' : 'none'; ?>;">
        <div class="modal-notif-box">
            <?php if (!empty($pesan_sukses)): ?>
                <i class="fas fa-check-circle icon-success"></i><h3>Berhasil!</h3><p><?php echo htmlspecialchars($pesan_sukses); ?></p>
            <?php elseif (!empty($pesan_error)): ?>
                <i class="fas fa-exclamation-triangle icon-danger"></i><h3>Perhatian</h3><p><?php echo htmlspecialchars($pesan_error); ?></p>
            <?php endif; ?>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>

    <!-- SCRIPT DROPDOWN SIDEBAR -->
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