<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include '../backend/koneksi.php'; 
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";

// HANYA mengambil warga yang statusnya masih 'Aktif'
$q_penduduk = mysqli_query($koneksi, "SELECT * FROM tabel_penduduk WHERE status = 'Aktif' ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Kematian - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header"><div class="logo"><i class="fas fa-landmark"></i></div><h2>Desa As Manulea</h2><p>Kabupaten Malaka</p></div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>Dashboard</span></a></li>
                <li><a href="data_penduduk.php"><i class="fas fa-users"></i> <span>Data Penduduk</span></a></li>
                <li><a href="data_kk.php"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
                <li><a href="kematian.php" class="active"><i class="fas fa-book-dead"></i> <span>Data Kematian</span></a></li>
                <li><a href="pindah.php"><i class="fas fa-truck-moving"></i> <span>Data Pindah</span></a></li>
                <li><a href="pendatang.php"><i class="fas fa-suitcase-rolling"></i> <span>Data Pendatang</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-file-alt"></i> <span>Laporan</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
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
                    <h2><i class="fas fa-plus"></i> Tambah Laporan Kematian</h2>
                </div>

                <div class="card-table" style="padding: 30px;">
                    <form action="proses_tambah_kematian.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <div class="form-group">
                            <label>Pilih Warga (Otomatis Menghapus dari KK) *</label>
                            <select name="id_penduduk" class="form-control" required>
                                <option value="">-- Cari Nama Warga --</option>
                                <?php while($p = mysqli_fetch_assoc($q_penduduk)): ?>
                                    <option value="<?php echo $p['id_penduduk']; ?>">
                                        <?php echo htmlspecialchars($p['nama']) . ' - NIK: ' . $p['nik']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Meninggal *</label>
                            <input type="date" name="tanggal_kematian" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Penyebab Kematian *</label>
                            <input type="text" name="penyebab" class="form-control" placeholder="Contoh: Sakit, Usia Lanjut, dll" required>
                        </div>

                        <div class="form-group">
                            <label>Tempat Meninggal</label>
                            <input type="text" name="tempat_kematian" class="form-control" placeholder="Contoh: RSUD, Rumah duka, dll">
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Proses Kematian</button>
                            <a href="kematian.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL POP-UP NOTIFIKASI -->
    <div class="modal-notif-overlay" id="modalNotif" style="display: <?php echo (!empty($pesan_error)) ? 'flex' : 'none'; ?>;">
        <div class="modal-notif-box">
            <i class="fas fa-exclamation-triangle icon-danger"></i>
            <h3>Perhatian</h3>
            <p><?php echo htmlspecialchars($pesan_error); ?></p>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>
</body>
</html>