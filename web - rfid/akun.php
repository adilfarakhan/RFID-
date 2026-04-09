<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "akun");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Proses update data user (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $koneksi->prepare("UPDATE users SET username=?, password=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $password, $id);
    $stmt->execute();
    echo "success";
    exit;
}

// Proses hapus user (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $id = $_POST['id'];
    $stmt = $koneksi->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "success";
    exit;
}

// Ambil data dari tabel users
$data_user = [];
$q2 = $koneksi->query("SELECT * FROM users");
if ($q2) {
    while ($row = $q2->fetch_assoc()) {
        $data_user[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #43e97b 0%, #38f9d7 100%);
            min-height: 100vh;
            margin: 0;
            display: flex;
        }
        /* Sidebar styles (copy dari index.php) */
        .sidebar {
            width: 60px;
            background: linear-gradient(180deg, #43e97b 0%, #38f9d7 100%);
            height: 100vh;
            padding-top: 18px; /* lebih kecil agar menu lebih ke atas */
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 16px rgba(44,62,80,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: width 0.25s;
            z-index: 10;
            overflow-x: hidden;
            justify-content: flex-start; /* pastikan menu menempel ke atas */
        }
        .sidebar:hover, .sidebar:focus-within {
            width: 210px;
        }
        .sidebar h3 {
            color: #fff;
            text-align: center;
            margin-bottom: 28px;
            letter-spacing: 1px;
            font-size: 1.3rem;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(44,62,80,0.08);
            opacity: 0;
            transition: opacity 0.2s;
        }
        .sidebar:hover h3, .sidebar:focus-within h3 {
            opacity: 1;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            width: 85%;
            margin: 8px auto;
            padding: 13px 0;
            color: #fff;
            background: rgba(255,255,255,0.08);
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: background 0.18s, color 0.18s, transform 0.18s, padding 0.18s;
            overflow: hidden;
        }
        .sidebar a .icon {
            display: inline-block;
            width: 32px;
            text-align: center;
            font-size: 1.3rem;
            margin-right: 0;
            transition: margin 0.18s;
        }
        .sidebar a .text {
            opacity: 0;
            width: 0;
            margin-left: 0;
            transition: opacity 0.18s, width 0.18s, margin 0.18s;
            white-space: nowrap;
        }
        .sidebar:hover a .text, .sidebar:focus-within a .text {
            opacity: 1;
            width: auto;
            margin-left: 12px;
        }
        .sidebar:hover a, .sidebar:focus-within a {
            padding-left: 12px;
            padding-right: 12px;
        }
        .sidebar a:hover {
            background: #fff;
            color: #43e97b;
            transform: translateX(6px) scale(1.04);
            font-weight: bold;
        }
        /* End sidebar styles */

        .main {
            margin-left: 60px;
            padding: 38px 4vw 38px 4vw;
            flex: 1;
            min-height: 100vh;
            background: transparent;
            transition: margin-left 0.25s;
        }
        .sidebar:hover ~ .main,
        .sidebar:focus-within ~ .main {
            margin-left: 210px;
        }
        h2 {
            margin-top: 40px;
            color: #fff;
            text-align: center;
            letter-spacing: 2px;
            text-shadow: 0 2px 8px #38f9d799;
        }
        .table-container {
            width: 95%;
            max-width: 900px;
            margin: 32px auto 0 auto;
            background: rgba(255,255,255,0.95);
            border-radius: 18px;
            box-shadow: 0 8px 32px #38f9d733;
            padding: 28px 18px 32px 18px;
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: transparent;
        }
        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #e0f7fa;
            text-align: left;
        }
        th {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            font-weight: 600;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover td {
            background: #e0f7fa55;
            transition: background 0.2s;
        }
        button {
            padding: 7px 16px;
            border: none;
            background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
            color: #fff;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            box-shadow: 0 2px 8px #38f9d722;
            transition: background 0.2s, box-shadow 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            box-shadow: 0 4px 16px #38f9d744;
        }

        /* Popup Styles */
        #popup {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.25);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #popup-content {
            background: linear-gradient(120deg, #fff 80%, #38f9d7 100%);
            padding: 36px 32px 28px 32px;
            border-radius: 18px;
            width: 340px;
            box-shadow: 0 8px 32px #38f9d733;
            position: relative;
            animation: popupIn 0.25s;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        @keyframes popupIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        #close-popup {
            position: absolute;
            top: 12px; right: 16px;
            background: none;
            border: none;
            font-size: 22px;
            color: #38f9d7;
            cursor: pointer;
            font-weight: bold;
            transition: color 0.2s;
        }
        #close-popup:hover {
            color: #e74c3c;
        }
        #popup-content h3 {
            margin: 0 0 12px 0;
            text-align: center;
            color: #219a6f;
            letter-spacing: 1px;
        }
        #popup-content label {
            font-weight: 500;
            color: #219a6f;
            margin-bottom: 2px;
        }
        #popup-content input[type="text"] {
            margin-top: 4px;
            border: 1.5px solid #38f9d7;
            border-radius: 8px;
            padding: 9px;
            font-size: 15px;
            background: #f7f7f7;
            transition: border 0.2s, background 0.2s;
        }
        #popup-content input[type="text"]:focus {
            outline: none;
            border: 2px solid #43e97b;
            background: #e0fff7;
        }
        #popup-content button[type="submit"] {
            background: linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 11px 0;
            margin-top: 8px;
            transition: background 0.2s;
            font-size: 16px;
        }
        #popup-content button[type="submit"]:hover {
            background:linear-gradient(90deg,#38f9d7 0%,#43e97b 100%);
        }
        #btn-delete {
            background: #e74c3c;
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 11px 0;
            margin-top: 2px;
            font-size: 16px;
            transition: background 0.2s;
        }
        #btn-delete:hover {
            background: #c0392b !important;
        }
        @media (max-width: 900px) {
            .sidebar,
            .sidebar:hover,
            .sidebar:focus-within {
                width: 100vw;
                position: static;
                height: auto;
                flex-direction: row;
                box-shadow: none;
                padding: 10px 0;
                margin-bottom: 18px;
            }
            .sidebar h3 { display: none; }
            .sidebar a {
                display: inline-flex;
                width: auto;
                margin: 0 6px;
                padding: 10px 14px;
                font-size: 0.95rem;
            }
            .sidebar a .text {
                opacity: 1;
                width: auto;
                margin-left: 8px;
            }
            .main,
            .sidebar:hover ~ .main,
            .sidebar:focus-within ~ .main {
                margin-left: 0;
                padding: 18px 2vw 18px 2vw;
            }
            table, th, td {
                font-size: 0.92rem;
            }
        }
        @media (max-width: 600px) {
            .table-container, #popup-content { width: 98vw !important; min-width: unset; }
            th, td { padding: 8px 6px; font-size: 14px; }
            .main h2 { font-size: 1.2rem; }
        }
    </style>
</head>
<body>
<!-- Sidebar Menu (copy dari index.php) -->
<div class="sidebar">
  <h3>Menu</h3>
  <a href="menu_utama.php" style="background: #ff9800;">
    <span class="icon">🏠</span><span class="text">Menu Utama</span>
  </a>
  <a href="index.php" style="background: #1976d2;">
    <span class="icon">🏡</span><span class="text">Beranda</span>
  </a>
  <a href="guru.php" style="background: #43e97b;">
    <span class="icon">👨‍🏫</span><span class="text">Data Guru</span>
  </a>
  <a href="murid.php" style="background: #4caf50;">
    <span class="icon">🎓</span><span class="text">Data Murid</span>
  </a>
  <a href="tambah_data.php" style="background: #9c27b0;">
    <span class="icon">➕</span><span class="text">Tambah Data</span>
  </a>
  <a href="scan_kartu.php" style="background: #00bcd4;">
    <span class="icon">💳</span><span class="text">Scan Kartu</span>
  </a>
  <a href="log_rekap.php" style="background: #e91e63; display: flex; align-items: center;">
    <span class="icon">📋</span><span class="text">Rekap Log</span>
  </a>
  <a href="profile.php" style="background: #ff5722;">
    <span class="icon">👤</span><span class="text">Profile</span>
  </a>
  <a href="logout.php" style="background: #f44336;">
    <span class="icon">🚪</span><span class="text">Logout</span>
  </a>
  <a href="akun.php" style="background: #f44336;">
    <span class="icon">🚪</span><span class="text">akun</span>
  </a>
</div>

<div class="main">
    <h2>Data Tabel Users</h2>
    <div class="table-container">
        <table>
            <tr>
                <?php if (!empty($data_user)): foreach(array_keys($data_user[0]) as $col): ?>
                    <th><?= htmlspecialchars($col) ?></th>
                <?php endforeach; ?>
                <th>Aksi</th>
                <?php endif; ?>
            </tr>
            <?php foreach($data_user as $row): ?>
            <tr>
                <?php foreach($row as $val): ?>
                    <td><?= htmlspecialchars($val) ?></td>
                <?php endforeach; ?>
                <td><button onclick="openPopup(<?= $row['id'] ?>, '<?= $row['username'] ?>', '<?= $row['password'] ?>')">Edit</button></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="popup">
        <div id="popup-content">
            <button id="close-popup" onclick="closePopup()">&times;</button>
            <h3>Edit User</h3>
            <form onsubmit="event.preventDefault(); updateUser();" style="display:flex;flex-direction:column;gap:10px;">
                <input type="hidden" id="edit-id">
                <label>Username
                    <input type="text" id="edit-username" placeholder="Username">
                </label>
                <label>Password
                    <input type="text" id="edit-password" placeholder="Password">
                </label>
                <button type="submit">Simpan</button>
                <button type="button" id="btn-delete" onclick="deleteUser()">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function openPopup(id, username, password) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-username').value = username;
        document.getElementById('edit-password').value = password;
        document.getElementById('popup').style.display = 'flex';
    }
    function closePopup() {
        document.getElementById('popup').style.display = "none";
    }
    window.onclick = function(e) {
        if (e.target == document.getElementById('popup')) {
            closePopup();
        }
    }
    function updateUser() {
        const id = document.getElementById('edit-id').value;
        const username = document.getElementById('edit-username').value;
        const password = document.getElementById('edit-password').value;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.responseText.trim() === "success") {
                location.reload();
            } else {
                alert("Gagal update.");
            }
        };
        xhr.send(`update_user=1&id=${id}&username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`);
    }
    function deleteUser() {
        if (!confirm("Yakin ingin menghapus akun ini?")) return;
        const id = document.getElementById('edit-id').value;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.responseText.trim() === "success") {
                location.reload();
            } else {
                alert("Gagal menghapus akun.");
            }
        };
        xhr.send(`delete_user=1&id=${id}`);
    }
</script>
</body>
</html>

