<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }
include '../backend/koneksi.php'; 
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";

// Ambil warga aktif untuk dropdown
$q_penduduk = mysqli_query($koneksi, "SELECT nik, nama FROM tabel_penduduk WHERE status = 'Aktif' ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Pengajuan Surat - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- Sidebar copy dari data_pengajuan.php -->
        <div class="main-content">
            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-plus"></i> Buat Pengajuan Surat Baru</h2>
                </div>
                <div class="card-table" style="padding: 30px;">
                    <form action="proses_tambah_pengajuan.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-group">
                            <label>Pilih Pemohon (Warga) *</label>
                            <select name="pemohon" class="form-control" required>
                                <option value="">-- Cari Nama Warga --</option>
                                <?php while($p = mysqli_fetch_assoc($q_penduduk)): ?>
                                    <!-- Value yang dikirim adalah gabungan NIK dan Nama yang dipisah dengan tanda # -->
                                    <option value="<?php echo $p['nik'] . '#' . htmlspecialchars($p['nama']); ?>">
                                        <?php echo htmlspecialchars($p['nama']) . ' - NIK: ' . $p['nik']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Pengajuan *</label>
                            <input type="date" name="tanggal_pengajuan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis Surat Pengajuan *</label>
                            <select name="jenis_surat" class="form-control" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="Surat Keterangan Usaha (SKU)">Surat Keterangan Usaha (SKU)</option>
                                <option value="Surat Keterangan Domisili">Surat Keterangan Domisili</option>
                                <option value="Surat Pengantar SKCK">Surat Pengantar SKCK</option>
                                <option value="Surat Keterangan Tidak Mampu (SKTM)">Surat Keterangan Tidak Mampu (SKTM)</option>
                                <option value="Surat Keterangan Belum Menikah">Surat Keterangan Belum Menikah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Proses">Proses</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Pengajuan</button>
                            <a href="data_pengajuan.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Error -->
    <div class="modal-notif-overlay" id="modalNotif" style="display: <?php echo (!empty($pesan_error)) ? 'flex' : 'none'; ?>;">
        <div class="modal-notif-box">
            <i class="fas fa-exclamation-triangle icon-danger"></i><h3>Perhatian</h3><p><?php echo htmlspecialchars($pesan_error); ?></p>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>
</body>
</html>