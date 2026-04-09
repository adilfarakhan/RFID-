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
    $tanggal_hari_ini = date('Y-m-d');
    $jam_pulang = '20:00:00';
    $waktu_sekarang = new DateTime($waktu_absensi);

    // Cek apakah UID terdaftar sebagai guru atau murid
    $resGuru = $koneksi->query("SELECT * FROM users WHERE id_kartu = '$uid'");
    $resMurid = null;
    if ($resGuru && $resGuru->num_rows > 0) {
        $data = $resGuru->fetch_assoc();
        $info = [
            'role' => 'Guru',
            'nama' => $data['nama'],
            'jabatan' => $data['jabatan'],
            'uid' => $data['id_kartu']
        ];
        $tipe = 'guru';
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
        }
    }

    if ($info) {
        // Cek apakah sudah absen masuk hari ini
        $cek_masuk = $koneksi->query("SELECT * FROM absensi_log WHERE id_kartu='$uid' AND DATE(waktu)='$tanggal_hari_ini' AND TIME(waktu) < '$jam_pulang' LIMIT 1");
        // Cek apakah sudah absen pulang hari ini
        $cek_pulang = $koneksi->query("SELECT * FROM absensi_log WHERE id_kartu='$uid' AND DATE(waktu)='$tanggal_hari_ini' AND TIME(waktu) >= '$jam_pulang' LIMIT 1");

        $jam_sekarang = date('H:i:s', strtotime($waktu_absensi));

        if ($jam_sekarang < $jam_pulang) {
            // Absen Masuk
            if ($cek_masuk && $cek_masuk->num_rows > 0) {
                $notifikasi = "Anda sudah melakukan absen masuk hari ini!";
            } else {
                $batas_waktu = new DateTime(date('Y-m-d') . ' 19:00:00');
                $notifikasi = ($waktu_sekarang > $batas_waktu) ? "Anda telah terlambat masuk!" : "Absensi berhasil!";
                $koneksi->query("INSERT INTO absensi_log (id_kartu, waktu) VALUES ('$uid', '$waktu_absensi')");
            }
        } else {
            // Absen Pulang
            if ($cek_pulang && $cek_pulang->num_rows > 0) {
                $notifikasi = "Anda sudah melakukan absen pulang hari ini!";
            } else {
                // Pastikan sudah absen masuk sebelum bisa absen pulang
                if ($cek_masuk && $cek_masuk->num_rows > 0) {
                    $notifikasi = "Absensi pulang berhasil!";
                    $koneksi->query("INSERT INTO absensi_log (id_kartu, waktu) VALUES ('$uid', '$waktu_absensi')");
                } else {
                    $notifikasi = "Lakukan absen masuk terlebih dahulu sebelum absen pulang!";
                }
            }
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            text-align: center;
            min-height: 100vh;
            background: #fff url('bg-logo.png') no-repeat center center;
            background-size: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255,255,255,0.97);
            padding: 48px 32px 32px 32px;
            border-radius: 22px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
            min-width: 320px;
            max-width: 370px;
            width: 100%;
            text-align: center;
            backdrop-filter: blur(2px);
            position: relative;
            overflow: hidden;
        }
        .container::before {
            content: "";
            position: absolute;
            left: -60px;
            top: -60px;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            opacity: 0.09;
            border-radius: 50%;
            z-index: 0;
        }
        .container h2 {
            font-size: 2rem;
            margin-bottom: 18px;
            font-weight: bold;
            color: #219a6f;
            letter-spacing: 1px;
            z-index: 1;
            position: relative;
        }
        .container form input[type="text"] {
            font-size: 1.15rem;
            padding: 14px 16px;
            width: 85%;
            border-radius: 10px;
            border: 2px solid #43e97b;
            outline: none;
            margin-top: 16px;
            background: #f9f9f9;
            transition: border 0.2s, background 0.2s;
            box-sizing: border-box;
            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            font-weight: 500;
            letter-spacing: 1px;
        }
        .container form input[type="text"]:focus {
            border-color: #219a6f;
            background: #fff;
        }
        .live-time {
            margin-top: 18px;
            font-size: 1.08rem;
            color: #388e3c;
            letter-spacing: 1px;
            font-weight: 500;
            background: #e0f7fa;
            border-radius: 7px;
            padding: 6px 0;
            width: 100%;
        }
        .card {
            margin-top: 28px;
            margin-left: auto;
            margin-right: auto;
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            border-radius: 14px;
            padding: 22px 16px 16px 16px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            display: block;
            text-align: left;
            min-width: 180px;
            max-width: 240px;
            border: 1.5px solid #43e97b;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
            z-index: 1;
        }
        .card h3 {
            margin-top: 0;
            color: #2e7d32;
            font-size: 1.15rem;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .card p {
            margin: 8px 0;
            font-size: 1rem;
            color: #333;
            font-weight: 500;
            border-left: 3px solid #43e97b;
            padding-left: 10px;
            background: rgba(255,255,255,0.7);
            border-radius: 4px;
        }
        .notfound {
            margin-top: 28px;
            color: #e53935;
            font-weight: bold;
            font-size: 1.08rem;
            background: #ffebee;
            border-radius: 7px;
            padding: 10px 0;
        }
        .notification {
            margin-top: 20px;
            font-size: 1.08rem;
            font-weight: bold;
            padding: 10px 0;
            border-radius: 7px;
            background: #ffe0e0;
            color: #e53935;
            box-shadow: 0 2px 8px rgba(44,62,80,0.05);
        }
        .notification.success {
            background: #e0ffe6;
            color: #219a6f;
        }
        .back-btn {
            display: inline-block;
            margin-top: 30px;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            text-decoration: none;
            padding: 13px 32px;
            border-radius: 25px;
            font-size: 1.08rem;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 4px 12px rgba(44,62,80,0.10);
            letter-spacing: 1px;
        }
        .back-btn:hover {
            background: linear-gradient(90deg, #219a6f 0%, #43e97b 100%);
            transform: translateY(-2px) scale(1.04);
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
