<?php
include 'db.php'; // Koneksi ke database
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Log Absensi</title>
  <style>
    body { font-family: Arial; text-align: center; padding-top: 20px; }
    table { margin: 0 auto; border-collapse: collapse; width: 80%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
    th { background-color: #f4f4f4; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    tr:hover { background-color: #f1f1f1; }
  </style>
  <script>
    // Fungsi untuk memuat ulang data log absensi
    function loadLog() {
      fetch('log_absensi_data.php')
        .then(response => response.text())
        .then(data => {
          document.getElementById('log-table-body').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }

    // Auto-refresh setiap 5 detik
    setInterval(loadLog, 5000);

    // Muat data pertama kali saat halaman dimuat
    window.onload = loadLog;
  </script>
</head>
<body>
  <h2>📋 Log Absensi</h2>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID Kartu</th>
        <th>Nama</th>
        <th>Jabatan/Kelas</th>
        <th>Waktu</th>
      </tr>
    </thead>
    <tbody id="log-table-body">
      <!-- Data log absensi akan dimuat di sini -->
    </tbody>
  </table>
</body>
</html>