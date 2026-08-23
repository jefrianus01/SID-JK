<?php
session_start();
// Cek hak akses Kepala Desa
if (!isset($_SESSION['username']) || $_SESSION['status_pengguna'] != 'Kepala Desa') {
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
    <title>Cetak Laporan - Kepala Desa As Manulea</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../admin/dashboard.css">
    <style>
        @media print {
            .sidebar, .header, .btn-print, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            .card-table { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body>
    <div class="layout-container">
        
        <!-- SIDEBAR KEPALA DESA -->
        <div class="sidebar no-print">
            <div class="sidebar-header">
                <div class="logo"><i class="fas fa-landmark"></i></div>
                <h2>Desa As Manulea</h2>
                <p>KEPALA DESA</p>
            </div>
            
            <ul class="nav-menu">
                <li><a href="dashboard.php"><i class="fas fa-desktop"></i> <span>DASHBOARD</span></a></li>
                <li><a href="laporan.php" class="active"><i class="fas fa-print"></i> <span>CETAK LAPORAN</span></a></li>
                
                <li style="border-top: 1px solid rgba(255,255,255,0.1); margin: 15px 0; padding-top: 10px;"></li>
                <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> <span>LOGOUT</span></a></li>
            </ul>
        </div>

        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="header no-print">
                <h1>PANEL KEPALA DESA - AS MANULEA</h1>
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=198754&color=fff" alt="Avatar">
                    <span>Selamat Datang, <strong><?php echo isset($_SESSION['nama_pengguna']) ? $_SESSION['nama_pengguna'] : $_SESSION['username']; ?></strong></span>
                </div>
            </div>

            <div class="content-wrapper">
                <div class="welcome-section no-print" style="padding: 20px 30px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h2><i class="fas fa-print"></i> Cetak Laporan Kependudukan</h2>
                        <p style="margin-top:5px;">Laporan resmi rekapitulasi data kependudukan Desa As Manulea.</p>
                    </div>
                    <button onclick="window.print()" class="btn btn-primary btn-print" style="background-color: #198754; color: white; padding: 10px 20px; border-radius: 6px; border: none; font-weight: bold; cursor: pointer;">
                        <i class="fas fa-print"></i> Cetak / Simpan PDF
                    </button>
                </div>

                <!-- KOP SURAT LAPORAN (Hanya tampil saat dicetak) -->
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 3px double #333; padding-bottom: 15px;">
                    <h3 style="margin: 0; text-transform: uppercase;">Pemerintahan Kabupaten Malaka</h3>
                    <h3 style="margin: 5px 0; text-transform: uppercase;">Kecamatan Sasitamean</h3>
                    <h2 style="margin: 5px 0; text-transform: uppercase;">Kantor Desa As Manulea</h2>
                    <p style="margin: 0; font-size: 13px;">Laporan Rekapitulasi Data Kependudukan Tahun <?php echo date('Y'); ?></p>
                </div>

                <div class="card-table" style="padding: 25px;">
                    <div class="card-header">
                        <h3>Daftar Seluruh Penduduk Desa As Manulea</h3>
                    </div>

                    <?php
                    $query = "SELECT * FROM tabel_penduduk ORDER BY nama ASC";
                    $result = mysqli_query($koneksi, $query);
                    ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat, Tgl Lahir</th>
                                    <th>Agama</th>
                                    <th>Pekerjaan</th>
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
                                    <td><?php echo htmlspecialchars($row['nik']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($row['tanggal_lahir']))); ?></td>
                                    <td><?php echo htmlspecialchars($row['agama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['pekerjaan']); ?></td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else: 
                                ?>
                                <tr>
                                    <td colspan="7" style="text-align:center; padding: 20px;">Belum ada data penduduk tercatat di database.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- TANDA TANGAN KEPALA DESA -->
                    <div style="margin-top: 50px; display: flex; justify-content: flex-end; text-align: center;">
                        <div style="width: 250px;">
                            <p style="margin-bottom: 5px;">As Manulea, <?php echo date('d F Y'); ?></p>
                            <p style="margin-bottom: 70px; font-weight: bold;">Kepala Desa As Manulea</p>
                            <p style="font-weight: bold; text-decoration: underline; margin: 0;">
                                <?php echo isset($_SESSION['nama_pengguna']) ? $_SESSION['nama_pengguna'] : '_________________________'; ?>
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>
</html>