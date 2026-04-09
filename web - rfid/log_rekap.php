<?php
include 'db.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id_kartu'])) {
    $id_kartu = $koneksi->real_escape_string($_POST['hapus_id_kartu']);
    $koneksi->query("DELETE FROM absensi_log WHERE id_kartu='$id_kartu'");
    echo "<script>alert('Data absensi berhasil dihapus!');window.location='log_rekap.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekap Log Absensi</title>
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
      text-align: center;
    }
    .main h3 {
      color: #2e7d32;
      margin-top: 32px;
      margin-bottom: 10px;
      font-size: 1.2rem;
      letter-spacing: 0.5px;
      text-align: left;
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
    th:nth-child(7) { background: #00bcd4; }
    th:nth-child(8) { background: #607d8b; }

    td:nth-child(1) { background: #fff3e0; }
    td:nth-child(2) { background: #e8f5e9; }
    td:nth-child(3) { background: #e3f2fd; }
    td:nth-child(4) { background: #fce4ec; }
    td:nth-child(5) { background: #f3e5f5; }
    td:nth-child(6) { background: #ffebee; }
    td:nth-child(7) { background: #e0f7fa; }
    td:nth-child(8) { background: #f5f5f5; }

    tbody tr:nth-child(odd) { background: #f4f9f4; }
    tbody tr:nth-child(even) { background: #e3f2fd; }

    tr:hover {
      background-color: #e0f7fa;
      transition: background 0.15s;
    }
    .main button, .main form button {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 7px 18px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.18s, color 0.18s, transform 0.18s;
      box-shadow: 0 2px 8px rgba(44,62,80,0.08);
      font-size: 0.98rem;
    }
    .main button:hover, .main form button:hover {
      background: #fff;
      color: #43e97b;
      border: 1.5px solid #43e97b;
      transform: scale(1.05);
    }
    #popup-overlay {
      display: none;
      position: fixed;
      z-index: 1001;
      left: 0; top: 0; right: 0; bottom: 0;
      background: rgba(44,62,80,0.25);
    }
    #popup {
      display: none;
      position: fixed;
      z-index: 1002;
      left: 50%; top: 50%;
      transform: translate(-50%, -50%);
      min-width: 320px;
      max-width: 95vw;
      max-height: 90vh;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 8px 32px rgba(44,62,80,0.18);
      padding: 28px 24px 18px 24px;
      overflow: auto;
      cursor: move;
    }
    .popup-close {
      position: absolute;
      top: 10px; right: 16px;
      background: #e53935;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 6px 16px;
      font-weight: bold;
      cursor: pointer;
      font-size: 1rem;
    }
    #popup-content {
      margin-top: 18px;
    }
    @media (max-width: 900px) {
      .sidebar,
      .sidebar:hover,
      .sidebar:focus-within {
        width: 100vw;
        position: static;
        height: auto;
        flex-direction: row;
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
    }
    @media (max-width: 600px) {
      .main h2 { font-size: 1.2rem; }
      .main h3 { font-size: 1rem; }
      table, th, td { font-size: 0.85rem; }
      .sidebar a { padding: 8px 8px; }
      #popup { min-width: 0; padding: 12px 4vw 8px 4vw; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h3>Menu</h3>
  <a href="menu_utama.php" style="background: #ff9800;">
    <span class="icon">🏠</span><span class="text">Menu Utama</span>
  </a>
  <a href="index.php" style="background: #1976d2;">
    <span class="icon">🏡</span><span class="text">Beranda</span>
  </a>
  <a href="guru.php" style="background: #43e97b;">
    <span class="icon">👨‍🏫</span><span class="text">Data Guru</span>
  </a>
  <a href="murid.php" style="background: #4caf50;">
    <span class="icon">🎓</span><span class="text">Data Murid</span>
  </a>
  <a href="tambah_data.php" style="background: #9c27b0;">
    <span class="icon">➕</span><span class="text">Tambah Data</span>
  </a>
  <a href="scan_kartu.php" style="background: #00bcd4;">
    <span class="icon">💳</span><span class="text">Scan Kartu</span>
  </a>
  <a href="log_rekap.php" style="background: #e91e63;">
    <span class="icon">📋</span><span class="text">Rekap Log Absensi</span>
  </a>
  <a href="profile.php" style="background: #ff5722;">
    <span class="icon">👤</span><span class="text">Profile</span>
  </a>
  <a href="logout.php" style="background: #f44336;">
    <span class="icon">🚪</span><span class="text">Logout</span>
  </a>
</div>

<!-- Konten Utama -->
<div class="main">
  <h2>📋 Rekap Log Absensi</h2>

  <!-- Tabel Guru -->
  <h3>👨‍🏫 Data Guru</h3>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID Kartu</th>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Total Kehadiran</th>
        <th>Cetak Excel</th>
        <th>Rincian</th>
        <th>Hapus</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql_guru = "SELECT a.id_kartu, g.nama, g.jabatan, COUNT(a.id_kartu) AS total_kehadiran
                   FROM absensi_log a
                   LEFT JOIN users g ON a.id_kartu = g.id_kartu
                   WHERE g.nama IS NOT NULL
                   GROUP BY a.id_kartu
                   ORDER BY total_kehadiran DESC";
      $result_guru = $koneksi->query($sql_guru);
      if ($result_guru->num_rows > 0) {
          $no = 1;
          while ($row = $result_guru->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
              echo "<td>" . htmlspecialchars($row['total_kehadiran']) . "</td>";
              echo "<td>
                      <form method='POST' action='export_excel.php' style='display:inline;'>
                        <input type='hidden' name='filter' value='30'>
                        <input type='hidden' name='id_kartu' value='" . htmlspecialchars($row['id_kartu']) . "'>
                        <button type='submit'>Cetak Excel (30 Hari)</button>
                      </form>
                    </td>";
              echo "<td><button onclick=\"showPopup('" . urlencode($row['id_kartu']) . "')\">Rincian</button></td>";
              echo "<td>
                      <form method='POST' onsubmit=\"return confirm('Yakin ingin menghapus semua data absensi ini?');\" style='display:inline;'>
                        <input type='hidden' name='hapus_id_kartu' value='" . htmlspecialchars($row['id_kartu']) . "'>
                        <button type='submit' style='background:#e53935;color:#fff;border:none;border-radius:6px;padding:7px 14px;font-weight:bold;cursor:pointer;'>Hapus</button>
                      </form>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='8'>Tidak ada data guru</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- Tabel Murid -->
  <h3>🎓 Data Murid</h3>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID Kartu</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Total Kehadiran</th>
        <th>Cetak Excel</th>
        <th>Rincian</th>
        <th>Hapus</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql_murid = "SELECT a.id_kartu, m.nama, m.kelas, COUNT(a.id_kartu) AS total_kehadiran
                    FROM absensi_log a
                    LEFT JOIN murid m ON a.id_kartu = m.id_kartu
                    WHERE m.nama IS NOT NULL
                    GROUP BY a.id_kartu
                    ORDER BY total_kehadiran DESC";
      $result_murid = $koneksi->query($sql_murid);
      if ($result_murid->num_rows > 0) {
          $no = 1;
          while ($row = $result_murid->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
              echo "<td>" . htmlspecialchars($row['total_kehadiran']) . "</td>";
              echo "<td>
                      <form method='POST' action='export_excel.php' style='display:inline;'>
                        <input type='hidden' name='filter' value='30'>
                        <input type='hidden' name='id_kartu' value='" . htmlspecialchars($row['id_kartu']) . "'>
                        <button type='submit'>Cetak Excel (30 Hari)</button>
                      </form>
                    </td>";
              echo "<td><button onclick=\"showPopup('" . urlencode($row['id_kartu']) . "')\">Rincian</button></td>";
              echo "<td>
                      <form method='POST' onsubmit=\"return confirm('Yakin ingin menghapus semua data absensi ini?');\" style='display:inline;'>
                        <input type='hidden' name='hapus_id_kartu' value='" . htmlspecialchars($row['id_kartu']) . "'>
                        <button type='submit' style='background:#e53935;color:#fff;border:none;border-radius:6px;padding:7px 14px;font-weight:bold;cursor:pointer;'>Hapus</button>
                      </form>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='8'>Tidak ada data murid</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Pop-up -->
<div id="popup-overlay" onclick="closePopup()"></div>
<div id="popup">
  <button class="popup-close" onclick="closePopup()">Tutup</button>
  <div id="popup-content"></div>
</div>
<script>
function showPopup(idKartu) {
  fetch('rincian_absensi.php?id_kartu=' + idKartu)
    .then(response => response.text())
    .then(data => {
      document.getElementById('popup-content').innerHTML = data;
      document.getElementById('popup').style.display = 'block';
      document.getElementById('popup-overlay').style.display = 'block';
    });
}
function closePopup() {
  document.getElementById('popup').style.display = 'none';
  document.getElementById('popup-overlay').style.display = 'none';
}
// Drag popup
window.onload = function() {
  const popup = document.getElementById('popup');
  let isDragging = false, offsetX, offsetY;
  popup.addEventListener('mousedown', (e) => {
    isDragging = true;
    offsetX = e.clientX - popup.getBoundingClientRect().left;
    offsetY = e.clientY - popup.getBoundingClientRect().top;
    popup.style.cursor = 'grabbing';
  });
  document.addEventListener('mousemove', (e) => {
    if (isDragging) {
      popup.style.left = `${e.clientX - offsetX}px`;
      popup.style.top = `${e.clientY - offsetY}px`;
      popup.style.transform = 'none';
    }
  });
  document.addEventListener('mouseup', () => {
    isDragging = false;
    popup.style.cursor = 'move';
  });
}
</script>
</body>
</html>

