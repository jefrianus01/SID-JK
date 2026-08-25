# AGENTS.md
# SISTEM INFORMASI DESA

## Project Overview
PHP-based web application for **Village (Desa) Population Information System** (Sistem Informasi Kependudukan Desa). Pure PHP, no framework. Manages penduduk, KK, kelahiran, kematian, pindah, pendatang, and master reference data for Desa As Manulea, NTT.

## Setup & Run
- **Web server**: Apache or Nginx with PHP 8.0+ (XAMPP/WAMP/LAMP recommended)
- **Database**: MariaDB 10.4+; create database `kependudukan_asmanulea` and import `kependudukan_asmanulea.sql`
- **Configure**: Verify `backend/koneksi.php` — defaults to `localhost`/`root`/`empty password`/database `kependudukan_asmanulea`
- **Start**: Place files in web root, visit `http://localhost/<folder>/index.php`
- **Default credentials**:
  - `admin1` / `123` → role: **Admin** → dashboard `admin/dashboard.php`
  - `jefri1` / `123` → role: **Kepala Desa** → dashboard `kepaladesa/dashboard.php`

## Architecture & Key Files
- **Entry point**: `index.php` — homepage with login modal, also displays stats (total/sex/age-group populasi) and desa profil from `tabel_profil`
- **Auth flow**: `proses_login.php` → prepared SELECT on `tabel_pengguna` → session sets `$_SESSION['username']` and `$_SESSION['status_pengguna']` → redirects by role
- **CSRF**: Token generated in session (`bin2hex(random_bytes(32))`), embedded as hidden field in login form
- **Database**: 17 tables in `kependudukan_asmanulea`:
  - `tabel_penduduk` (core population), `tabel_kk`, `tabel_kelahiran`, `tabel_kematian`, `tabel_pindah`, `tabel_pendatang`, `tabel_pengajuan`, `tabel_pengguna`, 3 master reference tables, `tabel_profil` (single row, id_profil=1)
- **No build/lint/typecheck**: This is a flat PHP project with no package manifests, dependency manager, or compiled assets. There are no test scripts, code quality hooks, or CI pipelines.
- **Report printers**: `admin/cetak_laporan_*.php` — PDF/print endpoints for penduduk, KK, kelahiran, kematian

## Role-Based Access
| Role | Default User | Destination |
|------|-------------|-------------|
| Admin | admin1 / 123 | admin/dashboard.php |
| Kepala Desa | jefri1 / 123 | kepaladesa/dashboard.php |
| Kepala Dusun | (not in default data) | dusun/dashboard.php |
| RT/RW | (not in default data) | rtrw/dashboard.php |

## Common Operations
- **Login**: Open `index.php`, enter credentials, submit → `proses_login.php` validates → redirects
- **CRUD**: All admin/data entry flows use `admin/tambah_*.php` → `admin/proses_tambah_*.php` → `admin/data_*.php` pattern
- **Profil**: Data desa stored in `tabel_profil` (single row, id_profil=1). Update via `admin/data_profil.php`
- **Database introspection**: `cek_tables.php` lists all tables; `cek_structure.php` outputs DESCRIBE for `tabel_penduduk`

## Gotchas
- Passwords in `tabel_pengguna` are **plain text** (login checks `$password === $row['password']`). Do not hash removal without also updating the login check or hashing existing records.
- `tabel_kk.jumlah_anggota` has DEFAULT 1 — inserting without specifying it will default to 1.
- CSRF token must be included in any POST form that submits to `proses_login.php` or custom endpoints (token lives in `$_SESSION['csrf_token']`).
- No database migration tooling — schema changes require manual SQL edits to `kependudukan_asmanulea.sql` and potentially `backend/koneksi.php`.