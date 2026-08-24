<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

include '../backend/koneksi.php'; 

$id_kk = isset($_GET['id_kk']) ? (int)$_GET['id_kk'] : 0;
$query = mysqli_query($koneksi, "SELECT * FROM tabel_kk WHERE id_kk = $id_kk");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>window.location.href='data_kk.php';</script>";
    exit;
}
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Keluarga - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .modal-notif-overlay {
            display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;
        }
        .modal-notif-box {
            background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative;
        }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="layout-container">
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
                    <h2><i class="fas fa-edit"></i> Edit Data Kartu Keluarga</h2>
                </div>

                <div class="card-table" style="padding: 30px;">
                    <form action="proses_edit_kk.php" method="POST">
                        <input type="hidden" name="id_kk" value="<?php echo $data['id_kk']; ?>">
                        <input type="hidden" name="id_kepala_desa" value="<?php echo $data['id_kepala_desa']; ?>">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div>
                                <div class="form-group">
                                    <label>Nomor KK *</label>
                                    <input type="text" name="no_kk" class="form-control" value="<?php echo htmlspecialchars($data['no_kk']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Nama Kepala Keluarga *</label>
                                    <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Anggota Keluarga *</label>
                                    <input type="number" name="jumlah_anggota" class="form-control" value="<?php echo isset($data['jumlah_anggota']) ? $data['jumlah_anggota'] : 1; ?>" min="1" required>
                                </div>
                                <div class="form-group">
                                    <label>Desa *</label>
                                    <input type="text" name="desa" class="form-control" value="<?php echo htmlspecialchars($data['desa']); ?>" required>
                                </div>
                            </div>
                            <div>
                                <div class="form-group">
                                    <label>Kecamatan *</label>
                                    <input type="text" name="kecamatan" class="form-control" value="<?php echo htmlspecialchars($data['kecamatan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Kabupaten *</label>
                                    <input type="text" name="kabupaten" class="form-control" value="<?php echo htmlspecialchars($data['kabupaten']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Provinsi *</label>
                                    <input type="text" name="provinsi" class="form-control" value="<?php echo htmlspecialchars($data['provinsi']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Bantuan / Keterangan Lain</label>
                                    <input type="text" name="bantuan" class="form-control" value="<?php echo htmlspecialchars($data['bantuan']); ?>">
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-warning" style="background-color: #f4a100; color: white;"><i class="fas fa-save"></i> Perbarui Data KK</button>
                            <a href="data_kk.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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