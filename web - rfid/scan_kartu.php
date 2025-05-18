<?php
date_default_timezone_set("Asia/Jakarta");
$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$info = null;
$tipe = "";
$waktu_absensi = "";
$notifikasi = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = $_POST['uid'];
    $waktu_absensi = date("Y-m-d H:i:s");
    $batas_waktu = new DateTime(date('Y-m-d') . ' 22:00:00');
    $waktu_sekarang = new DateTime($waktu_absensi);

    $resGuru = $koneksi->query("SELECT * FROM users WHERE id_kartu = '$uid'");
    if ($resGuru && $resGuru->num_rows > 0) {
        $data = $resGuru->fetch_assoc();
        $info = [
            'role' => 'Guru',
            'nama' => $data['nama'],
            'jabatan' => $data['jabatan'],
            'uid' => $data['id_kartu']
        ];
        $tipe = 'guru';
        $notifikasi = ($waktu_sekarang > $batas_waktu) ? "Anda telah terlambat masuk!" : "Absensi berhasil!";
        $koneksi->query("INSERT INTO absensi_log (id_kartu, waktu) VALUES ('$uid', '$waktu_absensi')");
    } else {
        $resMurid = $koneksi->query("SELECT * FROM murid WHERE id_kartu = '$uid'");
        if ($resMurid && $resMurid->num_rows > 0) {
            $data = $resMurid->fetch_assoc();
            $info = [
                'role' => 'Murid',
                'nama' => $data['nama'],
                'kelas' => $data['kelas'],
                'nis' => $data['nis'],
                'uid' => $data['id_kartu']
            ];
            $tipe = 'murid';
            $notifikasi = ($waktu_sekarang > $batas_waktu) ? "Anda telah terlambat masuk!" : "Absensi berhasil!";
            $koneksi->query("INSERT INTO absensi_log (id_kartu, waktu) VALUES ('$uid', '$waktu_absensi')");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Scan Kartu RFID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #fff url('bg-logo.png') no-repeat center center;
            background-size: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255,255,255,0.92);
            padding: 38px 28px 28px 28px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
            min-width: 320px;
            max-width: 370px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(2px);
        }
        .container h2 {
            font-size: 28px;
            margin-bottom: 16px;
            font-weight: bold;
            color: #219a6f;
            letter-spacing: 1px;
        }
        .container form input[type="text"] {
            font-size: 18px;
            padding: 10px 12px;
            width: 70%;         /* diperkecil dari 100% */
            border-radius: 8px;
            border: 2px solid #4CAF50;
            outline: none;
            margin-top: 12px;
            background: #f9f9f9;
            transition: border 0.2s, background 0.2s;
            box-sizing: border-box;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .container form input[type="text"]:focus {
            border-color: #219a6f;
            background: #fff;
        }
        .live-time {
            margin-top: 18px;
            font-size: 18px;
            color: #388e3c;
            letter-spacing: 1px;
            font-weight: 500;
        }
        .card {
            margin-top: 28px;
            margin-left: auto;
            margin-right: auto;
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            border-radius: 14px;
            padding: 20px 16px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            display: block; /* agar margin auto bekerja */
            text-align: left;
            min-width: 180px;
            max-width: 220px;
            border: 1.5px solid #4CAF50;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .card h3 {
            margin-top: 0;
            color: #2e7d32;
            font-size: 20px;
            letter-spacing: 1px;
        }
        .card p {
            margin: 8px 0;
            font-size: 16px;
            color: #333;
            font-weight: 500;
            border-left: 3px solid #4CAF50;
            padding-left: 10px;
            background: rgba(255,255,255,0.7);
            border-radius: 4px;
        }
        .notfound {
            margin-top: 28px;
            color: #e53935;
            font-weight: bold;
            font-size: 17px;
        }
        .notification {
            margin-top: 20px;
            font-size: 17px;
            font-weight: bold;
            padding: 10px 0;
            border-radius: 6px;
            background: #ffe0e0;
            color: #e53935;
        }
        .notification.success {
            background: #e0ffe6;
            color: #219a6f;
        }
        .back-btn {
            display: inline-block;
            margin-top: 26px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        }
        .back-btn:hover {
            background-color: #219a6f;
            transform: translateY(-2px) scale(1.03);
        }
        @media (max-width: 600px) {
            .container {
                min-width: unset;
                max-width: 98vw;
                padding: 18px 5vw;
            }
            .card {
                min-width: unset;
                max-width: 98vw;
            }
        }
    </style>
    <script>
        function updateLiveTime() {
            const now = new Date();
            const liveTime = now.toLocaleString('id-ID', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('live-time').textContent = liveTime + " WIB";
        }
        setInterval(updateLiveTime, 1000);
    </script>
</head>
<body onload="updateLiveTime()">

<div class="container">
    <h2>📡 Scan Kartu RFID</h2>
    <form method="post" autocomplete="off">
        <input type="text" name="uid" autofocus placeholder="Tempelkan Kartu..." />
    </form>

    <div id="live-time" class="live-time"></div>

    <?php if ($info): ?>
        <div class="card">
            <h3><?= $info['role'] === 'Guru' ? '👨‍🏫 Data Guru' : '🎓 Data Murid' ?></h3>
            <p><strong>Nama:</strong> <?= htmlspecialchars($info['nama']) ?></p>
            <?php if ($info['role'] === 'Guru'): ?>
                <p><strong>Jabatan:</strong> <?= htmlspecialchars($info['jabatan']) ?></p>
            <?php else: ?>
                <p><strong>Kelas:</strong> <?= htmlspecialchars($info['kelas']) ?></p>
                <p><strong>NIS:</strong> <?= htmlspecialchars($info['nis']) ?></p>
            <?php endif; ?>
            <p><strong>UID:</strong> <?= htmlspecialchars($info['uid']) ?></p>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="notfound">❌ UID tidak ditemukan!</div>
    <?php endif; ?>

    <?php if ($info): ?>
        <div class="notification <?= ($notifikasi === "Anda telah terlambat masuk!") ? '' : 'success' ?>">
            <?= htmlspecialchars($notifikasi) ?>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="notification">❌ UID tidak ditemukan!</div>
    <?php endif; ?>

    <a href="index.php" class="back-btn">← Kembali ke Home</a>
</div>

</body>
</html>
