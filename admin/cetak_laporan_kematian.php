<?php
session_start();
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kematian - Desa As Manulea</title>
    <link rel="stylesheet" href="cetak_laporan_penduduk.css">
</head>
<body>
    <div class="container">
        
        <div class="header-page">
            <h1>DESA <span style="color: #555;">AS MANULEA</span></h1>
            <h2>LAPORAN REGISTER KEMATIAN</h2>
            <p>Kecamatan Sasitamean, Kabupaten Malaka, Provinsi Nusa Tenggara Timur</p>
            <p>Kode Pos: 85764</p>
        </div>

        <div class="section">
            <h3>Ringkasan Data</h3>
            <?php
            $q_mati = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kematian");
            $total_mati = $q_mati ? mysqli_fetch_assoc($q_mati)['jml'] : 0;
            ?>
            <table>
                <tr><th>Total Kematian Tercatat</th><td>: <?php echo $total_mati; ?> Jiwa</td></tr>
            </table>
        </div>

        <div class="section">
            <h3>Daftar Kematian Penduduk</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tgl Wafat</th>
                        <th style="width: 35%;">Nama Penduduk</th>
                        <th style="width: 25%;">Penyebab</th>
                        <th style="width: 20%;">Pelapor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = "SELECT k.tanggal_kematian, k.sebab, k.nama_pelapor, p.nik, p.nama, p.jenis_kelamin 
                              FROM tabel_kematian k
                              INNER JOIN tabel_penduduk p ON k.id_penduduk = p.id_penduduk
                              ORDER BY k.tanggal_kematian DESC";
                    $result = mysqli_query($koneksi, $query);
                    
                    if($result && mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)):
                            $tgl_mati = date('d-m-Y', strtotime($row['tanggal_kematian']));
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo $tgl_mati; ?></strong></td>
                        <td style="text-align: left;">
                            <?php echo htmlspecialchars($row['nama']); ?><br>
                            <span style="font-size: 10px; color: #666;">NIK: <?php echo htmlspecialchars($row['nik']); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($row['sebab']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_pelapor']); ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data kematian tercatat.</td>
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