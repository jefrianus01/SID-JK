<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

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
    <title>Catat Kelahiran - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="layout-container">
        
        <!-- SIDEBAR -->
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
                <li><a href="kelahiran.php" class="active"><i class="fas fa-baby"></i> <span>Data Kelahiran</span></a></li>
                <li><a href="kematian.php"><i class="fas fa-book-dead"></i> <span>Data Kematian</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-file-alt"></i> <span>Laporan</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="header">
                <h1>SISTEM INFORMASI DATA KEPENDUDUKAN</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>&background=0061f2&color=fff" alt="User Avatar">
                    <span>Halo, <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-plus-circle"></i> Catat Data Kelahiran Baru</h2>
                </div>

                <div class="card-table" style="padding: 30px; max-width: 700px;">
                    <form action="proses_tambah_kelahiran.php" method="POST">
                        
                        <div class="form-group">
                            <label>Nama Lengkap Bayi *</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap Anak" required>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Tempat Lahir *</label>
                                <input type="text" name="tempat_lahir" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>Tanggal Lahir *</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin *</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pilih Data Orang Tua (Bapak/Ibu) *</label>
                            <select name="id_penduduk" class="form-control" required>
                                <option value="">-- Cari Orang Tua di Database --</option>
                                <?php
                                // Ambil data penduduk aktif untuk dijadikan opsi orang tua
                                $query_ortu = mysqli_query($koneksi, "SELECT id_penduduk, nik, nama FROM tabel_penduduk WHERE status='Aktif' ORDER BY nama ASC");
                                while($ortu = mysqli_fetch_assoc($query_ortu)) {
                                    echo "<option value='".$ortu['id_penduduk']."'>".$ortu['nik']." - ".$ortu['nama']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary" style="background-color: var(--success);"><i class="fas fa-save"></i> Simpan Register Kelahiran</button>
                            <a href="kelahiran.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>