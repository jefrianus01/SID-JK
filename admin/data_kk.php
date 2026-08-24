<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Memanggil koneksi. Mundur dari folder admin (../), lalu masuk ke folder backend
include '../backend/koneksi.php'; 

// Generate Token CSRF jika belum ada (dibutuhkan untuk tombol hapus)
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
    <title>Data Keluarga - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Tambahan CSS Modal Notifikasi */
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; animation: fadeIn 0.2s ease; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-success { font-size: 50px; color: #198754; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- ================= SIDEBAR ================= -->
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

        <!-- ================= MAIN CONTENT ================= -->
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
                    <h2><i class="fas fa-id-card"></i> Pengelolaan Data Kartu Keluarga</h2>
                </div>

                <div style="margin-bottom: 20px;">
                    <a href="tambah_kk.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data Keluarga</a>
                </div>

                <!-- TABEL DATA -->
                <div class="card-table">
                    <div class="card-header">
                        <h3>Daftar Data Keluarga</h3>
                    </div>

                    <?php
                    // Query pintar
                    $query = "SELECT k.id_kk, k.no_kk, k.nama as ketua_keluarga, 
                              (SELECT COUNT(*) FROM tabel_anggota a WHERE a.id_kk = k.id_kk) as jumlah_anggota 
                              FROM tabel_kk k 
                              ORDER BY k.no_kk ASC";
                    $result = mysqli_query($koneksi, $query);
                    ?>

                    <?php if($result && mysqli_num_rows($result) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No KK</th>
                                        <th>Kepala Keluarga</th>
                                        <th>Jumlah Anggota</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['no_kk']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['ketua_keluarga']); ?></td>
                                        <td>
                                            <span class="badge badge-active" style="background:#e2e8f0; color:#333; padding:5px 10px;"><?php echo $row['jumlah_anggota']; ?> Orang</span>
                                        </td>
                                        <td>
                                            <!-- INI TOMBOL DETAILNYA -->
                                            <a href="detail_kk.php?id=<?php echo $row['id_kk']; ?>" class="btn btn-sm" style="background-color: #17a2b8; color: white;"><i class="fas fa-users"></i> Detail</a>
                                            
                                            <a href="edit_kk.php?id_kk=<?php echo $row['id_kk']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                            
                                            <!-- Action hapus disesuaikan agar mengarah ke proses_hapus_kk.php -->
                                            <form action="proses_hapus_kk.php" method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data KK ini? Pastikan kosongkan anggotanya terlebih dahulu.');">
                                                <input type="hidden" name="id_kk" value="<?php echo $row['id_kk']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px 20px; text-align: center; color: #888;">
                            <i class="fas fa-folder-open" style="font-size: 48px; margin-bottom: 10px; color: #ddd;"></i>
                            <p>Belum ada data Kartu Keluarga yang terdaftar.</p>
                        </div>
                    <?php endif; ?>
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
                <i class="fas fa-exclamation-triangle icon-danger" style="font-size: 50px; color: #dc3545; margin-bottom: 15px;"></i><h3>Perhatian</h3><p><?php echo htmlspecialchars($pesan_error); ?></p>
            <?php endif; ?>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>
</body>
</html>