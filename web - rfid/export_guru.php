<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_guru.xls");

$koneksi = new mysqli("localhost", "root", "", "absensi");

echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>UID Kartu</th>
        <th>Nama</th>
        <th>Jabatan</th>
      </tr>";

$no = 1;
$res = $koneksi->query("SELECT * FROM users");
while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['id_kartu']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['jabatan']}</td>
          </tr>";
    $no++;
}

echo "</table>";
?>
