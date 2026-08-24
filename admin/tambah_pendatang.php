<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); }
include '../backend/koneksi.php'; 
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pendatang - Admin</title>
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
        <!-- Sidebar (Omitted for brevity, paste standard sidebar here) -->
        <div class="main-content">
            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-plus"></i> Tambah Data Pendatang</h2>
                </div>
                <div class="card-table" style="padding: 30px;">
                    <form action="proses_tambah_pendatang.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="form-group">
                            <label>NIK Pendatang *</label>
                            <input type="number" name="nik" class="form-control" placeholder="16 Digit NIK" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control" placeholder="Nama Sesuai KTP" required>
                        </div>
                        <div class="form-group">
                            <label>Asal Wilayah (Desa/Kec/Kab Asal) *</label>
                            <input type="text" name="asal_wilayah" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Datang *</label>
                            <input type="date" name="tanggal_datang" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Keterangan (Tujuan Kedatangan)</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="Cth: Ikut keluarga, Bekerja, dll">
                        </div>

                        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                            <a href="pendatang.php" class="btn" style="background-color: #e3e6ec; color: #333;"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-notif-overlay" id="modalNotif" style="display: <?php echo (!empty($pesan_error)) ? 'flex' : 'none'; ?>;">
        <div class="modal-notif-box">
            <i class="fas fa-exclamation-triangle icon-danger"></i><h3>Perhatian</h3><p><?php echo htmlspecialchars($pesan_error); ?></p>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>
</body>
</html>