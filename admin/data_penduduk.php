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
    <title>Data Penduduk - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Menggunakan CSS utama yang sudah disatukan -->
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
                <li><a href="data_penduduk.php" class="active"><i class="fas fa-users"></i> <span>Data Penduduk</span></a></li>
                <li><a href="data_kk.php"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
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
                    <h2><i class="fas fa-users"></i> Pengelolaan Data Penduduk</h2>
                </div>

                <div style="margin-bottom: 20px;">
                    <a href="tambah_penduduk.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Tambah Data Penduduk</a>
                </div>

                <!-- TABEL DATA -->
                <div class="card-table">
                    <div class="card-header">
                        <h3>Daftar Penduduk Desa</h3>
                    </div>

                    <?php
                    // Query untuk mengambil data penduduk
                    $query = "SELECT * FROM tabel_penduduk ORDER BY nama ASC";
                    $result = mysqli_query($koneksi, $query);
                    ?>

                    <?php if($result && mysqli_num_rows($result) > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                        <th>TTL</th>
                                        <th>Status Perkawinan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; while($row = mysqli_fetch_assoc($result)): 
                                        $tgl_lahir = date('d-m-Y', strtotime($row['tanggal_lahir']));
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['nik']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                        <td><?php echo htmlspecialchars($row['tempat_lahir']) . ', ' . $tgl_lahir; ?></td>
                                        <td>
                                            <?php 
                                            // Memberikan badge warna berbeda berdasarkan status perkawinan
                                            if ($row['status_perkawinan'] == 'Belum Kawin') {
                                                echo '<span class="badge" style="background: #e8eaf6; color: #3f51b5;">Belum Kawin</span>';
                                            } else if ($row['status_perkawinan'] == 'Kawin') {
                                                echo '<span class="badge badge-active">Kawin</span>';
                                            } else {
                                                echo '<span class="badge" style="background: #fff3e0; color: #e65100;">'.$row['status_perkawinan'].'</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="edit_penduduk.php?id_penduduk=<?php echo $row['id_penduduk']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="proses_hapus_penduduk.php?id_penduduk=<?php echo $row['id_penduduk']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Yakin ingin menghapus data penduduk ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px 20px; text-align: center; color: #888;">
                            <i class="fas fa-users-slash" style="font-size: 48px; margin-bottom: 10px; color: #ddd;"></i>
                            <p>Belum ada data penduduk yang terdaftar di sistem.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>