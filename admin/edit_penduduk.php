<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php'; 

// Mengambil data penduduk berdasarkan ID
$id_penduduk = $_GET['id_penduduk'];
$query = mysqli_query($koneksi, "SELECT * FROM tabel_penduduk WHERE id_penduduk='$id_penduduk'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='data_penduduk.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penduduk - Admin</title>
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
                <li><a href="data_penduduk.php" class="active"><i class="fas fa-users"></i> <span>Data Penduduk</span></a></li>
                <li><a href="data_kk.php"><i class="fas fa-id-card"></i> <span>Data Keluarga</span></a></li>
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
                    <h2><i class="fas fa-user-edit"></i> Edit Data Penduduk</h2>
                </div>

                <div class="card-table" style="padding: 30px;">
                    <form action="proses_edit_penduduk.php" method="POST">
                        
                        <!-- ID yang di-hidden untuk keperluan UPDATE SQL -->
                        <input type="hidden" name="id_penduduk" value="<?php echo $data['id_penduduk']; ?>">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            
                            <!-- Kolom Kiri -->
                            <div>
                                <div class="form-group">
                                    <label>NIK *</label>
                                    <input type="number" name="nik" class="form-control" value="<?php echo $data['nik']; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nama Lengkap *</label>
                                    <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" class="form-control" value="<?php echo $data['tempat_lahir']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo $data['tanggal_lahir']; ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="Laki-laki" <?php if($data['jenis_kelamin'] == 'Laki-laki') echo 'selected'; ?>>Laki-laki</option>
                                        <option value="Perempuan" <?php if($data['jenis_kelamin'] == 'Perempuan') echo 'selected'; ?>>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div>
                                <div class="form-group">
                                    <label>Agama *</label>
                                    <select name="agama" class="form-control" required>
                                        <option value="Katolik" <?php if($data['agama'] == 'Katolik') echo 'selected'; ?>>Katolik</option>
                                        <option value="Protestan" <?php if($data['agama'] == 'Protestan') echo 'selected'; ?>>Protestan</option>
                                        <option value="Islam" <?php if($data['agama'] == 'Islam') echo 'selected'; ?>>Islam</option>
                                        <option value="Hindu" <?php if($data['agama'] == 'Hindu') echo 'selected'; ?>>Hindu</option>
                                        <option value="Buddha" <?php if($data['agama'] == 'Buddha') echo 'selected'; ?>>Buddha</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Status Perkawinan *</label>
                                    <select name="status_perkawinan" class="form-control" required>
                                        <option value="Belum Kawin" <?php if($data['status_perkawinan'] == 'Belum Kawin') echo 'selected'; ?>>Belum Kawin</option>
                                        <option value="Kawin" <?php if($data['status_perkawinan'] == 'Kawin') echo 'selected'; ?>>Kawin</option>
                                        <option value="Cerai Hidup" <?php if($data['status_perkawinan'] == 'Cerai Hidup') echo 'selected'; ?>>Cerai Hidup</option>
                                        <option value="Cerai Mati" <?php if($data['status_perkawinan'] == 'Cerai Mati') echo 'selected'; ?>>Cerai Mati</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan" class="form-control" value="<?php echo $data['pendidikan']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control" value="<?php echo $data['pekerjaan']; ?>">
                                </div>

                                <div class="form-group">
                                    <label>Status Kependudukan *</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Aktif" <?php if($data['status'] == 'Aktif') echo 'selected'; ?>>Aktif</option>
                                        <option value="Meninggal" <?php if($data['status'] == 'Meninggal') echo 'selected'; ?>>Meninggal</option>
                                        <option value="Pindah" <?php if($data['status'] == 'Pindah') echo 'selected'; ?>>Pindah</option>
                                    </select>
                                </div>
                            </div>

                        </div> <!-- Akhir Grid -->

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-warning" style="background-color: #f4a100; color: white;"><i class="fas fa-save"></i> Perbarui Data</button>
                            <a href="data_penduduk.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>