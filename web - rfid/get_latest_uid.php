<?php
include 'db.php';
$no = 1;
$res = $koneksi->query("SELECT * FROM absensi_log ORDER BY waktu DESC LIMIT 10");
while($r = $res->fetch_assoc()){
  echo "<tr>
          <td>{$no}</td>
          <td>{$r['id_kartu']}</td>
          <td>{$r['waktu']}</td>
        </tr>";
  $no++;
}
?>
