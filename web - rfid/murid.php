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
    .main {
      margin-left: 210px;
      padding: 38px 4vw 38px 4vw;
      flex: 1;
      min-height: 100vh;
      background: transparent;
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
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    tr:nth-child(even) {
      background-color: #f4f9f4;
    }
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
      .main {
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
  <a href="menu_utama.php">Menu Utama</a>
  <a href="guru.php">Data Guru</a>
  <a href="murid.php">Data Murid</a>
  <a href="tambah_data.php">Tambah Data</a>
  <a href="scan_kartu.php">Scan Kartu</a>
  <a href="log_rekap.php">Rekap Log Absensi</a>
  <a href="profile.php">Profile</a>
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
  </table>
  <a href="export_murid_pdf.php" target="_blank">
    <button class="export-btn">
      🧾 Export Murid ke PDF
    </button>
  </a>
</div>

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
      <button type="submit">Simpan</button>
      <button type="button" class="cancel-btn" onclick="closePopup()">Batal</button>
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
</script>

<?php
// Proses update data murid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
  $id = intval($_POST['edit_id']);
  $nama = $koneksi->real_escape_string($_POST['edit_nama']);
  $kelas = $koneksi->real_escape_string($_POST['edit_kelas']);
  $nis = $koneksi->real_escape_string($_POST['edit_nis']);
  $koneksi->query("UPDATE murid SET nama='$nama', kelas='$kelas', nis='$nis' WHERE id=$id");
  echo "<script>window.location='murid.php';</script>";
  exit;
}
?>
</body>
</html>


