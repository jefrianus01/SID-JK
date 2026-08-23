<?php
session_start();
include 'backend/koneksi.php';

// Jika sudah login, arahkan ke dashboard masing-masing
if (isset($_SESSION['username']) && isset($_SESSION['status_pengguna'])) {
    $role = $_SESSION['status_pengguna'];
    if ($role == 'Admin') { header("location: admin/dashboard.php"); exit; }
    else if ($role == 'Kepala Desa') { header("location: kepaladesa/dashboard.php"); exit; }
    else if ($role == 'Kepala Dusun') { header("location: dusun/dashboard.php"); exit; }
    else if ($role == 'RT' || $role == 'RW') { header("location: rtrw/dashboard.php"); exit; }
}

// 1. STATISTIK PENDUDUK BERDASARKAN DATABASE
$q_total = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk");
$d_total = mysqli_fetch_assoc($q_total);

$q_lki = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE jenis_kelamin='Laki-laki'");
$d_lki = mysqli_fetch_assoc($q_lki);

$q_prp = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE jenis_kelamin='Perempuan'");
$d_prp = mysqli_fetch_assoc($q_prp);

// 2. KELOMPOK UMUR LENGKAP (Berdasarkan tanggal_lahir)
// Anak-anak (0 - 12 tahun)
$q_anak = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 0 AND 12");
$d_anak = mysqli_fetch_assoc($q_anak);

// Remaja (13 - 21 tahun)
$q_remaja = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 13 AND 21");
$d_remaja = mysqli_fetch_assoc($q_remaja);

// Dewasa (22 - 59 tahun)
$q_dewasa = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 22 AND 59");
$d_dewasa = mysqli_fetch_assoc($q_dewasa);

// Lansia (60+ tahun)
$q_lansia = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM tabel_penduduk WHERE TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= 60");
$d_lansia = mysqli_fetch_assoc($q_lansia);

// 3. AMBIL DATA PROFIL DESA
$q_profil = mysqli_query($koneksi, "SELECT * FROM tabel_profil WHERE id_profil=1");
$profil = mysqli_fetch_assoc($q_profil);
$nama_desa = isset($profil['nama_desa']) ? $profil['nama_desa'] : 'As Manulea';
$kecamatan = isset($profil['kecamatan']) ? $profil['kecamatan'] : 'Sasitamean';
$kabupaten = isset($profil['kabupaten']) ? $profil['kabupaten'] : 'Malaka';
$nama_kades = isset($profil['nama_kades']) ? $profil['nama_kades'] : 'Kepala Desa';
$alamat_kantor = isset($profil['alamat_kantor']) ? $profil['alamat_kantor'] : 'Kantor Desa As Manulea';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Kependudukan - Desa <?php echo htmlspecialchars($nama_desa); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; display: flex; flex-direction: column; min-height: 100vh; }
        
        /* NAVBAR */
        .navbar { background: #0061f2; color: white; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar .brand { display: flex; align-items: center; gap: 15px; }
        .navbar .brand i { font-size: 32px; color: #ffc107; }
        .navbar .brand h1 { font-size: 20px; font-weight: 600; text-transform: uppercase; }
        .navbar .brand p { font-size: 12px; opacity: 0.85; }
        
        .btn-nav-login { background: #198754; color: white; border: none; padding: 8px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s; display: flex; align-items: center; gap: 8px; font-size: 14px; }
        .btn-nav-login:hover { background: #146c43; }

        /* HERO SECTION */
        .hero { background: linear-gradient(rgba(0, 97, 242, 0.85), rgba(0, 97, 242, 0.85)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80'); background-size: cover; background-position: center; color: white; padding: 50px 20px; text-align: center; }
        .hero h2 { font-size: 28px; margin-bottom: 10px; font-weight: 700; }
        .hero p { font-size: 15px; max-width: 700px; margin: 0 auto; opacity: 0.9; line-height: 1.6; }

        /* KONTEN UTAMA */
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; width: 100%; flex: 1; }
        
        .section-title { font-size: 20px; color: #333; margin-bottom: 20px; border-left: 4px solid #0061f2; padding-left: 10px; font-weight: 600; }
        
        /* PROFIL & TENTANG DESA */
        .about-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 40px; }
        @media(max-width: 800px) { .about-grid { grid-template-columns: 1fr; } }
        
        .card-info { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.08); }
        .card-info p { line-height: 1.7; color: #555; font-size: 14px; margin-bottom: 10px; }

        /* STATISTIK GRID */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0.15rem 1.75rem 0 rgba(33, 40, 50, 0.08); border-left: 4px solid #0061f2; display: flex; align-items: center; gap: 15px; }
        .stat-card i { font-size: 32px; color: #0061f2; }
        .stat-card h4 { font-size: 13px; color: #777; margin-bottom: 5px; }
        .stat-card h2 { font-size: 22px; color: #333; }

        /* MODAL POP-UP LOGIN */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; }
        .modal-box { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position: relative; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        
        .modal-close { position: absolute; top: 15px; right: 20px; font-size: 20px; cursor: pointer; color: #aaa; background: none; border: none; }
        .modal-close:hover { color: #333; }
        
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .btn-submit-login { width: 100%; background: #0061f2; color: white; border: none; padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: background 0.3s; }
        .btn-submit-login:hover { background: #004ecc; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; font-size: 13px; margin-bottom: 15px; }

        /* FOOTER */
        footer { background: #212832; color: white; text-align: center; padding: 20px; font-size: 13px; margin-top: auto; }
    </style>
</head>
<body>

    <!-- NAVBAR DENGAN TOMBOL LOGIN -->
    <div class="navbar">
        <div class="brand">
            <i class="fas fa-landmark"></i>
            <div>
                <h1>Desa <?php echo htmlspecialchars($nama_desa); ?></h1>
                <p>Kec. <?php echo htmlspecialchars($kecamatan); ?>, Kab. <?php echo htmlspecialchars($kabupaten); ?> - NTT</p>
            </div>
        </div>
        <button class="btn-nav-login" onclick="openLoginModal()">
            <i class="fas fa-sign-in-alt"></i> Login Sistem
        </button>
    </div>

    <!-- HERO / BANNER -->
    <div class="hero">
        <h2>Portal Resmi Kependudukan Desa <?php echo htmlspecialchars($nama_desa); ?></h2>
        <p>Pusat transparansi data demografi, kependudukan, dan layanan administrasi wilayah secara digital dan terintegrasi.</p>
    </div>

    <!-- KONTEN UTAMA BERANDA -->
    <div class="container">
        
        <!-- PROFIL & TENTANG DESA -->
        <div class="about-grid">
            <div class="card-info">
                <h3 class="section-title" style="margin-bottom: 15px;">Tentang Wilayah Desa</h3>
                <p>
                    Desa <strong><?php echo htmlspecialchars($nama_desa); ?></strong> terletak di wilayah Kecamatan <?php echo htmlspecialchars($kecamatan ? $kecamatan : 'Sasitamean'); ?>, Kabupaten <?php echo htmlspecialchars($kabupaten ? $kabupaten : 'Malaka'); ?>, Provinsi Nusa Tenggara Timur. 
                </p>
                <p>
                    Website Sistem Informasi Kependudukan ini dikembangkan untuk memudahkan pengelolaan data administrasi warga, mulai dari pencatatan kependudukan, sirkulasi (kelahiran, kematian, pindah, pendatang), hingga rekapitulasi data statistik umur penduduk secara transparan.
                </p>
            </div>

            <div class="card-info">
                <h3 class="section-title" style="margin-bottom: 15px;">Pemerintahan Desa</h3>
                <p><strong>Kepala Desa:</strong> <?php echo htmlspecialchars($nama_kades); ?></p>
                <p><strong>Alamat Kantor:</strong> <?php echo htmlspecialchars($alamat_kantor); ?></p>
                <p><strong>Kecamatan:</strong> <?php echo htmlspecialchars($kecamatan); ?></p>
                <p><strong>Kabupaten:</strong> <?php echo htmlspecialchars($kabupaten); ?></p>
            </div>
        </div>

        <!-- REKAPITULASI STATISTIK PENDUDUK LENGKAP -->
        <h3 class="section-title">Statistik Rekapitulasi Penduduk</h3>
        <div class="stats-grid">
            <div class="stat-card" style="border-left-color: #0061f2;">
                <i class="fas fa-users"></i>
                <div>
                    <h4>Total Seluruh Penduduk</h4>
                    <h2><?php echo $d_total['jml']; ?> Jiwa</h2>
                </div>
            </div>

            <div class="stat-card" style="border-left-color: #198754;">
                <i class="fas fa-male"></i>
                <div>
                    <h4>Penduduk Laki-Laki</h4>
                    <h2><?php echo $d_lki['jml']; ?> Jiwa</h2>
                </div>
            </div>

            <div class="stat-card" style="border-left-color: #d63384;">
                <i class="fas fa-female"></i>
                <div>
                    <h4>Penduduk Perempuan</h4>
                    <h2><?php echo $d_prp['jml']; ?> Jiwa</h2>
                </div>
            </div>
        </div>

        <!-- REKAPITULASI BERDASARKAN Kelompok Umur -->
        <h3 class="section-title">Rekapitulasi Berdasarkan Kelompok Umur</h3>
        <div class="stats-grid">
            <div class="stat-card" style="border-left-color: #ffc107;">
                <i class="fas fa-child"></i>
                <div>
                    <h4>Anak-Anak (0 - 12 Thn)</h4>
                    <h2><?php echo $d_anak['jml']; ?> Jiwa</h2>
                </div>
            </div>

            <div class="stat-card" style="border-left-color: #0dcaf0;">
                <i class="fas fa-user-graduate"></i>
                <div>
                    <h4>Remaja (13 - 21 Thn)</h4>
                    <h2><?php echo $d_remaja['jml']; ?> Jiwa</h2>
                </div>
            </div>

            <div class="stat-card" style="border-left-color: #6f42c1;">
                <i class="fas fa-user-tie"></i>
                <div>
                    <h4>Dewasa (22 - 59 Thn)</h4>
                    <h2><?php echo $d_dewasa['jml']; ?> Jiwa</h2>
                </div>
            </div>

            <div class="stat-card" style="border-left-color: #dc3545;">
                <i class="fas fa-user-clock"></i>
                <div>
                    <h4>Lansia (60+ Thn)</h4>
                    <h2><?php echo $d_lansia['jml']; ?> Jiwa</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL POP-UP FORM LOGIN -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-box">
            <button class="modal-close" onclick="closeLoginModal()">&times;</button>
            
            <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                <div class="alert-error">
                    Login gagal! Username atau password salah.
                </div>
            <?php endif; ?>

            <h2 style="font-size: 18px; margin-bottom: 10px; color: #333;"><i class="fas fa-lock"></i> Login Panel Sistem</h2>
            <p style="font-size: 12px; color: #666; margin-bottom: 20px;">Masukkan akun resmi Anda untuk masuk ke sistem.</p>
            
            <form action="proses_login.php" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-submit-login"><i class="fas fa-sign-in-alt"></i> Masuk Sistem</button>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Pemerintah Desa <?php echo htmlspecialchars($nama_desa); ?>. Hak Cipta Dilindungi.</p>
    </footer>

    <!-- JAVASCRIPT UNTUK MODAL LOGIN -->
    <script>
        const modal = document.getElementById('loginModal');

        function openLoginModal() {
            modal.style.display = 'flex';
        }

        function closeLoginModal() {
            modal.style.display = 'none';
        }

        // Jika terjadi error login, otomatis buka modalnya kembali agar pesan error terlihat
        <?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
            window.onload = function() {
                openLoginModal();
            }
        <?php endif; ?>

        // Tutup modal jika pengguna mengklik area luar kotak putih
        window.onclick = function(event) {
            if (event.target == modal) {
                closeLoginModal();
            }
        }
    </script>
</body>
</html>