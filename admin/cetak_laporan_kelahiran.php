<?php
session_start();
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kelahiran - Desa As Manulea</title>
    <link rel="stylesheet" href="cetak_laporan_penduduk.css">
</head>
<body>
    <div class="container">
        
        <div class="header-page">
            <h1>DESA <span style="color: #555;">AS MANULEA</span></h1>
            <h2>LAPORAN REGISTER KELAHIRAN</h2>
            <p>Kecamatan Sasitamean, Kabupaten Malaka, Provinsi Nusa Tenggara Timur</p>
            <p>Kode Pos: 85764</p>
        </div>

        <div class="section">
            <h3>Ringkasan Data</h3>
            <?php
            $q_total = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kelahiran");
            $total = $q_total ? mysqli_fetch_assoc($q_total)['jml'] : 0;
            
            $q_lk = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kelahiran WHERE jenis_kelamin='Laki-laki'");
            $lk = $q_lk ? mysqli_fetch_assoc($q_lk)['jml'] : 0;
            
            $q_pr = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kelahiran WHERE jenis_kelamin='Perempuan'");
            $pr = $q_pr ? mysqli_fetch_assoc($q_pr)['jml'] : 0;
            ?>
            <table>
                <tr><th>Total Kelahiran Tercatat</th><td>: <?php echo $total; ?> Jiwa</td></tr>
                <tr><th>Bayi Laki-Laki</th><td>: <?php echo $lk; ?> Anak</td></tr>
                <tr><th>Bayi Perempuan</th><td>: <?php echo $pr; ?> Anak</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Daftar Kelahiran</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Tgl Lahir</th>
                        <th style="width: 30%;">Nama Bayi</th>
                        <th style="width: 15%;">J. Kelamin</th>
                        <th style="width: 30%;">Nama Orang Tua</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT k.nama as nama_bayi, k.jenis_kelamin, k.tanggal_lahir, p.nama as nama_orang_tua 
                              FROM tabel_kelahiran k
                              LEFT JOIN tabel_penduduk p ON k.id_penduduk = p.id_penduduk
                              ORDER BY k.tanggal_lahir DESC";
                    $result = mysqli_query($koneksi, $query);
                    
                    if($result && mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)):
                            $tgl_lahir = date('d-m-Y', strtotime($row['tanggal_lahir']));
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $tgl_lahir; ?></strong></td>
                        <td style="text-align: left;"><?php echo htmlspecialchars($row['nama_bayi']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                        <td style="text-align: left;"><?php echo htmlspecialchars($row['nama_orang_tua']); ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data kelahiran tercatat.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer-page">
            <p>Dicetak oleh: Admin | Waktu Cetak: <?php echo date('d F Y, H:i:s'); ?> WITA</p>
            <p>— Dokumen Resmi Sistem Informasi Kependudukan Desa As Manulea —</p>
            <div style="margin-top: 50px; text-align: right; padding-right: 30px;">
                <p>As Manulea, <?php echo date('d F Y'); ?></p>
                <p>Kepala Desa As Manulea,</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">.........................................</p>
            </div>
        </div>
    </div>
    <script>window.onload = function() { window.print(); }</script>
</body>
</html>