<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Menu Utama</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      position: relative;
      overflow-x: hidden;
      /* Background warna-warni baru */
      background:
        linear-gradient(120deg, #f6d365 0%, #fda085 100%),
        radial-gradient(circle at 15% 85%, #43e97b 0%, #38f9d7 30%, transparent 70%),
        radial-gradient(circle at 85% 15%, #2196f3 0%, #00bcd4 30%, transparent 70%),
        radial-gradient(circle at 80% 80%, #e91e63 0%, #f44336 30%, transparent 70%),
        radial-gradient(circle at 20% 20%, #ffc107 0%, #ff9800 30%, transparent 70%);
      background-blend-mode: lighten;
    }
    .main-menu-bg {
      position: fixed;
      z-index: 0;
      top: 0; left: 0; width: 100vw; height: 100vh;
      background:
        radial-gradient(circle at 10% 10%, #43e97b 0%, #38f9d7 30%, transparent 70%),
        radial-gradient(circle at 90% 20%, #2196f3 0%, #00bcd4 30%, transparent 70%),
        radial-gradient(circle at 80% 80%, #e91e63 0%, #f44336 30%, transparent 70%),
        radial-gradient(circle at 20% 80%, #ffc107 0%, #ff9800 30%, transparent 70%);
      opacity: 0.18;
      pointer-events: none;
    }
    .main-menu {
      width: 100%;
      max-width: 1100px;
      margin: 48px auto 0 auto;
      padding: 0 16px 32px 16px;
      position: relative;
      z-index: 1;
    }
    .main-menu h1 {
      text-align: center;
      color: #fff;
      font-size: 2.3rem;
      font-weight: 700;
      letter-spacing: 1.5px;
      margin-bottom: 38px;
      text-shadow: 0 4px 18px #43e97b99, 0 2px 8px #38f9d799;
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 40%, #2196f3 100%);
      border-radius: 18px;
      padding: 18px 0 18px 0;
      box-shadow: 0 4px 18px #38f9d733;
      border: none;
    }
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 32px;
      width: 100%;
      margin: 0 auto;
    }
    .menu-card {
      border-radius: 18px;
      box-shadow: 0 4px 18px rgba(44,62,80,0.10);
      padding: 38px 20px 30px 20px;
      text-align: center;
      transition: transform 0.22s, box-shadow 0.22s, background 0.22s, border 0.22s;
      text-decoration: none;
      color: #333;
      position: relative;
      overflow: hidden;
      border: 2.5px solid transparent;
      background: #fff;
      z-index: 1;
    }
    /* Warna gradasi berbeda tiap menu */
    .menu-card:nth-child(1) { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: #fff; }
    .menu-card:nth-child(2) { background: linear-gradient(135deg, #2196f3 0%, #00bcd4 100%); color: #fff; }
    .menu-card:nth-child(3) { background: linear-gradient(135deg, #e91e63 0%, #f44336 100%); color: #fff; }
    .menu-card:nth-child(4) { background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%); color: #fff; }
    .menu-card:nth-child(5) { background: linear-gradient(135deg, #607d8b 0%, #00bcd4 100%); color: #fff; }
    .menu-card:nth-child(6) { background: linear-gradient(135deg, #8e24aa 0%, #ffb300 100%); color: #fff; }
    .menu-card:nth-child(7) { background: linear-gradient(135deg, #00bcd4 0%, #43e97b 100%); color: #fff; }
    .menu-card:nth-child(8) { background: linear-gradient(135deg, #f44336 0%, #ff9800 100%); color: #fff; }

    .menu-card::before {
      content: "";
      position: absolute;
      left: -60px;
      top: -60px;
      width: 120px;
      height: 120px;
      background: rgba(255,255,255,0.13);
      border-radius: 50%;
      z-index: 0;
      transition: opacity 0.2s;
    }
    .menu-card:hover {
      transform: translateY(-7px) scale(1.04);
      box-shadow: 0 10px 28px rgba(44,62,80,0.18);
      border: 2.5px solid #fff;
      filter: brightness(1.08);
      z-index: 2;
    }
    .menu-icon {
      font-size: 56px;
      margin-bottom: 10px;
      filter: drop-shadow(0 2px 8px #fff8);
      transition: filter 0.2s;
      z-index: 1;
      position: relative;
      display: inline-block;
      animation: popicon 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .menu-card:hover .menu-icon {
      filter: drop-shadow(0 4px 16px #fff8);
      animation: popicon 0.4s;
    }
    @keyframes popicon {
      0% { transform: scale(0.7);}
      70% { transform: scale(1.15);}
      100% { transform: scale(1);}
    }
    .menu-card h3 {
      margin-top: 18px;
      font-size: 1.25rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      z-index: 1;
      position: relative;
      color: #fff;
      text-shadow: 0 2px 8px #3335;
    }
    .menu-card:hover h3 {
      text-shadow: 0 4px 16px #fff, 0 2px 8px #3335;
    }
    @media (max-width: 900px) {
      .main-menu h1 { font-size: 1.2rem; }
      .menu-grid {
        grid-template-columns: 1fr;
        gap: 18px;
      }
      .menu-card {
        padding: 28px 10px 22px 10px;
      }
      .menu-icon {
        font-size: 38px;
      }
    }
  </style>
</head>
<body>
  <div class="main-menu-bg"></div>
  <div class="main-menu">
    <h1>📋 Menu Utama Sistem Absensi Jagat Arsy</h1>
    <div class="menu-grid">
      <a href="scan_kartu.php" class="menu-card" style="background:linear-gradient(135deg,#43e97b 0%,#38f9d7 100%);color:#fff;">
        <div class="menu-icon">📡</div>
        <h3>Scan Kartu</h3>
      </a>
      <a href="guru.php" class="menu-card" style="background:linear-gradient(135deg,#2196f3 0%,#00bcd4 100%);color:#fff;">
        <div class="menu-icon">👨‍🏫</div>
        <h3>Data Guru</h3>
      </a>
      <a href="murid.php" class="menu-card" style="background:linear-gradient(135deg,#e91e63 0%,#f44336 100%);color:#fff;">
        <div class="menu-icon">🎓</div>
        <h3>Data Murid</h3>
      </a>
      <a href="tambah_data.php" class="menu-card" style="background:linear-gradient(135deg,#ff9800 0%,#ffc107 100%);color:#fff;">
        <div class="menu-icon">➕</div>
        <h3>Tambah Data</h3>
      </a>
      <a href="log_rekap.php" class="menu-card" style="background:linear-gradient(135deg,#607d8b 0%,#00bcd4 100%);color:#fff;">
        <div class="menu-icon">📊</div>
        <h3>Rekap Absensi</h3>
      </a>
      <a href="profile.php?id_kartu=123456789" class="menu-card" style="background:linear-gradient(135deg,#8e24aa 0%,#ffb300 100%);color:#fff;">
        <div class="menu-icon">👤</div>
        <h3>Profil Saya</h3>
      </a>
      <a href="index.php" class="menu-card" style="background:linear-gradient(135deg,#00bcd4 0%,#43e97b 100%);color:#fff;">
        <div class="menu-icon">🏠</div>
        <h3>Beranda</h3>
      </a>
      <a href="akun.php" class="menu-card" style="background:linear-gradient(135deg,#ff9800 0%,#8e24aa 100%);color:#fff;">
        <div class="menu-icon">👥</div>
        <h3>Akun</h3>
      </a>
    </div>
  </div>
</body>
</html>
