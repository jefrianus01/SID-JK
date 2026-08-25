-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2026 at 04:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kependudukan_asmanulea`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_anggota`
--

CREATE TABLE `tabel_anggota` (
  `id_anggota` int(11) NOT NULL,
  `id_kk` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  `hubungan_keluarga` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_dusun`
--

CREATE TABLE `tabel_dusun` (
  `id_dusun` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `ketua_dusun` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_kelahiran`
--

CREATE TABLE `tabel_kelahiran` (
  `id_kelahiran` int(11) NOT NULL,
  `nama_bayi` varchar(150) NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `nama_ortu` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_kelahiran`
--

INSERT INTO `tabel_kelahiran` (`id_kelahiran`, `nama_bayi`, `jenis_kelamin`, `tanggal_lahir`, `nama_ortu`) VALUES
(1, 'hero', 'Laki-laki', '2026-08-22', 'jefrianus tanggur');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_kematian`
--

CREATE TABLE `tabel_kematian` (
  `id_kematian` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  `tanggal_kematian` date NOT NULL,
  `penyebab` varchar(255) NOT NULL,
  `tempat_kematian` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_kepala_desa`
--

CREATE TABLE `tabel_kepala_desa` (
  `id_kepala_desa` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_kk`
--

CREATE TABLE `tabel_kk` (
  `id_kk` int(11) NOT NULL,
  `id_kepala_desa` int(20) DEFAULT NULL,
  `no_kk` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `jumlah_anggota` int(11) NOT NULL DEFAULT 1,
  `desa` varchar(50) DEFAULT NULL,
  `kecamatan` varchar(50) DEFAULT NULL,
  `kabupaten` varchar(50) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `bantuan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_kk`
--

INSERT INTO `tabel_kk` (`id_kk`, `id_kepala_desa`, `no_kk`, `nama`, `jumlah_anggota`, `desa`, `kecamatan`, `kabupaten`, `provinsi`, `bantuan`) VALUES
(1, 1, '8765432123456789', 'jefrianus tanggur', 1, 'As Manulea', 'Sasitamean', 'Malaka', 'Nusa Tenggara Timur', 'BLT'),
(2, 1, '0987654321321456', 'jefrianus tanggur', 4, 'As Manulea', 'Sasitamean', 'Malaka', 'Nusa Tenggara Timur', ''),
(3, 1, '12345678909876543', 'jefrianus tanggure', 0, 'As Manulea', 'Sasitamean', 'Malaka', 'Nusa Tenggara Timur', 'PKH');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_master_agama`
--

CREATE TABLE `tabel_master_agama` (
  `id_agama` int(11) NOT NULL,
  `nama_agama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_master_pekerjaan`
--

CREATE TABLE `tabel_master_pekerjaan` (
  `id_pekerjaan` int(11) NOT NULL,
  `nama_pekerjaan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_master_pendidikan`
--

CREATE TABLE `tabel_master_pendidikan` (
  `id_pendidikan` int(11) NOT NULL,
  `nama_pendidikan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pendatang`
--

CREATE TABLE `tabel_pendatang` (
  `id_pendatang` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `asal_wilayah` varchar(255) NOT NULL,
  `tanggal_datang` date NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_penduduk`
--

CREATE TABLE `tabel_penduduk` (
  `id_penduduk` int(20) NOT NULL,
  `id_kepala_desa` int(20) DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `status_perkawinan` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') DEFAULT NULL,
  `pendidikan` varchar(30) DEFAULT NULL,
  `pekerjaan` varchar(30) DEFAULT NULL,
  `status` enum('Aktif','Meninggal','Pindah') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_penduduk`
--

INSERT INTO `tabel_penduduk` (`id_penduduk`, `id_kepala_desa`, `nik`, `nama`, `tanggal_lahir`, `jenis_kelamin`, `tempat_lahir`, `agama`, `status_perkawinan`, `pendidikan`, `pekerjaan`, `status`) VALUES
(1, 1, '1234567891234567', 'dffdsa', '2026-08-13', 'Laki-laki', 'kshhs', 'Katolik', 'Belum Kawin', 's1', 'pns', 'Meninggal'),
(2, 1, '1234567890098098', 'jefrianus tanggur', '2002-01-30', 'Laki-laki', 'prang', 'Katolik', 'Belum Kawin', 's3', 'PEGAWAI SWASTA', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pengajuan`
--

CREATE TABLE `tabel_pengajuan` (
  `id_pengajuan` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `jenis_surat` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Proses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pengguna`
--

CREATE TABLE `tabel_pengguna` (
  `username` varchar(20) NOT NULL,
  `nama_pengguna` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status_pengguna` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_pengguna`
--

INSERT INTO `tabel_pengguna` (`username`, `nama_pengguna`, `password`, `status_pengguna`) VALUES
('admin1', 'Administrator Sistem', '123', 'Admin'),
('jefri1', 'jefri', '123', 'Kepala Desa');

-- --------------------------------------------------------

--
-- Table structure for table `tabel_pindah`
--

CREATE TABLE `tabel_pindah` (
  `id_pindah` int(11) NOT NULL,
  `id_penduduk` int(11) NOT NULL,
  `tanggal_pindah` date NOT NULL,
  `alamat_tujuan` text NOT NULL,
  `alasan_pindah` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tabel_profil`
--

CREATE TABLE `tabel_profil` (
  `id_profil` int(11) NOT NULL,
  `nama_desa` varchar(100) NOT NULL,
  `nama_kades` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kabupaten` varchar(100) NOT NULL,
  `provinsi` varchar(100) NOT NULL,
  `kode_pos` varchar(10) NOT NULL,
  `alamat_kantor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tabel_profil`
--

INSERT INTO `tabel_profil` (`id_profil`, `nama_desa`, `nama_kades`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `alamat_kantor`) VALUES
(1, 'As Manulea', 'Kepala Desa As Manulea', 'Sasitamean', 'Malaka', 'Nusa Tenggara Timur', '85764', 'Kantor Desa As Manulea, Kecamatan Sasitamean');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_anggota`
--
ALTER TABLE `tabel_anggota`
  ADD PRIMARY KEY (`id_anggota`);

--
-- Indexes for table `tabel_dusun`
--
ALTER TABLE `tabel_dusun`
  ADD PRIMARY KEY (`id_dusun`);

--
-- Indexes for table `tabel_kelahiran`
--
ALTER TABLE `tabel_kelahiran`
  ADD PRIMARY KEY (`id_kelahiran`);

--
-- Indexes for table `tabel_kematian`
--
ALTER TABLE `tabel_kematian`
  ADD PRIMARY KEY (`id_kematian`);

--
-- Indexes for table `tabel_kepala_desa`
--
ALTER TABLE `tabel_kepala_desa`
  ADD PRIMARY KEY (`id_kepala_desa`);

--
-- Indexes for table `tabel_kk`
--
ALTER TABLE `tabel_kk`
  ADD PRIMARY KEY (`id_kk`);

--
-- Indexes for table `tabel_master_agama`
--
ALTER TABLE `tabel_master_agama`
  ADD PRIMARY KEY (`id_agama`);

--
-- Indexes for table `tabel_master_pekerjaan`
--
ALTER TABLE `tabel_master_pekerjaan`
  ADD PRIMARY KEY (`id_pekerjaan`);

--
-- Indexes for table `tabel_master_pendidikan`
--
ALTER TABLE `tabel_master_pendidikan`
  ADD PRIMARY KEY (`id_pendidikan`);

--
-- Indexes for table `tabel_pendatang`
--
ALTER TABLE `tabel_pendatang`
  ADD PRIMARY KEY (`id_pendatang`);

--
-- Indexes for table `tabel_penduduk`
--
ALTER TABLE `tabel_penduduk`
  ADD PRIMARY KEY (`id_penduduk`);

--
-- Indexes for table `tabel_pengajuan`
--
ALTER TABLE `tabel_pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`);

--
-- Indexes for table `tabel_pengguna`
--
ALTER TABLE `tabel_pengguna`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tabel_pindah`
--
ALTER TABLE `tabel_pindah`
  ADD PRIMARY KEY (`id_pindah`);

--
-- Indexes for table `tabel_profil`
--
ALTER TABLE `tabel_profil`
  ADD PRIMARY KEY (`id_profil`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_anggota`
--
ALTER TABLE `tabel_anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_kelahiran`
--
ALTER TABLE `tabel_kelahiran`
  MODIFY `id_kelahiran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tabel_kematian`
--
ALTER TABLE `tabel_kematian`
  MODIFY `id_kematian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_kk`
--
ALTER TABLE `tabel_kk`
  MODIFY `id_kk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tabel_master_agama`
--
ALTER TABLE `tabel_master_agama`
  MODIFY `id_agama` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_master_pekerjaan`
--
ALTER TABLE `tabel_master_pekerjaan`
  MODIFY `id_pekerjaan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_master_pendidikan`
--
ALTER TABLE `tabel_master_pendidikan`
  MODIFY `id_pendidikan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_pendatang`
--
ALTER TABLE `tabel_pendatang`
  MODIFY `id_pendatang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_penduduk`
--
ALTER TABLE `tabel_penduduk`
  MODIFY `id_penduduk` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tabel_pengajuan`
--
ALTER TABLE `tabel_pengajuan`
  MODIFY `id_pengajuan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_pindah`
--
ALTER TABLE `tabel_pindah`
  MODIFY `id_pindah` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tabel_profil`
--
ALTER TABLE `tabel_profil`
  MODIFY `id_profil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
