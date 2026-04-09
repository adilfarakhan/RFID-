<?php

$koneksi = new mysqli("localhost", "root", "", "akun");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $koneksi->real_escape_string($_POST['username']);
    $password = $koneksi->real_escape_string($_POST['password']);
    // Jika ingin password di-hash, gunakan: $password = md5($password);

    $cek = $koneksi->query("SELECT * FROM users WHERE username='$username'");
    if (!$cek) {
        die("Query error: " . $koneksi->error);
    }
    if ($cek->num_rows > 0) {
        $pesan = "Username sudah terdaftar!";
    } else {
        $simpan = $koneksi->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($simpan) {
            $pesan = "Akun berhasil ditambahkan!";
        } else {
            $pesan = "Gagal menambah akun!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Akun</title>
    <style>
        body { font-family: Arial, sans-serif; background: #e3f2fd; }
        .form-box {
            background: #fff;
            max-width: 340px;
            margin: 60px auto;
            padding: 32px 28px 24px 28px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(44,62,80,0.13);
        }
        h2 { text-align: center; color: #2196f3; margin-bottom: 24px; }
        input {
            width: 100%; padding: 10px; margin-bottom: 16px;
            border: 1.5px solid #2196f3; border-radius: 6px; font-size: 1rem;
        }
        button {
            width: 100%; padding: 10px; background: #2196f3; color: #fff;
            border: none; border-radius: 6px; font-size: 1rem; font-weight: bold;
            cursor: pointer; transition: background 0.18s;
        }
        button:hover { background: #1976d2; }
        .msg { text-align: center; margin-bottom: 12px; color: #2e7d32; font-weight: bold; }
        .msg.err { color: #e53935; }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Tambah Akun</h2>
        <?php if ($pesan): ?>
            <div class="msg<?= strpos($pesan, 'berhasil') ? '' : ' err' ?>"><?= htmlspecialchars($pesan) ?></div>
        <?php endif; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Tambah Akun</button>
        </form>
    </div>
</body>
</html>