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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_kartu'])) {
    $id_kartu = $koneksi->real_escape_string($_POST['id_kartu']);
    $filter = isset($_POST['filter']) ? intval($_POST['filter']) : 30;

    // Ambil data user/guru/murid
    $data = $koneksi->query("SELECT nama, jabatan FROM users WHERE id_kartu='$id_kartu'")->fetch_assoc();
    if (!$data) {
        $data = $koneksi->query("SELECT nama, kelas as jabatan FROM murid WHERE id_kartu='$id_kartu'")->fetch_assoc();
    }

    // Ambil data absensi detail hanya untuk id_kartu ini
    $sql = "SELECT waktu FROM absensi_log WHERE id_kartu='$id_kartu' ORDER BY waktu DESC LIMIT $filter";
    $result = $koneksi->query($sql);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=rekap_absensi_$id_kartu.xls");
    echo "<table border='1'>";
    echo "<tr><th colspan='4'>Rekap Absensi</th></tr>";
    echo "<tr><td>Nama</td><td colspan='3'>" . htmlspecialchars($data['nama']) . "</td></tr>";
    echo "<tr><td>Jabatan/Kelas</td><td colspan='3'>" . htmlspecialchars($data['jabatan']) . "</td></tr>";
    echo "<tr><th>No</th><th>Tanggal</th><th>Jam</th><th>Status</th></tr>";

    $no = 1;
    $jam_pulang = '16:00:00';
    while ($row = $result->fetch_assoc()) {
        $waktu = strtotime($row['waktu']);
        $tanggal = date('d-m-Y', $waktu);
        $jam = date('H:i:s', $waktu);
        $status = ($jam >= $jam_pulang) ? 'Pulang' : 'Masuk';
        echo "<tr>";
        echo "<td>$no</td>";
        echo "<td>$tanggal</td>";
        echo "<td>$jam</td>";
        echo "<td>$status</td>";
        echo "</tr>";
        $no++;
    }
    echo "</table>";
    exit;
}
?>