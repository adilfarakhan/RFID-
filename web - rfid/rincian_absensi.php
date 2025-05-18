<?php
include 'db.php';

$id_kartu = isset($_GET['id_kartu']) ? $_GET['id_kartu'] : '';

if (!$id_kartu) {
    echo "ID Kartu tidak valid.";
    exit;
}

// Ambil nama pengguna berdasarkan ID Kartu
$sqlNama = "SELECT 
                CASE 
                    WHEN g.nama IS NOT NULL THEN g.nama 
                    WHEN m.nama IS NOT NULL THEN m.nama 
                    ELSE 'Tidak Diketahui' 
                END AS nama
            FROM absensi_log a
            LEFT JOIN users g ON a.id_kartu = g.id_kartu
            LEFT JOIN murid m ON a.id_kartu = m.id_kartu
            WHERE a.id_kartu = '$id_kartu'
            LIMIT 1";

$resultNama = $koneksi->query($sqlNama);
$nama = "Tidak Diketahui";

if ($resultNama->num_rows > 0) {
    $rowNama = $resultNama->fetch_assoc();
    $nama = $rowNama['nama'];
}

// Ambil rincian absensi berdasarkan ID Kartu
$sql = "SELECT id_kartu, waktu FROM absensi_log WHERE id_kartu = '$id_kartu' ORDER BY waktu DESC";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Rincian Absensi untuk: " . htmlspecialchars($nama) . "</h3>";
    echo "<table border='1' style='width: 100%; border-collapse: collapse;'>";
    echo "<tr><th>No</th><th>ID Kartu</th><th>Waktu</th><th>Keterangan</th></tr>";
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $jam_masuk = strtotime(date('Y-m-d', strtotime($row['waktu'])) . ' 22:00:00');
        $waktu_absen = strtotime($row['waktu']);
        $keterangan = ($waktu_absen > $jam_masuk + 1800) ? 'Terlambat' : 'Tepat Waktu'; // 30 menit toleransi
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
        echo "<td>" . htmlspecialchars($row['waktu']) . "</td>";
        echo "<td>" . $keterangan . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h3>Rincian Absensi untuk: " . htmlspecialchars($nama) . "</h3>";
    echo "Tidak ada data absensi.";
}
?>