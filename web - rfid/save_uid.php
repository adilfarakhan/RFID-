<?php
include 'db.php';  // koneksi ke database

if (isset($_POST['uid'])) {
    $uid = $koneksi->real_escape_string($_POST['uid']);
    // Masukkan ke tabel absensi_log
    $sql = "INSERT INTO absensi_log (id_kartu, waktu) VALUES ('$uid', NOW())";
    if ($koneksi->query($sql)) {
        echo "selesai menyimpan";
    } else {
        echo "error";
    }
}
