<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Memanggil koneksi. Mundur dari folder admin (../), lalu masuk ke folder backend
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Keluarga - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Menggunakan CSS utama yang sudah kita perbagus -->
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
                <li><a href="data_kk.php" class="active"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
                <li><a href="kematian.php"><i class="fas fa-book-dead"></i> <span>Data Kematian</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-file-alt"></i> <span>Laporan</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="main-content">
            
            <!-- HEADER -->
            <div class="header">
                <h1>SISTEM INFORMASI DATA KEPENDUDUKAN</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?>&background=0061f2&color=fff" alt="User Avatar">
                    <span>Halo, <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin'; ?></strong></span>
                </div>
            </div>

            <!-- KONTEN UTAMA -->
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
                    // Query untuk mengambil data KK sekaligus menghitung jumlah anggotanya dari tabel_anggota
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
                                            <span class="badge badge-active"><?php echo $row['jumlah_anggota']; ?> Orang</span>
                                        </td>
                                        <td>
                                            <!-- Menggunakan id_kk sesuai field primary key di database -->
                                            <a href="edit_kk.php?id_kk=<?php echo $row['id_kk']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="proses_hapus_kk.php?id_kk=<?php echo $row['id_kk']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus data KK ini?');"><i class="fas fa-trash"></i> Hapus</a>
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
</body>
</html>