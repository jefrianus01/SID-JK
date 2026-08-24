<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit;
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include '../backend/koneksi.php';

$pesan = "";

// PROSES TAMBAH PENGGUNA SISTEM BARU
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_pengguna'])) {
    // Verifikasi CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token invalid!");
    }
    
    $nama_pengguna   = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna']);
    $username        = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password        = mysqli_real_escape_string($koneksi, $_POST['password']);
    $status_pengguna = mysqli_real_escape_string($koneksi, $_POST['status_pengguna']);

    if (!empty($username) && !empty($password) && !empty($status_pengguna)) {
        // Cek apakah username sudah ada di database menggunakan prepared statement
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM tabel_pengguna WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $cek_result = mysqli_stmt_get_result($stmt);
        $cek = mysqli_num_rows($cek_result);

        if ($cek > 0) {
            $pesan = "Username sudah terdaftar, silakan gunakan username lain!";
        } else {
            // Insert menggunakan prepared statement
            $stmt2 = mysqli_prepare($koneksi, "INSERT INTO tabel_pengguna (username, nama_pengguna, password, status_pengguna) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt2, "ssss", $username, $nama_pengguna, $password, $status_pengguna);
            if (mysqli_stmt_execute($stmt2)) {
                echo "<script>alert('Pengguna sistem berhasil ditambahkan!'); window.location.href='pengguna.php';</script>";
                exit;
            } else {
                $pesan = "Gagal menambah pengguna";
            }
        }
    } else {
        $pesan = "Semua field wajib diisi!";
    }
}

// PROSES HAPUS PENGGUNA BERDASARKAN USERNAME
if (isset($_POST['hapus'])) {
    // Verifikasi CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token invalid!");
    }
    
    $username_hapus = mysqli_real_escape_string($koneksi, $_POST['hapus']);
    
    // Pengaman: Mencegah admin menghapus akunnya sendiri yang sedang aktif login
    if ($username_hapus == $_SESSION['username']) {
        echo "<script>alert('Peringatan: Tidak dapat menghapus akun yang sedang aktif digunakan!'); window.location.href='pengguna.php';</script>";
        exit;
    } else {
        // Hapus menggunakan prepared statement
        $stmt = mysqli_prepare($koneksi, "DELETE FROM tabel_pengguna WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username_hapus);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Pengguna berhasil dihapus dari database!'); window.location.href='pengguna.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal menghapus pengguna'); window.location.href='pengguna.php';</script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna Sistem - Admin Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .submenu { display: none; list-style: none; padding-left: 20px; background: rgba(0, 0, 0, 0.05); margin-bottom: 5px; }
        .submenu.show { display: block; }
        .submenu li a { font-size: 14px; padding: 8px 15px; opacity: 0.8; }
        .submenu li a:hover { opacity: 1; padding-left: 20px; }
        .has-submenu > a { display: flex; justify-content: space-between; align-items: center; }
        .toggle-icon { font-size: 12px; transition: transform 0.3s ease; }
        .toggle-icon.rotate { transform: rotate(180deg); }
        .menu-divider { border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px; }
        
        /* Grid Layout: Kiri Form Tambah, Kanan Tabel Data */
        .user-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
        @media(max-width: 992px) { .user-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2>
                <p>ADMIN</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>DASHBOARD</span></a></li>
                
                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-database"></i> <span>KELOLA DATA</span> <i class="fas fa-chevron-down toggle-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="data_penduduk.php"><i class="fas fa-users"></i> Data Penduduk</a></li>
                        <li><a href="data_kk.php"><i class="fas fa-id-card"></i> Data KK</a></li>
                    </ul>
                </li>

                <li class="has-submenu">
                    <a href="#" class="submenu-toggle"><i class="fas fa-sync-alt"></i> <span>SIRKULASI PENDUDUK</span> <i class="fas fa-chevron-down toggle-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="kelahiran.php"><i class="fas fa-baby"></i> Data Kelahiran</a></li>
                        <li><a href="kematian.php"><i class="fas fa-book-dead"></i> Data Kematian</a></li>
                        <li><a href="pindah.php"><i class="fas fa-truck-moving"></i> Data Pindah</a></li>
                        <li><a href="pendatang.php"><i class="fas fa-suitcase-rolling"></i> Data Pendatang</a></li>
                    </ul>
                </li>

                <li><a href="data_pengajuan.php"><i class="fas fa-file-signature"></i> <span>DATA PENGAJUAN</span></a></li>
                <li><a href="master_data.php"><i class="fas fa-server"></i> <span>MASTER DATA</span></a></li>
                <li><a href="data_dusun.php"><i class="fas fa-map-marker-alt"></i> <span>DATA DUSUN/RT</span></a></li>
                <li><a href="data_profil.php"><i class="fas fa-id-badge"></i> <span>DATA PROFIL</span></a></li>
                <li><a href="laporan.php"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>

                <li class="menu-divider"></li>

                <li><a href="setting.php"><i class="fas fa-cog"></i> <span>Setting</span></a></li>
                <li><a href="pengguna.php" class="active"><i class="fas fa-user-cog"></i> <span>PENGGUNA SISTEM</span></a></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="header">
                <h1>SISTEM INFORMASI DATA PENDUDUK DESA AS MANULEA</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=0061f2&color=fff" alt="Avatar">
                    <span>Halo, <strong><?php echo $_SESSION['username']; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="welcome-section" style="padding: 15px 30px; margin-bottom: 20px;">
                    <h2><i class="fas fa-user-cog"></i> Manajemen Pengguna Sistem</h2>
                    <p style="margin-top:5px;">Kelola akun dan hak akses pengguna yang terdaftar di dalam database.</p>
                </div>

                <?php if(!empty($pesan)): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
                        <?php echo $pesan; ?>
                    </div>
                <?php endif; ?>

                <div class="user-grid">
                    
                    <!-- KOTAK KIRI: FORM TAMBAH PENGGUNA -->
                    <div class="card-table" style="padding: 25px; height: fit-content;">
                        <div class="card-header" style="margin-bottom: 15px;">
                            <h3><i class="fas fa-user-plus"></i> Tambah Pengguna Baru</h3>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 13px; font-weight: bold;">Nama Lengkap *</label>
                                <input type="text" name="nama_pengguna" class="form-control" placeholder="Nama lengkap" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 13px; font-weight: bold;">Username *</label>
                                <input type="text" name="username" class="form-control" placeholder="Username login" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 12px;">
                                <label style="font-size: 13px; font-weight: bold;">Password *</label>
                                <input type="text" name="password" class="form-control" placeholder="Kata sandi" required style="width: 100%; padding: 8px; margin-top: 5px;">
                            </div>

                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="font-size: 13px; font-weight: bold;">Status / Peran (Role) *</label>
                                <select name="status_pengguna" class="form-control" required style="width: 100%; padding: 8px; margin-top: 5px;">
                                    <option value="">-- Pilih Peran --</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Kepala Desa">Kepala Desa</option>
                                    <option value="Kepala Dusun">Kepala Dusun</option>
                                    <option value="RT">RT</option>
                                    <option value="RW">RW</option>
                                </select>
                            </div>

                            <button type="submit" name="tambah_pengguna" class="btn btn-primary" style="width: 100%; padding: 10px; font-weight: bold; border-radius: 6px;">
                                <i class="fas fa-save"></i> Simpan Pengguna
                            </button>
                        </form>
                    </div>

                    <!-- KOTAK KANAN: TABEL DAFTAR PENGGUNA -->
                    <div class="card-table">
                        <div class="card-header">
                            <h3>Daftar Pengguna Sistem</h3>
                        </div>
                        <?php
                        // Mengambil data dari tabel_pengguna database kependudukan_asmanulea
                        $query = "SELECT * FROM tabel_pengguna ORDER BY username ASC";
                        $result = mysqli_query($koneksi, $query);
                        ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama & Username</th>
                                        <th>Peran (Status)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if($result && mysqli_num_rows($result) > 0): 
                                        $no=1; 
                                        while($row = mysqli_fetch_assoc($result)): 
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['nama_pengguna']); ?></strong><br>
                                            <span style="color: #666; font-size: 12px;"><i class="fas fa-user"></i> <?php echo htmlspecialchars($row['username']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background:#e8f4fd; color:#0061f2; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                <?php echo htmlspecialchars($row['status_pengguna']); ?>
                                            </span>
                                        </td>
<td>
                                            <!-- Form Hapus menggunakan POST method -->
                                            <form method="POST" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus pengguna <?php echo htmlspecialchars($row['username']); ?> ini?');">
                                                <input type="hidden" name="hapus" value="<?php echo urlencode($row['username']); ?>">
                                                <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </tr>
                                    <?php 
                                        endwhile; 
                                    else: 
                                    ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center; padding: 20px;">Belum ada pengguna terdaftar di database.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toggles = document.querySelectorAll('.submenu-toggle');
            toggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    var submenu = this.nextElementSibling;
                    var icon = this.querySelector('.toggle-icon');
                    submenu.classList.toggle('show');
                    icon.classList.toggle('rotate');
                });
            });
        });
    </script>
</body>
</html>