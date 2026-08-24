<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
include '../backend/koneksi.php'; 

$pesan_sukses = isset($_SESSION['sukses']) ? $_SESSION['sukses'] : "";
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
unset($_SESSION['sukses']); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kelahiran - Admin Desa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        /* (Gunakan CSS modal notifikasi yang sama seperti di file pendatang.php) */
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; animation: fadeIn 0.2s ease; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-success { font-size: 50px; color: #198754; margin-bottom: 15px; }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- Copy Paste Sidebar Menu Lengkap Di Sini -->
        
        <div class="main-content">
            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-baby"></i> Pengelolaan Data Kelahiran</h2>
                </div>

                <div style="margin-bottom: 20px;">
                    <a href="tambah_kelahiran.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Data Kelahiran</a>
                </div>

                <div class="card-table">
                    <div class="card-header"><h3>Daftar Bayi Lahir</h3></div>
                    <?php
                    $query = "SELECT * FROM tabel_kelahiran ORDER BY tanggal_lahir DESC";
                    $result = mysqli_query($koneksi, $query);
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Bayi</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Nama Orang Tua / KK</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($result && mysqli_num_rows($result) > 0): $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['nama_bayi']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($row['tanggal_lahir'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['nama_ortu']); ?></td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="5" style="text-align: center; padding: 20px;">Belum ada data kelahiran.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
                <i class="fas fa-exclamation-triangle icon-danger"></i><h3>Perhatian</h3><p><?php echo htmlspecialchars($pesan_error); ?></p>
            <?php endif; ?>
            <button class="btn-modal-close" onclick="document.getElementById('modalNotif').style.display='none'">OK</button>
        </div>
    </div>
</body>
</html>