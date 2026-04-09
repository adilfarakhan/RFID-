<?php
// Koneksi database
$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil id_guru dari parameter GET
$id_guru = isset($_GET['id_guru']) ? intval($_GET['id_guru']) : 0;

// Ambil data guru
$nama_guru = "";
$qGuru = $koneksi->query("SELECT nama FROM users WHERE id=$id_guru LIMIT 1");
if ($qGuru && $qGuru->num_rows > 0) {
    $nama_guru = $qGuru->fetch_assoc()['nama'];
}

// Ambil data jadwal guru dari tabel jadwal
$jadwal = [];
$qJadwal = $koneksi->query("SELECT * FROM jadwal WHERE id_guru=$id_guru ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam");
while ($row = $qJadwal->fetch_assoc()) {
    $jadwal[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Guru</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f5f5f5; margin: 0; }
        .popup-content {
            background: #fff;
            padding: 30px 24px 18px 24px;
            border-radius: 14px;
            min-width: 320px;
            max-width: 95vw;
            position: relative;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
            margin: 40px auto;
        }
        h3 { color: #219a6f; margin-bottom: 18px; text-align: center; }
        table {
            margin: 0 auto 18px auto;
            border-collapse: collapse;
            width: 100%;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(44,62,80,0.04);
        }
        th, td {
            border: none;
            padding: 10px 8px;
            text-align: center;
        }
        th {
            background: #43e97b;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
        }
        tr:nth-child(even) { background-color: #f4f9f4; }
        tr:hover { background-color: #e0f7fa; transition: background 0.15s; }
        td { font-size: 0.98rem; color: #333; }
        .btn {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 18px;
            font-weight: bold;
            cursor: pointer;
            margin: 0 6px;
            transition: background 0.18s, color 0.18s, transform 0.18s;
        }
        .btn:hover {
            background: #fff;
            color: #43e97b;
            border: 1.5px solid #43e97b;
            transform: scale(1.05);
        }
        .btn-cancel {
            background: #e53935;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 7px 18px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 10px;
        }
        .btn-cancel:hover {
            background: #fff;
            color: #e53935;
            border: 1.5px solid #e53935;
            transform: scale(1.04);
        }
    </style>
</head>
<body>
<div class="popup-content">
    <h3>Jadwal <?= htmlspecialchars($nama_guru) ?></h3>
    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mata Pelajaran</th>
                <th>Ruangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($jadwal) > 0): ?>
                <?php foreach ($jadwal as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['hari']) ?></td>
                        <td><?= htmlspecialchars($row['jam']) ?></td>
                        <td><?= htmlspecialchars($row['mapel']) ?></td>
                        <td><?= htmlspecialchars($row['ruangan']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Belum ada jadwal.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div style="text-align:center;">
        <button class="btn" onclick="alert('Fitur edit jadwal belum tersedia')">Edit Jadwal</button>
        <button class="btn" onclick="alert('Fitur tambah jadwal belum tersedia')">Tambah Jadwal</button>
        <button class="btn-cancel" onclick="window.close()">Tutup</button>
    </div>
</div>
</body>
</html>
<?php
$koneksi->close();
?>