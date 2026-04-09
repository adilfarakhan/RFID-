<?php
$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Data Murid</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    .sidebar:hover, .sidebar:focus-within {
      width: 210px;
    }
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
    .sidebar:hover h3, .sidebar:focus-within h3 {
      opacity: 1;
    }
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
    .main h2 {
      color: #219a6f;
      margin-bottom: 18px;
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: 1px;
    }
    table {
      margin-top: 10px;
      border-collapse: collapse;
      width: 100%;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 18px rgba(44,62,80,0.08);
    }
    th, td {
      border: none;
      padding: 12px 8px;
      text-align: center;
    }
    th {
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    th:nth-child(1) { background: #ff9800; }
    th:nth-child(2) { background: #4caf50; }
    th:nth-child(3) { background: #2196f3; }
    th:nth-child(4) { background: #e91e63; }
    th:nth-child(5) { background: #9c27b0; }
    th:nth-child(6) { background: #f44336; }

    td:nth-child(1) { background: #fff3e0; }
    td:nth-child(2) { background: #e8f5e9; }
    td:nth-child(3) { background: #e3f2fd; }
    td:nth-child(4) { background: #fce4ec; }
    td:nth-child(5) { background: #f3e5f5; }
    td:nth-child(6) { background: #ffebee; }

    tbody tr:nth-child(odd) { background: #f4f9f4; }
    tbody tr:nth-child(even) { background: #e3f2fd; }

    tr:hover {
      background-color: #e0f7fa;
      transition: background 0.15s;
    }
    td {
      font-size: 0.98rem;
      color: #333;
    }
    .edit-btn {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 7px 18px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, transform 0.18s;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
    }
    .edit-btn:hover {
      background: #fff;
      color: #43e97b;
      border: 1.5px solid #43e97b;
      transform: scale(1.05);
    }
    .export-btn {
      margin-top: 18px;
      padding: 10px 22px;
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
      transition: background 0.18s, color 0.18s, transform 0.18s;
      display: block;
    }
    .export-btn:hover {
      background: #fff;
      color: #43e97b;
      border: 1.5px solid #43e97b;
      transform: scale(1.04);
    }
    /* Popup */
    #editPopup {
      display: none;
      position: fixed;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background: rgba(0,0,0,0.4);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
    #editPopup .popup-content {
      background: #fff;
      padding: 30px 24px 18px 24px;
      border-radius: 14px;
      min-width: 320px;
      max-width: 95vw;
      position: relative;
      box-shadow: 0 8px 32px rgba(44,62,80,0.13);
      animation: popIn 0.25s;
    }
    @keyframes popIn {
      0% { transform: scale(0.8); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
    #editPopup h3 {
      color: #219a6f;
      margin-bottom: 18px;
      text-align: center;
    }
    #editPopup label {
      font-weight: 500;
      color: #219a6f;
      margin-bottom: 4px;
      display: block;
      text-align: left;
    }
    #editPopup input[type="text"] {
      width: 100%;
      padding: 9px;
      border-radius: 6px;
      border: 1.5px solid #43e97b;
      margin-bottom: 14px;
      font-size: 1rem;
      background: #f9f9f9;
      transition: border 0.18s;
    }
    #editPopup input[type="text"]:focus {
      border-color: #219a6f;
      background: #fff;
    }
    #editPopup button[type="submit"] {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 8px 22px;
      font-weight: bold;
      cursor: pointer;
      margin-right: 10px;
      transition: background 0.18s, color 0.18s, transform 0.18s;
    }
    #editPopup button[type="submit"]:hover {
      background: #fff;
      color: #43e97b;
      border: 1.5px solid #43e97b;
      transform: scale(1.04);
    }
    #editPopup .cancel-btn {
      background: #e53935;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 8px 22px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, transform 0.18s;
    }
    #editPopup .cancel-btn:hover {
      background: #fff;
      color: #e53935;
      border: 1.5px solid #e53935;
      transform: scale(1.04);
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
      table, th, td {
        font-size: 0.92rem;
      }
      #editPopup .popup-content {
        min-width: 90vw;
        padding: 18px 6vw;
      }
    }
    @media (max-width: 600px) {
      .main h2 { font-size: 1.2rem; }
      table, th, td { font-size: 0.85rem; }
      .sidebar a { padding: 8px 8px; }
      #editPopup .popup-content { min-width: 98vw; }
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

<div class="main">
  <h2>📋 Daftar Murid</h2>
  <table>
    <tr>
      <th>No</th>
      <th>UID Kartu</th>
      <th>Nama</th>
      <th>Kelas</th>
      <th>NIS</th>
      <th>Edit</th>
    </tr>
    <?php
    $no = 1;
    $res = $koneksi->query("SELECT * FROM murid ORDER BY id DESC");
    while ($row = $res->fetch_assoc()) {
      echo "<tr>
              <td>{$no}</td>
              <td>{$row['id_kartu']}</td>
              <td>{$row['nama']}</td>
              <td>{$row['kelas']}</td>
              <td>{$row['nis']}</td>
              <td>
                <button class='edit-btn' 
                  data-id='{$row['id']}'
                  data-id_kartu='{$row['id_kartu']}'
                  data-nama=\"".htmlspecialchars($row['nama'], ENT_QUOTES)."\"
                  data-kelas=\"".htmlspecialchars($row['kelas'], ENT_QUOTES)."\"
                  data-nis=\"".htmlspecialchars($row['nis'], ENT_QUOTES)."\"
                >Edit</button>
              </td>
            </tr>";
      $no++;
    }
    ?>

<!-- Pop Up Edit -->
<div id="editPopup">
  <div class="popup-content">
    <h3>Edit Data Murid</h3>
    <form method="post" id="editForm">
      <input type="hidden" name="edit_id" id="edit_id">
      <div>
        <label>UID Kartu</label>
        <input type="text" name="edit_id_kartu" id="edit_id_kartu" readonly>
      </div>
      <div>
        <label>Nama</label>
        <input type="text" name="edit_nama" id="edit_nama" required>
      </div>
      <div>
        <label>Kelas</label>
        <input type="text" name="edit_kelas" id="edit_kelas" required>
      </div>
      <div>
        <label>NIS</label>
        <input type="text" name="edit_nis" id="edit_nis" required>
      </div>
      <button type="submit" name="simpan">Simpan</button>
      <button type="button" class="cancel-btn" onclick="closePopup()">Batal</button>
      <button type="button" class="cancel-btn" style="background:#e53935;margin-left:8px;" onclick="hapusMurid()">Hapus</button>
    </form>
    <form method="post" id="hapusForm" style="display:none;">
      <input type="hidden" name="hapus_id" id="hapus_id">
    </form>
  </div>
</div>

<script>
  // Tampilkan popup dan isi data
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.onclick = function() {
      document.getElementById('edit_id').value = this.dataset.id;
      document.getElementById('edit_id_kartu').value = this.dataset.id_kartu;
      document.getElementById('edit_nama').value = this.dataset.nama;
      document.getElementById('edit_kelas').value = this.dataset.kelas;
      document.getElementById('edit_nis').value = this.dataset.nis;
      document.getElementById('hapus_id').value = this.dataset.id;
      document.getElementById('editPopup').style.display = 'flex';
    }
  });
  function closePopup() {
    document.getElementById('editPopup').style.display = 'none';
  }
  // Tutup popup jika klik di luar form
  document.getElementById('editPopup').onclick = function(e) {
    if (e.target === this) closePopup();
  }
  // Hapus murid
  function hapusMurid() {
    if (confirm('Yakin ingin menghapus data murid ini?')) {
      document.getElementById('hapusForm').submit();
    }
  }
</script>

<?php
// Proses update data murid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id']) && isset($_POST['simpan'])) {
  $id = intval($_POST['edit_id']);
  $nama = $koneksi->real_escape_string($_POST['edit_nama']);
  $kelas = $koneksi->real_escape_string($_POST['edit_kelas']);
  $nis = $koneksi->real_escape_string($_POST['edit_nis']);
  $koneksi->query("UPDATE murid SET nama='$nama', kelas='$kelas', nis='$nis' WHERE id=$id");
  echo "<script>window.location='murid.php';</script>";
  exit;
}

// Proses hapus data murid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
  $id = intval($_POST['hapus_id']);
  $koneksi->query("DELETE FROM murid WHERE id=$id");
  echo "<script>window.location='murid.php';</script>";
  exit;
}
?>
</body>
</html>


