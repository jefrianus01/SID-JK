<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Menghubungkan ke database
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
    <title>Data Kematian - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
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
                <li><a href="data_kk.php"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
                <li><a href="kematian.php" class="active"><i class="fas fa-book-dead"></i> <span>Data Kematian</span></a></li>
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
                    <h2><i class="fas fa-book-dead"></i> Register Kematian Penduduk</h2>
                </div>

                <div style="margin-bottom: 20px;">
                    <!-- Tombol ini akan mengarah ke form input data kematian -->
                    <a href="tambah_kematian.php" class="btn btn-primary" style="background-color: var(--danger);"><i class="fas fa-plus"></i> Catat Kematian Baru</a>
                </div>

                <!-- TABEL DATA -->
                <div class="card-table">
                    <div class="card-header">
                        <h3>Daftar Kematian Penduduk</h3>
                    </div>

                    <?php
                    // Query JOIN antara tabel kematian dan tabel penduduk
                    $query = "SELECT k.id_kematian, k.tanggal_kematian, k.sebab, k.nama_pelapor, 
                                     p.nik, p.nama, p.jenis_kelamin 
                              FROM tabel_kematian k
                              INNER JOIN tabel_penduduk p ON k.id_penduduk = p.id_penduduk
                              ORDER BY k.tanggal_kematian DESC";
                    $result = mysqli_query($koneksi, $query);
                    ?>

                    <?php if($result && mysqli_num_rows($result) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Wafat</th>
                                        <th>NIK - Nama Penduduk</th>
                                        <th>Penyebab</th>
                                        <th>Nama Pelapor</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): 
                                        $tgl_mati = date('d-m-Y', strtotime($row['tanggal_kematian']));
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo $tgl_mati; ?></strong></td>
                                        <td>
                                            <?php echo htmlspecialchars($row['nik']); ?><br>
                                            <strong><?php echo htmlspecialchars($row['nama']); ?></strong> 
                                            <span style="font-size: 11px; color: #888;">(<?php echo $row['jenis_kelamin']; ?>)</span>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['sebab']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_pelapor']); ?></td>
                                        <td>
                                            <!-- Aksi ini bisa ditambahkan nanti -->
                                            <a href="#" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus catatan ini?');"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px 20px; text-align: center; color: #888;">
                            <i class="fas fa-file-signature" style="font-size: 48px; margin-bottom: 10px; color: #ddd;"></i>
                            <p>Belum ada catatan register kematian.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>