<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}
include '../backend/koneksi.php'; 

$pesan_sukses = isset($_SESSION['sukses']) ? $_SESSION['sukses'] : "";
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
unset($_SESSION['sukses']); // Hapus session setelah ditampilkan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kematian - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
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
                    <h2><i class="fas fa-book-dead"></i> Pengelolaan Data Kematian</h2>
                </div>

                <div style="margin-bottom: 20px;">
                    <a href="tambah_kematian.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data Kematian</a>
                </div>

                <div class="card-table">
                    <div class="card-header">
                        <h3>Daftar Warga Meninggal</h3>
                    </div>
                    <?php
                    // Relasi mengambil nama dan NIK dari tabel_penduduk
                    $query = "SELECT k.*, p.nama, p.nik FROM tabel_kematian k JOIN tabel_penduduk p ON k.id_penduduk = p.id_penduduk ORDER BY k.tanggal_kematian DESC";
                    $result = mysqli_query($koneksi, $query);
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Warga</th>
                                    <th>Tanggal Wafat</th>
                                    <th>Penyebab</th>
                                    <th>Tempat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && mysqli_num_rows($result) > 0): $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['nik']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_kematian'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['penyebab']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tempat_kematian']); ?></td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="6" style="text-align: center; padding: 20px;">Belum ada data kematian tercatat.</td></tr>
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
                <i class="fas fa-check-circle icon-success"></i>
                <h3>Berhasil!</h3>
                <p><?php echo htmlspecialchars($pesan_sukses); ?></p>
            <?php elseif (!empty($pesan_error)): ?>
                <i class="fas fa-exclamation-triangle icon-danger"></i>
                <h3>Perhatian</h3>
                <p><?php echo htmlspecialchars($pesan_error); ?></p>
            <?php endif; ?>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>
</body>
</html>