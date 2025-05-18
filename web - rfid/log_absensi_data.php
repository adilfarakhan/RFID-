<?php
include 'db.php'; // Koneksi ke database

$sql = "SELECT a.id_kartu, a.waktu, 
               CASE 
                   WHEN g.nama IS NOT NULL THEN g.nama 
                   WHEN m.nama IS NOT NULL THEN m.nama 
                   ELSE 'Tidak Diketahui' 
               END AS nama,
               CASE 
                   WHEN g.jabatan IS NOT NULL THEN g.jabatan 
                   WHEN m.kelas IS NOT NULL THEN m.kelas 
                   ELSE 'Tidak Diketahui' 
               END AS jabatan_kelas
        FROM absensi_log a
        LEFT JOIN users g ON a.id_kartu = g.id_kartu
        LEFT JOIN murid m ON a.id_kartu = m.id_kartu
        ORDER BY a.waktu DESC";

$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['id_kartu']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
        echo "<td>" . htmlspecialchars($row['jabatan_kelas']) . "</td>";
        echo "<td>" . htmlspecialchars($row['waktu']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
}
?>