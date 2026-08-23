<?php
session_start();

// Panggil koneksi langsung dengan mundur satu folder (../) lalu masuk ke backend
include '../backend/koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan KK - Desa As Manulea</title>
    <!-- Memanggil CSS Cetak yang sudah ada -->
    <link rel="stylesheet" href="cetak_laporan_penduduk.css">
</head>
<body>
    <div class="container">
        
        <!-- KOP SURAT DESA -->
        <div class="header-page">
            <h1>DESA <span style="color: #555;">AS MANULEA</span></h1>
            <h2>LAPORAN REKAPITULASI KARTU KELUARGA (KK)</h2>
            <p>Kecamatan Sasitamean, Kabupaten Malaka, Provinsi Nusa Tenggara Timur</p>
            <p>Kode Pos: 85764</p>
        </div>

        <!-- BAGIAN RINGKASAN STATISTIK -->
        <div class="section">
            <h3>Ringkasan Data</h3>
            <?php
            // Menghitung jumlah total KK
            $q_kk = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kk");
            $total_kk = $q_kk ? mysqli_fetch_assoc($q_kk)['jml'] : 0;
            
            // Menghitung KK yang menerima bantuan
            $q_bantuan = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kk WHERE bantuan != '-' AND bantuan != ''");
            $total_bantuan = $q_bantuan ? mysqli_fetch_assoc($q_bantuan)['jml'] : 0;
            ?>
            <table>
                <tr><th>Total Kepala Keluarga</th><td>: <?php echo $total_kk; ?> KK</td></tr>
                <tr><th>Penerima Bantuan Sosial</th><td>: <?php echo $total_bantuan; ?> KK</td></tr>
            </table>
        </div>

        <!-- BAGIAN TABEL DATA KK -->
        <div class="section">
            <h3>Daftar Kepala Keluarga</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nomor KK</th>
                        <th style="width: 30%;">Nama Kepala Keluarga</th>
                        <th style="width: 15%;">Jml. Anggota</th>
                        <th style="width: 25%;">Keterangan Bantuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // Query untuk mengambil data KK sekaligus menghitung jumlah anggotanya
                    $query = "SELECT k.no_kk, k.nama as ketua_keluarga, k.bantuan, 
                                     (SELECT COUNT(*) FROM tabel_anggota a WHERE a.id_kk = k.id_kk) as jumlah_anggota 
                              FROM tabel_kk k 
                              ORDER BY k.nama ASC";
                              
                    $result = mysqli_query($koneksi, $query);
                    
                    if($result && mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['no_kk']); ?></strong></td>
                        <td style="text-align: left;"><?php echo htmlspecialchars($row['ketua_keluarga']); ?></td>
                        <td><?php echo $row['jumlah_anggota']; ?> Orang</td>
                        <td>
                            <?php 
                                echo (!empty($row['bantuan']) && $row['bantuan'] != '-') ? htmlspecialchars($row['bantuan']) : '-'; 
                            ?>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data Kartu Keluarga.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER LAPORAN -->
        <div class="footer-page">
            <p>Dicetak oleh: Admin | Waktu Cetak: <?php echo date('d F Y, H:i:s'); ?> WITA</p>
            <p>— Dokumen Resmi Sistem Informasi Kependudukan Desa As Manulea —</p>
            
            <!-- Tempat Tanda Tangan Kepala Desa -->
            <div style="margin-top: 50px; text-align: right; padding-right: 30px;">
                <p>As Manulea, <?php echo date('d F Y'); ?></p>
                <p>Kepala Desa As Manulea,</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">.........................................</p>
            </div>
        </div>
        
    </div>

    <!-- Menjalankan jendela print otomatis -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>