<?php
session_start();
if (!isset($_SESSION['username'])) {
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
    <title>Cetak Laporan Penduduk - Desa As Manulea</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 20px 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2, .header h3, .header h4 {
            margin: 2px 0;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
        }
        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 8px;
        }
        .table-data th {
            background-color: #f2f2f2;
        }
        .signature-area {
            width: 300px;
            float: right;
            text-align: center;
            margin-top: 40px;
        }
        .signature-area p {
            margin: 5px 0;
        }
        .signature-name {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
        /* CSS ini mengatur agar saat di-print, tabel rapi dan tidak terpotong */
        @media print {
            @page { margin: 1cm; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()"> <!-- Script ini otomatis memunculkan pop-up Print -->

    <div class="header">
        <h2>PEMERINTAH KABUPATEN MALAKA</h2>
        <h3>KECAMATAN SASITAMEAN</h3>
        <h4>DESA AS MANULEA</h4>
        <p style="font-size: 12px; margin-top: 5px;">Alamat: Kantor Desa As Manulea, Kec. Sasitamean, Kab. Malaka, NTT</p>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">LAPORAN DATA PENDUDUK AKTIF</h3>

    <table class="table-data">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>Agama</th>
                <th>Pekerjaan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = mysqli_query($koneksi, "SELECT * FROM tabel_penduduk WHERE status = 'Aktif' ORDER BY nama ASC");
            if(mysqli_num_rows($query) > 0){
                $no = 1;
                while($row = mysqli_fetch_assoc($query)){
                    $ttl = $row['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($row['tanggal_lahir']));
                    echo "<tr>
                            <td style='text-align: center;'>".$no++."</td>
                            <td>".$row['nik']."</td>
                            <td>".$row['nama']."</td>
                            <td style='text-align: center;'>".$row['jenis_kelamin']."</td>
                            <td>".$ttl."</td>
                            <td>".$row['agama']."</td>
                            <td>".$row['pekerjaan']."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center;'>Tidak ada data.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Kolom Tanda Tangan Kepala Desa -->
    <div class="signature-area">
        <p>As Manulea, <?php echo date('d F Y'); ?></p>
        <p>Kepala Desa As Manulea</p>
        <div class="signature-name">
            ( NAMA KEPALA DESA )
        </div>
        <p>NIP. .......................................</p>
    </div>

</body>
</html>