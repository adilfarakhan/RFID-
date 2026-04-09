<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "akun");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$error = "";
$success = "";

// Proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_password'])) {
    $username = $koneksi->real_escape_string($_POST['username']);
    $new_pass = $koneksi->real_escape_string($_POST['new_password']);
    
    $cek = $koneksi->query("SELECT * FROM users WHERE username='$username'");
    if ($cek->num_rows > 0) {
        $update = $koneksi->query("UPDATE users SET password='$new_pass' WHERE username='$username'");
        if ($update) {
            $success = "Password berhasil direset! Silakan login.";
        } else {
            $error = "Gagal mengubah password!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset_password'])) {
    $username = $koneksi->real_escape_string($_POST['username']);
    $password = $koneksi->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $username;
        $user = $result->fetch_assoc();
        $_SESSION['nama_user'] = $user['nama_user'];
        header("Location: menu_utama.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Sistem Absensi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(-45deg, #ff6a00, #43e97b, #38f9d7, #ee0979, #1976d2, #ff9800);
            background-size: 400% 400%;
            animation: gradientMove 12s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        @keyframes gradientMove {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }
        .bg-ornament {
            position: fixed;
            top: -80px; left: -80px;
            width: 300px; height: 300px;
            background: radial-gradient(circle at 60% 40%, #ff6a00 0%, #ee0979 80%, transparent 100%);
            z-index: 0;
            filter: blur(12px);
        }
        .bg-ornament2 {
            position: fixed;
            bottom: -100px; right: -100px;
            width: 340px; height: 340px;
            background: radial-gradient(circle at 40% 60%, #38f9d7 0%, #43e97b 80%, transparent 100%);
            z-index: 0;
            filter: blur(14px);
        }
        .login-box {
            background: rgba(255,255,255,0.97);
            padding: 38px 32px 32px 32px;
            border-radius: 18px;
            width: 350px;
            max-width: 95vw;
            box-shadow: 0 8px 32px #38f9d733;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        .login-box h2 {
            margin: 0 0 18px 0;
            color: #219a6f;
            letter-spacing: 1px;
            font-weight: 700;
            text-align: center;
        }
        .input-group { margin-bottom: 18px; width: 100%; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 14px;
            font-size: 1rem;
            border: 1.5px solid #38f9d7;
            border-radius: 8px;
            background: #f7f7f7;
            transition: border 0.2s, background 0.2s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border: 2px solid #43e97b;
            background: #e0fff7;
        }
        button {
            width: 100%;
            padding: 12px 0;
            background: linear-gradient(90deg,#43e97b 0%,#38f9d7 100%);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 8px;
            font-size: 16px;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px #38f9d722;
        }
        button:hover {
            background:linear-gradient(90deg,#38f9d7 0%,#43e97b 100%);
            box-shadow: 0 4px 16px #38f9d744;
        }
        .forgot-btn {
            background: #e91e63;
            margin-top: 10px;
            font-size: 15px;
        }
        .forgot-btn:hover {
            background: #ad1457;
        }
        .error, .success {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 0;
            width: 100%;
        }
        .error { background: #ffebee; color: #e53935; }
        .success { background: #e3fcec; color: #219a6f; }
        @media (max-width: 500px) {
            .login-box {
                padding: 22px 8vw 22px 8vw;
                width: 98vw;
            }
            .bg-ornament, .bg-ornament2 {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="bg-ornament"></div>
    <div class="bg-ornament2"></div>
    <div class="login-box">
        <h2>Login Sistem Absensi</h2>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>
        
        <form method="post" id="loginForm" autocomplete="off">
            <div class="input-group">
                <input type="text" name="username" id="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
            <button type="button" class="forgot-btn" onclick="startForgotPassword()">Lupa Password?</button>
        </form>
    </div>

    <script>
    let generatedOTP = null;

    function showNotification(msg) {
        if (Notification.permission === "granted") {
            new Notification("OTP Anda", { body: msg });
        } else {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification("OTP Anda", { body: msg });
                } else {
                    alert("Izinkan notifikasi agar bisa menerima OTP.");
                }
            });
        }
    }

    function startForgotPassword() {
        const username = document.getElementById("username").value.trim();
        if (!username) {
            alert("Masukkan username terlebih dahulu!");
            return;
        }

        generatedOTP = Math.floor(100000 + Math.random() * 900000);
        localStorage.setItem("otp", generatedOTP);
        localStorage.setItem("otp_user", username);
        showNotification("Kode OTP Anda: " + generatedOTP);

        setTimeout(() => {
            const userInput = prompt("Masukkan OTP yang telah dikirim ke notifikasi:");
            if (userInput == generatedOTP) {
                const newPassword = prompt("OTP benar! Masukkan password baru:");
                if (newPassword && newPassword.length >= 3) {
                    const form = document.createElement("form");
                    form.method = "post";
                    form.style.display = "none";

                    form.innerHTML = `
                        <input type="hidden" name="username" value="${username}">
                        <input type="hidden" name="new_password" value="${newPassword}">
                        <input type="hidden" name="reset_password" value="1">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                } else {
                    alert("Password baru tidak valid!");
                }
            } else {
                alert("OTP salah!");
            }
        }, 100); // delay 100ms agar notifikasi muncul dulu
    }
    </script>
</body>
</html>
