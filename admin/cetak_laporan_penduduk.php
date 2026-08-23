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
    <title>Cetak Laporan Penduduk - Desa As Manulea</title>
    <!-- Memanggil CSS Cetak -->
    <link rel="stylesheet" href="cetak_laporan_penduduk.css">
</head>
<body>
    <div class="container">
        
        <!-- KOP SURAT DESA -->
        <div class="header-page">
            <h1>DESA <span style="color: #555;">AS MANULEA</span></h1>
            <h2>LAPORAN DATA PENDUDUK</h2>
            <p>Kecamatan Sasitamean, Kabupaten Malaka, Provinsi Nusa Tenggara Timur</p>
            <p>Kode Pos: 85764</p>
        </div>

        <!-- BAGIAN RINGKASAN STATISTIK -->
        <div class="section">
            <h3>Ringkasan Data</h3>
            <?php
            // Menghitung jumlah dari tabel yang sesuai
            $total = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk")->fetch_assoc()['jml'];
            $lk = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE jenis_kelamin='Laki-laki'")->fetch_assoc()['jml'];
            $pr = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE jenis_kelamin='Perempuan'")->fetch_assoc()['jml'];
            $kk = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_kk")->fetch_assoc()['jml'];
            ?>
            <table>
                <tr><th>Total Penduduk</th><td>: <?php echo $total; ?> orang</td></tr>
                <tr><th>Laki-Laki</th><td>: <?php echo $lk; ?> orang</td></tr>
                <tr><th>Perempuan</th><td>: <?php echo $pr; ?> orang</td></tr>
                <tr><th>Total Keluarga</th><td>: <?php echo $kk; ?> KK</td></tr>
            </table>
        </div>

        <!-- BAGIAN TABEL DATA PENDUDUK -->
        <div class="section">
            <h3>Daftar Penduduk</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat/Tgl Lahir</th>
                        <th>No KK</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    
                    // Query JOIN untuk mengambil data lintas tabel
                    $query = "SELECT p.nama, p.jenis_kelamin, p.tempat_lahir, p.tanggal_lahir, k.no_kk 
                              FROM tabel_penduduk p
                              LEFT JOIN tabel_anggota a ON p.id_penduduk = a.id_penduduk
                              LEFT JOIN tabel_kk k ON a.id_kk = k.id_kk
                              ORDER BY p.nama ASC";
                              
                    $result = mysqli_query($koneksi, $query);
                    
                    while($row = mysqli_fetch_assoc($result)):
                        // Jika penduduk belum memiliki KK, tampilkan tanda strip (-)
                        $no_kk = !empty($row['no_kk']) ? $row['no_kk'] : '-';
                        
                        // Format tanggal lahir agar lebih mudah dibaca
                        $tgl_lahir = date('d-m-Y', strtotime($row['tanggal_lahir']));
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['jenis_kelamin']); ?></td>
                        <td><?php echo htmlspecialchars($row['tempat_lahir']) . ', ' . $tgl_lahir; ?></td>
                        <td><?php echo htmlspecialchars($no_kk); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- FOOTER LAPORAN -->
        <div class="footer-page">
            <p>Dicetak pada: <?php echo date('d F Y, H:i:s'); ?> WITA</p>
            <p>Berdasarkan data tanggal: <?php echo date('d F Y'); ?></p>
            <p>— Dokumen Resmi Sistem Informasi Kependudukan —</p>
        </div>
        
    </div>

    <!-- Menjalankan jendela print otomatis -->
    <script>
        window.print();
    </script>
</body>
</html>