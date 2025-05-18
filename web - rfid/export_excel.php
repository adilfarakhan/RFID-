<?php
include 'db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rekap_log_absensi_30_hari.xls");

// Ambil filter waktu dan role dari POST
$role = isset($_POST['role']) ? $_POST['role'] : 'guru';

// Tentukan kondisi role
if ($role === 'guru') {
    $roleCondition = "g.role = 'guru'";
} elseif ($role === 'siswa') {
    $roleCondition = "m.role = 'siswa'";
} else {
    die("Role tidak valid.");
}

// Query untuk data guru atau siswa dalam 30 hari terakhir
$sql = "SELECT a.id_kartu, 
               CASE 
                   WHEN g.nama IS NOT NULL THEN g.nama 
                   WHEN m.nama IS NOT NULL THEN m.nama 
                   ELSE 'Tidak Diketahui' 
               END AS nama,
               CASE 
                   WHEN g.jabatan IS NOT NULL THEN g.jabatan 
                   WHEN m.kelas IS NOT NULL THEN m.kelas 
                   ELSE 'Tidak Diketahui' 
               END AS jabatan_kelas,
               COUNT(a.id_kartu) AS total_kehadiran
        FROM absensi_log a
        LEFT JOIN users g ON a.id_kartu = g.id_kartu
        LEFT JOIN murid m ON a.id_kartu = m.id_kartu
        WHERE $roleCondition AND a.waktu >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY a.id_kartu
        ORDER BY total_kehadiran DESC";

$result = $koneksi->query($sql);

// Header kolom Excel
echo "No\tID Kartu\tNama\tJabatan/Kelas\tTotal Kehadiran\n";

// Tampilkan data
if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo $no++ . "\t" . $row['id_kartu'] . "\t" . $row['nama'] . "\t" . $row['jabatan_kelas'] . "\t" . $row['total_kehadiran'] . "\n";
    }
} else {
    echo "Tidak ada data\n";
}
?>