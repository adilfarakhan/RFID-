<?php
// Koneksi ke database akun (untuk login)
$koneksi_akun = new mysqli("localhost", "root", "", "akun");
if ($koneksi_akun->connect_error) {
    die("Koneksi ke database akun gagal: " . $koneksi_akun->connect_error);
}

// Koneksi ke database absensi (untuk data guru/murid)
$koneksi_absensi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi_absensi->connect_error) {
    die("Koneksi ke database absensi gagal: " . $koneksi_absensi->connect_error);
}

$pesan_akun = "";
$pesan_user = "";

// Proses tambah akun login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_akun'])) {
    $username = $koneksi_akun->real_escape_string($_POST['username']);
    $password = $koneksi_akun->real_escape_string($_POST['password']);
    // Jika ingin password di-hash, gunakan: $password = md5($password);

    $cek = $koneksi_akun->query("SELECT * FROM users WHERE username='$username'");
    if (!$cek) {
        die("Query error: " . $koneksi_akun->error);
    }
    if ($cek->num_rows > 0) {
        $pesan_akun = "Username sudah terdaftar!";
    } else {
        $simpan_akun = $koneksi_akun->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($simpan_akun) {
            $pesan_akun = "✅ Akun login berhasil ditambahkan!";
        } else {
            $pesan_akun = "❌ Gagal menambah akun login!";
        }
    }
}

// Proses tambah data user (guru/murid)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_user'])) {
    $role = $_POST['role'];
    $id_kartu = $_POST['id_kartu'];
    $nama = $_POST['nama'];

    if ($role === 'guru') {
        $jabatan = $_POST['jabatan'];
        $stmt = $koneksi_absensi->prepare("INSERT INTO users (id_kartu, nama, jabatan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id_kartu, $nama, $jabatan);
    } elseif ($role === 'murid') {
        $kelas = $_POST['kelas'];
        $nis = $_POST['nis'];
        $stmt = $koneksi_absensi->prepare("INSERT INTO murid (id_kartu, nama, kelas, nis) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id_kartu, $nama, $kelas, $nis);
    }

    $simpan_user = isset($stmt) ? $stmt->execute() : false;

    if ($simpan_user) {
        $pesan_user = "✅ Data user berhasil ditambahkan!";
    } else {
        $pesan_user = "❌ Gagal menambah data user!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Akun & Data User</title>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            min-height: 100vh;
            display: flex;
        }
        .sidebar {
            width: 60px;
            background: linear-gradient(180deg, #43e97b 0%, #38f9d7 100%);
            height: 100vh;
            padding-top: 28px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 16px rgba(44,62,80,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: width 0.25s;
            z-index: 10;
            overflow-x: hidden;
        }
        .sidebar:hover, .sidebar:focus-within { width: 210px; }
        .sidebar h3 {
            color: #fff;
            text-align: center;
            margin-bottom: 28px;
            letter-spacing: 1px;
            font-size: 1.3rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(44,62,80,0.08);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .sidebar:hover h3, .sidebar:focus-within h3 { opacity: 1; }
        .sidebar a {
            display: flex;
            align-items: center;
            width: 85%;
            margin: 8px auto;
            padding: 13px 0;
            color: #fff;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: background 0.18s, color 0.18s, transform 0.18s, padding 0.18s;
            overflow: hidden;
        }
        .sidebar a .icon {
            display: inline-block;
            width: 32px;
            text-align: center;
            font-size: 1.3rem;
            margin-right: 0;
            transition: margin 0.18s;
        }
        .sidebar a .text {
            opacity: 0;
            width: 0;
            margin-left: 0;
            transition: opacity 0.18s, width 0.18s, margin 0.18s;
            white-space: nowrap;
        }
        .sidebar:hover a .text, .sidebar:focus-within a .text {
            opacity: 1;
            width: auto;
            margin-left: 12px;
        }
        .sidebar:hover a, .sidebar:focus-within a {
            padding-left: 12px;
            padding-right: 12px;
        }
        .sidebar a:hover {
            background: #fff;
            color: #43e97b;
            transform: translateX(6px) scale(1.04);
            font-weight: bold;
        }
        .main {
            margin-left: 60px;
            padding: 38px 4vw 38px 4vw;
            flex: 1;
            min-height: 100vh;
            background: transparent;
            transition: margin-left 0.25s;
        }
        .sidebar:hover ~ .main,
        .sidebar:focus-within ~ .main {
            margin-left: 210px;
        }
        .container {
            max-width: 950px;
            margin: 40px auto;
            display: flex;
            gap: 32px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .form-box {
            background: #fff;
            flex: 1;
            padding: 36px 32px 28px 32px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
            min-width: 320px;
            max-width: 420px;
            margin-bottom: 18px;
            position: relative;
            overflow: hidden;
        }
        .form-box h2 {
            text-align: center;
            color: #2196f3;
            margin-bottom: 24px;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .msg {
            text-align: center;
            margin-bottom: 12px;
            color: #2e7d32;
            font-weight: bold;
            background: #e8f5e9;
            border-radius: 6px;
            padding: 8px 0;
        }
        .msg.err {
            color: #e53935;
            background: #ffebee;
        }
        .form-group { margin-bottom: 18px; }
        label {
            font-weight: 500;
            color: #219a6f;
            margin-bottom: 4px;
            display: block;
            letter-spacing: 0.5px;
        }
        input, select {
            width: 100%;
            padding: 12px 10px;
            margin-bottom: 2px;
            border: 1.5px solid #2196f3;
            border-radius: 7px;
            font-size: 1rem;
            background: #f9f9f9;
            transition: border 0.18s, background 0.18s;
        }
        input:focus, select:focus {
            border: 2px solid #43e97b;
            background: #fff;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 8px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            transition: background 0.18s;
        }
        button:hover {
            background: linear-gradient(90deg, #2196f3 0%, #43e97b 100%);
        }
        small {
            color: #888;
            font-size: 0.95em;
            margin-top: 2px;
            display: block;
        }
        .form-box::before {
            content: "";
            position: absolute;
            left: -60px;
            top: -60px;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            opacity: 0.07;
            border-radius: 50%;
            z-index: 0;
        }
        @media (max-width: 900px) {
            .sidebar,
            .sidebar:hover,
            .sidebar:focus-within {
                width: 100vw;
                height: auto;
                flex-direction: row;
                position: static;
                box-shadow: none;
                padding: 10px 0;
                margin-bottom: 18px;
            }
            .sidebar h3 { display: none; }
            .sidebar a {
                display: inline-flex;
                width: auto;
                margin: 0 6px;
                padding: 10px 14px;
                font-size: 0.95rem;
            }
            .sidebar a .text {
                opacity: 1;
                width: auto;
                margin-left: 8px;
            }
            .main,
            .sidebar:hover ~ .main,
            .sidebar:focus-within ~ .main {
                margin-left: 0;
                padding: 18px 2vw 18px 2vw;
            }
            .container { flex-direction: column; gap: 0; }
        }
        @media (max-width: 600px) {
            .form-box { padding: 18px 4vw 14px 4vw; min-width: 0; }
            .main h2 { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
  <h3>Menu</h3>
  <a href="menu_utama.php" style="background:#ff9800;">
    <span class="icon">🏠</span><span class="text">Menu Utama</span>
  </a>
  <a href="index.php" style="background:#00bcd4;">
    <span class="icon">🏡</span><span class="text">Beranda</span>
  </a>
  <a href="guru.php" style="background:#4caf50;">
    <span class="icon">👨‍🏫</span><span class="text">Data Guru</span>
  </a>
  <a href="murid.php" style="background:#2196f3;">
    <span class="icon">🎓</span><span class="text">Data Murid</span>
  </a>
  <a href="tambah_data.php" style="background:#e91e63;">
    <span class="icon">➕</span><span class="text">Tambah Data</span>
  </a>
  <a href="scan_kartu.php" style="background:#9c27b0;">
    <span class="icon">💳</span><span class="text">Scan Kartu</span>
  </a>
  <a href="log_rekap.php" style="background:#f44336;">
    <span class="icon">📋</span><span class="text">Rekap Log Absensi</span>
  </a>
  <a href="profile.php" style="background:#607d8b;">
    <span class="icon">👤</span><span class="text">Profile</span>
  </a>
  <a href="logout.php" style="background:#bdbdbd; color:#333;">
    <span class="icon">🚪</span><span class="text">Logout</span>
  </a>
</div>

<!-- Konten Utama -->
<div class="main">
    <h2>Tambah Akun & Data User</h2>
    <div class="container">
        <!-- Form Tambah Akun Login -->
        <div class="form-box">
            <h2>Tambah Akun Login</h2>
            <?php if ($pesan_akun): ?>
                <div class="msg<?= strpos($pesan_akun, 'berhasil') !== false ? '' : ' err' ?>"><?= htmlspecialchars($pesan_akun) ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="text" name="username" placeholder="Username" required autofocus>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="tambah_akun">Tambah Akun Login</button>
            </form>
        </div>

        <!-- Form Tambah Data User -->
        <div class="form-box">
            <h2>Tambah Data User</h2>
            <?php if ($pesan_user): ?>
                <div class="msg<?= strpos($pesan_user, 'berhasil') !== false ? '' : ' err' ?>"><?= htmlspecialchars($pesan_user) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label>Jenis Pengguna:</label>
                    <select name="role" id="role" required>
                        <option value="guru">Guru</option>
                        <option value="murid">Murid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>UID Kartu NFC:</label>
                    <input type="text" name="id_kartu" id="uid" required autofocus>
                    <small>Tempelkan kartu NFC ke reader</small>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap:</label>
                    <input type="text" name="nama" required>
                </div>
                <div id="murid-fields">
                    <div class="form-group">
                        <label>Kelas:</label>
                        <input type="text" name="kelas">
                    </div>
                    <div class="form-group" id="nis-field">
                        <label>NIS:</label>
                        <input type="text" name="nis">
                    </div>
                </div>
                <div id="guru-fields">
                    <div class="form-group">
                        <label>Jabatan:</label>
                        <input type="text" name="jabatan">
                    </div>
                </div>
                <button type="submit" name="tambah_user">Tambah Data User</button>
            </form>
        </div>
    </div>
</div>
<script>
    // Sembunyikan form awal
    document.getElementById('guru-fields').style.display = 'none';

    // Toggle form berdasarkan role
    document.getElementById('role').addEventListener('change', function () {
        if (this.value === 'murid') {
            document.getElementById('murid-fields').style.display = 'block';
            document.getElementById('guru-fields').style.display = 'none';
            document.getElementById('nis-field').style.display = 'block';
        } else {
            document.getElementById('murid-fields').style.display = 'none';
            document.getElementById('guru-fields').style.display = 'block';
            document.getElementById('nis-field').style.display = 'none';
        }
    });

    // Inisialisasi agar NIS hanya tampil untuk murid saat pertama kali load
    if (document.getElementById('role').value === 'murid') {
        document.getElementById('nis-field').style.display = 'block';
    } else {
        document.getElementById('nis-field').style.display = 'none';
    }
</script>
</body>
</html>
