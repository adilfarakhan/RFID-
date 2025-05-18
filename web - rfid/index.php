<?php 
date_default_timezone_set("Asia/Jakarta");

$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
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
  <a href="menu_utama.php">Menu Utama</a>
  <a href="guru.php">Data Guru</a>
  <a href="murid.php">Data Murid</a>
  <a href="tambah_data.php">Tambah Data</a>
  <a href="scan_kartu.php">Scan Kartu</a>
  <a href="log_rekap.php">Rekap Log Absensi</a>
  <a href="profile.php">Profile</a>
</div>

<!-- Konten Utama -->
<div class="main">
  <h2>Selamat datang di Sistem Absensi</h2>
  
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
          $jam_masuk = strtotime('07:00:00');
          $jam_pulang = strtotime('16:00:00');

          while ($row = $result_guru->fetch_assoc()) {
              $waktu_absen = strtotime($row['waktu']);
              // Jam masuk tetap 07:00:00, toleransi 30 menit (07:30:00)
              $keterangan = ($waktu_absen > $jam_masuk + 1800) ? "Terlambat" : "Tidak Terlambat";
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['jabatan']) . "</td>";
              echo "<td>" . date('d M Y, H:i:s', $waktu_absen) . "</td>";
              echo "<td>" . $keterangan . "</td>";
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
          $jam_masuk = strtotime('22:00:00');
          $jam_pulang = strtotime('16:00:00');

          while ($row = $result_murid->fetch_assoc()) {
              $waktu_absen = strtotime($row['waktu']);
              // Jam masuk tetap 07:00:00, toleransi 30 menit (07:30:00)
              $keterangan = ($waktu_absen > $jam_masuk + 1800) ? "Terlambat" : "Tidak Terlambat";
              echo "<tr>";
              echo "<td>" . $no++ . "</td>";
              echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
              echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
              echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
              echo "<td>" . date('d M Y, H:i:s', $waktu_absen) . "</td>";
              echo "<td>" . $keterangan . "</td>";
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
