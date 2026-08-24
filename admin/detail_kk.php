<?php
session_start();
if (!isset($_SESSION['username'])) { header("location: ../index.php"); exit; }
include '../backend/koneksi.php'; 

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id_kk = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$pesan_sukses = isset($_SESSION['sukses']) ? $_SESSION['sukses'] : "";
$pesan_error = isset($_GET['error']) ? $_GET['error'] : "";
unset($_SESSION['sukses']); 

// Ambil Data KK
$q_kk = mysqli_prepare($koneksi, "SELECT * FROM tabel_kk WHERE id_kk = ?");
mysqli_stmt_bind_param($q_kk, "i", $id_kk);
mysqli_stmt_execute($q_kk);
$data_kk = mysqli_fetch_assoc(mysqli_stmt_get_result($q_kk));

if (!$data_kk) {
    die("Data KK tidak ditemukan!");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail KK - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .modal-notif-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center; animation: fadeIn 0.2s ease; }
        .modal-notif-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; }
        .modal-notif-box i.icon-success { font-size: 50px; color: #198754; margin-bottom: 15px; }
        .modal-notif-box i.icon-danger { font-size: 50px; color: #dc3545; margin-bottom: 15px; }
        .modal-notif-box h3 { margin-bottom: 10px; color: #333; font-size: 20px; }
        .modal-notif-box p { color: #666; font-size: 14px; margin-bottom: 20px; line-height: 1.5; }
        .btn-modal-close { background: #0061f2; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .info-card { background: #f8f9fc; border-left: 4px solid #0061f2; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="layout-container">
        <!-- (Gunakan Sidebar Standarmu Di Sini) -->
        <div class="sidebar">
            <div class="sidebar-header"><div class="logo"><i class="fas fa-landmark"></i></div><h2>Desa As Manulea</h2></div>
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>Dashboard</span></a></li>
                <li><a href="data_penduduk.php"><i class="fas fa-users"></i> <span>Data Penduduk</span></a></li>
                <li><a href="data_kk.php" class="active"><i class="fas fa-id-card"></i> <span>Data KK</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-users"></i> Detail Anggota Keluarga</h2>
                </div>

                <div class="info-card">
                    <h3 style="margin: 0 0 10px 0;">No. KK : <?php echo htmlspecialchars($data_kk['no_kk']); ?></h3>
                    <p style="margin: 0; color: #555;">Kepala Keluarga Tercatat: <strong><?php echo htmlspecialchars($data_kk['nama']); ?></strong></p>
                </div>

                <!-- FORM TAMBAH ANGGOTA -->
                <div class="card-table" style="padding: 20px; margin-bottom: 20px; background: #fff;">
                    <h3 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Tambah Anggota ke KK Ini</h3>
                    <form action="proses_tambah_anggota.php" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="id_kk" value="<?php echo $id_kk; ?>">
                        
                        <div style="flex: 1; min-width: 250px;">
                            <label>Pilih Warga (Status Aktif) *</label>
                            <select name="id_penduduk" class="form-control" required>
                                <option value="">-- Pilih Penduduk --</option>
                                <?php
                                // Ambil warga aktif yang belum masuk ke KK manapun (Opsional: bisa dihapus NOT IN-nya jika satu orang boleh punya banyak KK, tapi biasanya tidak boleh)
                                $q_warga = mysqli_query($koneksi, "SELECT id_penduduk, nik, nama FROM tabel_penduduk WHERE status='Aktif' AND id_penduduk NOT IN (SELECT id_penduduk FROM tabel_anggota) ORDER BY nama ASC");
                                while($w = mysqli_fetch_assoc($q_warga)){
                                    echo "<option value='".$w['id_penduduk']."'>".$w['nik']." - ".$w['nama']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div style="flex: 1; min-width: 200px;">
                            <label>Hubungan Keluarga *</label>
                            <select name="hubungan_keluarga" class="form-control" required>
                                <option value="">-- Pilih Hubungan --</option>
                                <option value="Kepala Keluarga">Kepala Keluarga</option>
                                <option value="Istri">Istri</option>
                                <option value="Suami">Suami</option>
                                <option value="Anak">Anak</option>
                                <option value="Cucu">Cucu</option>
                                <option value="Orang Tua">Orang Tua</option>
                                <option value="Famili Lain">Famili Lain</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;"><i class="fas fa-plus"></i> Tambahkan</button>
                        </div>
                    </form>
                </div>

                <!-- DAFTAR ANGGOTA -->
                <div class="card-table">
                    <div class="card-header">
                        <h3>Daftar Anggota Keluarga</h3>
                    </div>
                    <?php
                    $q_anggota = mysqli_query($koneksi, "SELECT a.*, p.nik, p.nama, p.jenis_kelamin, p.tempat_lahir, p.tanggal_lahir FROM tabel_anggota a JOIN tabel_penduduk p ON a.id_penduduk = p.id_penduduk WHERE a.id_kk = '$id_kk' ORDER BY a.id_anggota ASC");
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Hubungan Keluarga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($q_anggota) > 0): $no=1; while($row = mysqli_fetch_assoc($q_anggota)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><strong><?php echo $row['nik']; ?></strong></td>
                                    <td><?php echo $row['nama']; ?></td>
                                    <td><?php echo $row['jenis_kelamin']; ?></td>
                                    <td><span class="badge" style="background:#0061f2; color:#fff; padding:5px 10px;"><?php echo $row['hubungan_keluarga']; ?></span></td>
                                    <td>
                                        <form action="proses_hapus_anggota.php" method="POST" style="display:inline" onsubmit="return confirm('Keluarkan warga ini dari KK?');">
                                            <input type="hidden" name="id_anggota" value="<?php echo $row['id_anggota']; ?>">
                                            <input type="hidden" name="id_kk" value="<?php echo $id_kk; ?>">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="6" style="text-align: center; padding: 20px;">Belum ada anggota dalam KK ini.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding: 20px;">
                        <a href="data_kk.php" class="btn" style="background-color: #6c757d; color: white;"><i class="fas fa-arrow-left"></i> Kembali ke Data KK</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL NOTIF -->
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