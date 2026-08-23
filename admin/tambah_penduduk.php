<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penduduk - Admin Desa As Manulea</title>
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
                    <h2><i class="fas fa-user-plus"></i> Tambah Data Penduduk Baru</h2>
                </div>

                <div class="card-table" style="padding: 30px;">
                    <form action="proses_tambah_penduduk.php" method="POST">
                        
                        <!-- Relasi ke Kepala Desa (Bisa diubah jadi dinamis nanti jika perlu) -->
                        <input type="hidden" name="id_kepala_desa" value="1">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            
                            <!-- Kolom Kiri -->
                            <div>
                                <div class="form-group">
                                    <label>NIK (Nomor Induk Kependudukan) *</label>
                                    <input type="number" name="nik" class="form-control" placeholder="Masukkan 16 digit NIK" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nama Lengkap *</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama sesuai KTP" required>
                                </div>

                                <div class="form-group">
                                    <label>Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Lahir *</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div>
                                <div class="form-group">
                                    <label>Agama *</label>
                                    <select name="agama" class="form-control" required>
                                        <option value="">-- Pilih Agama --</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Protestan">Protestan</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Status Perkawinan *</label>
                                    <select name="status_perkawinan" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Belum Kawin">Belum Kawin</option>
                                        <option value="Kawin">Kawin</option>
                                        <option value="Cerai Hidup">Cerai Hidup</option>
                                        <option value="Cerai Mati">Cerai Mati</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Pendidikan Terakhir</label>
                                    <input type="text" name="pendidikan" class="form-control" placeholder="Contoh: SMA / S1">
                                </div>

                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control" placeholder="Contoh: Petani / Wiraswasta">
                                </div>

                                <div class="form-group">
                                    <label>Status Kependudukan *</label>
                                    <select name="status" class="form-control" required>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Meninggal">Meninggal</option>
                                        <option value="Pindah">Pindah</option>
                                    </select>
                                </div>
                            </div>

                        </div> <!-- Akhir Grid -->

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Penduduk</button>
                            <a href="data_penduduk.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>