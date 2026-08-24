<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Memanggil koneksi
include '../backend/koneksi.php'; 

// Membuat Token CSRF jika belum ada
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pesan_sukses = "";
$pesan_error = "";

// PROSES HAPUS PENDUDUK
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_penduduk'])) {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        $id_hapus = (int)$_POST['id_penduduk'];
        
        $query_hapus = "DELETE FROM tabel_penduduk WHERE id_penduduk = $id_hapus";
        if (mysqli_query($koneksi, $query_hapus)) {
            $_SESSION['sukses'] = "Data penduduk berhasil dihapus dari sistem.";
            header("location: data_penduduk.php");
            exit;
        } else {
            $pesan_error = "Gagal menghapus data: " . mysqli_error($koneksi);
        }
    } else {
        $pesan_error = "Validasi keamanan gagal (CSRF Token tidak valid).";
    }
}

// Menangkap pesan sukses dari session
if (isset($_SESSION['sukses'])) {
    $pesan_sukses = $_SESSION['sukses'];
    unset($_SESSION['sukses']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk - Admin Desa As Manulea</title>
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
        .modal-notif-box i.icon-success {
            font-size: 50px;
            color: #198754;
            margin-bottom: 15px;
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
                                            
                                            <form method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus data penduduk ini?');">
                                                <input type="hidden" name="id_penduduk" value="<?php echo $row['id_penduduk']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                                <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                            </form>
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

    <!-- MODAL POP-UP NOTIFIKASI DI TENGAH HALAMAN -->
    <div class="modal-notif-overlay" id="modalNotif" style="display: <?php echo (!empty($pesan_sukses) || !empty($pesan_error)) ? 'flex' : 'none'; ?>;">
        <div class="modal-notif-box">
            <?php if (!empty($pesan_sukses)): ?>
                <i class="fas fa-check-circle icon-success"></i>
                <h3>Berhasil!</h3>
                <p><?php echo htmlspecialchars($pesan_sukses); ?></p>
            <?php elseif (!empty($pesan_error)): ?>
                <i class="fas fa-exclamation-triangle icon-danger"></i>
                <h3>Terjadi Kesalahan</h3>
                <p><?php echo htmlspecialchars($pesan_error); ?></p>
            <?php endif; ?>
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