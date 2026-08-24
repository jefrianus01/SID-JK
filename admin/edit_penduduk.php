<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php'; 

// Menangkap ID penduduk dari URL
$id_penduduk = isset($_GET['id_penduduk']) ? mysqli_real_escape_string($koneksi, $_GET['id_penduduk']) : "";
$query = mysqli_query($koneksi, "SELECT * FROM tabel_penduduk WHERE id_penduduk='$id_penduduk'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>window.location.href='data_penduduk.php';</script>";
    exit;
}

// Menangkap pesan error dari proses edit (jika ada)
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penduduk - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* Gaya Pop-up Modal di Tengah Halaman */
        .modal-notif-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.2s ease;
        }
        .modal-notif-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
        }
        .modal-notif-box i.icon-danger {
            font-size: 50px;
            color: #dc3545;
            margin-bottom: 15px;
        }
        .modal-notif-box h3 {
            margin-bottom: 10px;
            color: #333;
            font-size: 20px;
        }
        .modal-notif-box p {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        .btn-modal-close {
            background: #0061f2;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-modal-close:hover {
            background: #004ecc;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
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
                        
                        <input type="hidden" name="id_penduduk" value="<?php echo $data['id_penduduk']; ?>">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            
                            <!-- Kolom Kiri -->
                            <div>
                                <div class="form-group">
                                    <label>NIK *</label>
                                    <input type="number" name="nik" class="form-control" value="<?php echo htmlspecialchars($data['nik']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Nama Lengkap *</label>
                                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($data['tempat_lahir']); ?>" required>
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
                                    <input type="text" name="pendidikan" class="form-control" value="<?php echo htmlspecialchars($data['pendidikan']); ?>">
                                </div>

                                <div class="form-group">
                                    <label>Pekerjaan</label>
                                    <input type="text" name="pekerjaan" class="form-control" value="<?php echo htmlspecialchars($data['pekerjaan']); ?>">
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

                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-warning" style="background-color: #f4a100; color: white;"><i class="fas fa-save"></i> Perbarui Data</button>
                            <a href="data_penduduk.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL POP-UP NOTIFIKASI DI TENGAH HALAMAN -->
    <div class="modal-notif-overlay" id="modalNotif" style="display: <?php echo (!empty($pesan_error)) ? 'flex' : 'none'; ?>;">
        <div class="modal-notif-box">
            <i class="fas fa-exclamation-triangle icon-danger"></i>
            <h3>Perhatian</h3>
            <p><?php echo htmlspecialchars($pesan_error); ?></p>
            <button class="btn-modal-close" onclick="closeModalNotif()">OK</button>
        </div>
    </div>

    <script>
        function closeModalNotif() {
            document.getElementById('modalNotif').style.display = 'none';
        }
    </script>
</body>
</html>