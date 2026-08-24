<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Generate CSRF token untuk keamanan
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include '../backend/koneksi.php'; 
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";

// Mengambil data Kepala Desa yang aktif (Asumsi ada tabel_kepala_desa, jika tidak ada, ini akan diskip dan kamu bisa menyesuaikannya)
$q_kades = mysqli_query($koneksi, "SELECT id_kepala_desa, nama_kepala_desa FROM tabel_kepala_desa WHERE status = 'Aktif' LIMIT 1");
$kades = $q_kades ? mysqli_fetch_assoc($q_kades) : null;
$id_kades_default = $kades ? $kades['id_kepala_desa'] : 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data KK - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        
        .form-row { display: flex; gap: 20px; flex-wrap: wrap; }
        .form-row .form-group { flex: 1; min-width: 200px; }
    </style>
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
                <li><a href="data_kk.php" class="active"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
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
                    <h2><i class="fas fa-plus-circle"></i> Tambah Data Kartu Keluarga Baru</h2>
                    <p style="margin-top: 5px;">Silakan buat wadah Kartu Keluarga terlebih dahulu, lalu masukkan anggotanya pada menu Detail.</p>
                </div>

                <div class="card-table" style="padding: 30px;">
                    <form action="proses_tambah_kk.php" method="POST">
                        
                        <!-- Token Keamanan & ID Kepala Desa -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id_kepala_desa" value="<?php echo $id_kades_default; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nomor Kartu Keluarga (No. KK) *</label>
                                <input type="number" name="no_kk" class="form-control" placeholder="Masukkan 16 digit No KK" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Kepala Keluarga *</label>
                                <input type="text" name="nama" class="form-control" placeholder="Sesuai yang tertera di KK" required>
                            </div>
                        </div>

                        <!-- HAPUS KOLOM JUMLAH ANGGOTA: Sistem akan menghitung otomatis -->
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Desa/Kelurahan *</label>
                                <input type="text" name="desa" class="form-control" value="As Manulea" required>
                            </div>
                            <div class="form-group">
                                <label>Kecamatan *</label>
                                <input type="text" name="kecamatan" class="form-control" value="Sasitamean" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Kabupaten *</label>
                                <input type="text" name="kabupaten" class="form-control" value="Malaka" required>
                            </div>
                            <div class="form-group">
                                <label>Provinsi *</label>
                                <input type="text" name="provinsi" class="form-control" value="Nusa Tenggara Timur" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status Bantuan Sosial</label>
                            <select name="bantuan" class="form-control">
                                <option value="Tidak Ada">Tidak Ada</option>
                                <option value="PKH">PKH (Program Keluarga Harapan)</option>
                                <option value="BLT">BLT (Bantuan Langsung Tunai)</option>
                                <option value="BPNT">BPNT (Bantuan Pangan Non Tunai)</option>
                            </select>
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Buat Kartu Keluarga</button>
                            <a href="data_kk.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL POP-UP NOTIFIKASI ERROR -->
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