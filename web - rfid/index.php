<?php 
date_default_timezone_set("Asia/Jakarta");

$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$tanggal_hari_ini = date('Y-m-d');

// Total guru yang sudah absen hari ini
$total_guru = $koneksi->query("
    SELECT COUNT(DISTINCT g.id_kartu) AS total
    FROM absensi_log a
    LEFT JOIN users g ON a.id_kartu = g.id_kartu
    WHERE g.nama IS NOT NULL
      AND DATE(a.waktu) = '$tanggal_hari_ini'
")->fetch_assoc()['total'];

// Total murid yang sudah absen hari ini
$total_murid = $koneksi->query("
    SELECT COUNT(DISTINCT m.id_kartu) AS total
    FROM absensi_log a
    LEFT JOIN murid m ON a.id_kartu = m.id_kartu
    WHERE m.nama IS NOT NULL
      AND DATE(a.waktu) = '$tanggal_hari_ini'
")->fetch_assoc()['total'];

// Jam pulang (misal: >= 16:00:00)
$jam_pulang = '16:00:00';

// Total guru absen pulang hari ini
$total_guru_pulang = $koneksi->query("
    SELECT COUNT(DISTINCT g.id_kartu) AS total
    FROM absensi_log a
    LEFT JOIN users g ON a.id_kartu = g.id_kartu
    WHERE g.nama IS NOT NULL
      AND DATE(a.waktu) = '$tanggal_hari_ini'
      AND TIME(a.waktu) >= '$jam_pulang'
")->fetch_assoc()['total'];

// Total murid absen pulang hari ini
$total_murid_pulang = $koneksi->query("
    SELECT COUNT(DISTINCT m.id_kartu) AS total
    FROM absensi_log a
    LEFT JOIN murid m ON a.id_kartu = m.id_kartu
    WHERE m.nama IS NOT NULL
      AND DATE(a.waktu) = '$tanggal_hari_ini'
      AND TIME(a.waktu) >= '$jam_pulang'
")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Beranda - Sistem Absensi</title>
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
    .main h3 {
      color: #2e7d32;
      margin-top: 32px;
      margin-bottom: 10px;
      font-size: 1.2rem;
      letter-spacing: 0.5px;
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
      /* Hapus gradasi, beri warna-warni per kolom */
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

    /* Baris data warna-warni selang-seling */
    tbody tr:nth-child(odd) { background: #f4f9f4; }
    tbody tr:nth-child(even) { background: #e3f2fd; }

    /* Jika ingin warna-warni per kolom data juga: */
    td:nth-child(1) { background: #fff3e0; }
    td:nth-child(2) { background: #e8f5e9; }
    td:nth-child(3) { background: #e3f2fd; }
    td:nth-child(4) { background: #fce4ec; }
    td:nth-child(5) { background: #f3e5f5; }
    td:nth-child(6) { background: #ffebee; }

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
  <a href="log_rekap.php" style="background: #e91e63; display: flex; align-items: center;">
    <span class="icon">📋</span><span class="text">Rekap Log</span>
  </a>
  <a href="profile.php" style="background: #ff5722;">
    <span class="icon">👤</span><span class="text">Profile</span>
  </a>
  <a href="logout.php" style="background: #f44336;">
    <span class="icon">🚪</span><span class="text">Logout</span>
  </a>
  <a href="akun.php" style="background: #f44336;">
    <span class="icon">🚪</span><span class="text">akun</span>
  </a>
</div>


<!-- Konten Utama -->
<div class="main">
  <h2>Selamat datang di Sistem Absensi</h2>

  <!-- Info Statistik -->
  <div style="display:flex; gap:18px; margin-bottom:28px; flex-wrap:wrap;">
    <div style="flex:1; min-width:180px; background:#2196f3; color:#fff; border-radius:12px; padding:18px 12px; box-shadow:0 2px 8px rgba(44,62,80,0.10); text-align:center;">
      <div style="font-size:1.7rem; font-weight:bold; margin-bottom:4px;"><?= $total_guru ?></div>
      <div style="font-size:1rem;">Guru Absen Masuk</div>
    </div>
    <div style="flex:1; min-width:180px; background:#1976d2; color:#fff; border-radius:12px; padding:18px 12px; box-shadow:0 2px 8px rgba(44,62,80,0.10); text-align:center;">
      <div style="font-size:1.7rem; font-weight:bold; margin-bottom:4px;"><?= $total_murid ?></div>
      <div style="font-size:1rem;">Murid Absen Masuk</div>
    </div>
    <div style="flex:1; min-width:180px; background:#ff9800; color:#fff; border-radius:12px; padding:18px 12px; box-shadow:0 2px 8px rgba(44,62,80,0.10); text-align:center;">
      <div style="font-size:1.7rem; font-weight:bold; margin-bottom:4px;"><?= $total_guru_pulang ?></div>
      <div style="font-size:1rem;">Guru Absen Pulang</div>
    </div>
    <div style="flex:1; min-width:180px; background:#4caf50; color:#fff; border-radius:12px; padding:18px 12px; box-shadow:0 2px 8px rgba(44,62,80,0.10); text-align:center;">
      <div style="font-size:1.7rem; font-weight:bold; margin-bottom:4px;"><?= $total_murid_pulang ?></div>
      <div style="font-size:1rem;">Murid Absen Pulang</div>
    </div>
  </div>

  <!-- Tabel Guru -->
  <h3>👨‍🏫 Log Absensi Guru</h3>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID Kartu</th>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Waktu</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql_guru = "SELECT a.id_kartu, a.waktu, 
                          g.nama AS nama, 
                          g.jabatan AS jabatan
                   FROM absensi_log a
                   LEFT JOIN users g ON a.id_kartu = g.id_kartu
                   WHERE g.nama IS NOT NULL
                   ORDER BY a.waktu DESC";

      $result_guru = $koneksi->query($sql_guru);

      if ($result_guru->num_rows > 0) {
          $no = 1;
          $jam_masuk = strtotime('19:00:00');
          $jam_pulang = strtotime('20:00:00');

          while ($row = $result_guru->fetch_assoc()) {
              $waktu_absen = strtotime($row['waktu']);
              // Jam masuk tetap 07:00:00, toleransi 30 menit (07:30:00)
              $keterangan_telat = ($waktu_absen > $jam_masuk + 1800) ? "Terlambat" : "Tidak Terlambat";
              $keterangan_masuk_pulang = (date('H:i:s', $waktu_absen) >= $jam_pulang) ? "Pulang" : "Masuk";
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
              echo "<td>" . date('d M Y, H:i:s', $waktu_absen) . "</td>";
              echo "<td>" . $keterangan_masuk_pulang . " - " . $keterangan_telat . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='6'>Tidak ada data guru</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <!-- Tabel Murid -->
  <h3>🎓 Log Absensi Murid</h3>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID Kartu</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Waktu</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql_murid = "SELECT a.id_kartu, a.waktu, 
                           m.nama AS nama, 
                           m.kelas AS kelas
                    FROM absensi_log a
                    LEFT JOIN murid m ON a.id_kartu = m.id_kartu
                    WHERE m.nama IS NOT NULL
                    ORDER BY a.waktu DESC";

      $result_murid = $koneksi->query($sql_murid);

      if ($result_murid->num_rows > 0) {
          $no = 1;
          $jam_masuk = strtotime('19:00:00');
          $jam_pulang = strtotime('20:00:00');

          while ($row = $result_murid->fetch_assoc()) {
              $waktu_absen = strtotime($row['waktu']);
              // Jam masuk tetap 07:00:00, toleransi 30 menit (07:30:00)
              $keterangan_telat = ($waktu_absen > $jam_masuk + 1800) ? "Terlambat" : "Tidak Terlambat";
              $keterangan_masuk_pulang = (date('H:i:s', $waktu_absen) >= $jam_pulang) ? "Pulang" : "Masuk";
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
              echo "<td>" . date('d M Y, H:i:s', $waktu_absen) . "</td>";
              echo "<td>" . $keterangan_masuk_pulang . " - " . $keterangan_telat . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='6'>Tidak ada data murid</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
