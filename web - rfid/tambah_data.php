<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $id_kartu = $_POST['id_kartu'];
    $nama = $_POST['nama'];

    if ($role === 'guru') {
        $jabatan = $_POST['jabatan'];
        $stmt = $koneksi->prepare("INSERT INTO users (id_kartu, nama, jabatan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $id_kartu, $nama, $jabatan);
    } elseif ($role === 'murid') {
        $kelas = $_POST['kelas'];
        $nis = $_POST['nis'];
        $stmt = $koneksi->prepare("INSERT INTO murid (id_kartu, nama, kelas, nis) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id_kartu, $nama, $kelas, $nis);
    }

    if (isset($stmt) && $stmt->execute()) {
        $pesan = "✅ Data berhasil disimpan.";
    } else {
        $pesan = "❌ Gagal menyimpan data: " . $koneksi->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah Data via NFC</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      margin: 0;
      background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
      min-height: 100vh;
      display: flex;
    }
    .sidebar {
      width: 210px;
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
    }
    .sidebar h3 {
      color: #fff;
      text-align: center;
      margin-bottom: 28px;
      letter-spacing: 1px;
      font-size: 1.3rem;
      font-weight: 700;
      text-shadow: 0 2px 8px rgba(44,62,80,0.08);
    }
    .sidebar a {
      display: block;
      width: 85%;
      margin: 8px auto;
      padding: 13px 0;
      color: #fff;
      background: rgba(255,255,255,0.08);
      border-radius: 8px;
      text-decoration: none;
      text-align: center;
      font-weight: 500;
      font-size: 1rem;
      letter-spacing: 0.5px;
      transition: background 0.18s, color 0.18s, transform 0.18s;
    }
    .sidebar a:hover {
      background: #fff;
      color: #43e97b;
      transform: translateX(6px) scale(1.04);
      font-weight: bold;
    }
    .content {
      margin-left: 210px;
      padding: 38px 4vw 38px 4vw;
      flex: 1;
      min-height: 100vh;
      background: transparent;
    }
    .form-container {
      max-width: 480px;
      margin: 0 auto;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(44,62,80,0.12);
      padding: 32px 28px 28px 28px;
      margin-top: 38px;
      margin-bottom: 38px;
      animation: popIn 0.3s;
    }
    @keyframes popIn {
      0% { transform: scale(0.95); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    .form-container h2 {
      text-align: center;
      color: #388e3c;
      margin-bottom: 24px;
      letter-spacing: 1px;
      font-size: 1.5rem;
      font-weight: bold;
    }
    .form-group {
      margin-bottom: 18px;
    }
    label {
      display: block;
      margin-bottom: 7px;
      font-weight: bold;
      color: #388e3c;
      letter-spacing: 0.5px;
    }
    input, select {
      width: 100%;
      padding: 12px;
      border: 1.5px solid #4CAF50;
      border-radius: 6px;
      font-size: 16px;
      background: #f9f9f9;
      transition: border 0.2s, background 0.2s;
      margin-bottom: 2px;
    }
    input:focus, select:focus {
      border-color: #388e3c;
      background: #fff;
    }
    button[type="submit"], .nfc-section button {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #155724;
      padding: 12px 24px;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 12px;
      box-shadow: 0 4px 12px rgba(44,62,80,0.08);
      transition: background 0.2s, transform 0.2s;
      margin-bottom: 8px;
    }
    button[type="submit"]:hover, .nfc-section button:hover {
      background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
      transform: translateY(-2px) scale(1.03);
    }
    .nfc-section {
      background: #e3fcec;
      padding: 22px;
      margin-top: 28px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
      text-align: center;
    }
    .nfc-section h3 {
      color: #2e7d32;
      margin-bottom: 10px;
      font-size: 1.1rem;
    }
    .success, .error {
      font-size: 16px;
      border-radius: 6px;
      margin-bottom: 22px;
      text-align: center;
      font-weight: bold;
      letter-spacing: 0.5px;
      padding: 12px 0;
    }
    .success {
      background: #e0ffe6;
      color: #388e3c;
      border: 1.5px solid #43e97b;
    }
    .error {
      background: #ffe0e0;
      color: #e53935;
      border: 1.5px solid #ffb3b3;
    }
    small {
      color: #888;
      font-size: 0.95em;
      margin-top: 2px;
      display: block;
    }
    @media (max-width: 900px) {
      .sidebar {
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
        display: inline-block;
        width: auto;
        margin: 0 6px;
        padding: 10px 14px;
        font-size: 0.95rem;
      }
      .content {
        margin-left: 0;
        padding: 18px 2vw 18px 2vw;
      }
      .form-container {
        max-width: 98vw;
        padding: 18px 6vw;
      }
    }
    @media (max-width: 600px) {
      .form-container h2 { font-size: 1.1rem; }
      .sidebar a { padding: 8px 8px; }
      .form-container { padding: 12px 2vw; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3>Menu</h3>
  <a href="menu_utama.php">Menu Utama</a>
  <a href="guru.php">Data Guru</a>
  <a href="murid.php">Data Murid</a>
  <a href="tambah_data.php">Tambah Data</a>
  <a href="scan_kartu.php">Scan Kartu</a>
  <a href="log_rekap.php">Rekap Log Absensi</a>
  <a href="profile.php">Profile</a>
</div>

<div class="content">
  <div class="form-container">
    <h2>➕ Tambah Data</h2>

    <?php if ($pesan): ?>
      <div class="<?= strpos($pesan, '✅') !== false ? 'success' : 'error' ?>">
        <?= $pesan ?>
      </div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label>Jenis Pengguna:</label>
        <select name="role" required>
          <option value="guru">Guru</option>
          <option value="murid">Murid</option>
        </select>
      </div>

      <div class="form-group">
        <label>UID Kartu NFC:</label>
        <input type="text" name="id_kartu" id="uid" required readonly>
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

      <button type="submit">Simpan Data</button>
    </form>

    <div class="nfc-section">
      <h3>📡 Scan NFC</h3>
      <p>Tekan tombol di bawah ini untuk membuka scanner NFC:</p>
      <button type="button" onclick="startNFCScan()">Scan Kartu</button>
    </div>
  </div>
</div>

<script>
  // Sembunyikan form awal
  document.getElementById('guru-fields').style.display = 'none';

  // Toggle form berdasarkan role
  document.querySelector('[name="role"]').addEventListener('change', function () {
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
  if (document.querySelector('[name="role"]').value === 'murid') {
    document.getElementById('nis-field').style.display = 'block';
  } else {
    document.getElementById('nis-field').style.display = 'none';
  }

  function startNFCScan() {
    const uid = prompt("Masukkan UID kartu (simulasi NFC):");
    if (uid) {
      document.getElementById('uid').value = uid;
      alert("Kartu terbaca: " + uid);
    }
  }
</script>

</body>
</html>
