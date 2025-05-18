<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Menu Utama</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
      min-height: 100vh;
    }

    .header {
      background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
      color: white;
      padding: 32px 20px 24px 20px;
      text-align: center;
      letter-spacing: 1px;
      box-shadow: 0 4px 16px rgba(44,62,80,0.08);
    }

    .header h1 {
      margin: 0;
      font-size: 2.2rem;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-shadow: 0 2px 8px rgba(44,62,80,0.08);
    }

    .menu-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 28px;
      padding: 48px 24px 32px 24px;
      max-width: 1100px;
      margin: 0 auto;
    }

    .menu-item {
      background: white;
      border-radius: 18px;
      box-shadow: 0 4px 18px rgba(44,62,80,0.10);
      padding: 38px 20px 30px 20px;
      text-align: center;
      transition: transform 0.22s, box-shadow 0.22s, background 0.22s;
      text-decoration: none;
      color: #333;
      position: relative;
      overflow: hidden;
      border: 2px solid transparent;
    }

    .menu-item::before {
      content: "";
      position: absolute;
      left: -60px;
      top: -60px;
      width: 120px;
      height: 120px;
      background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
      opacity: 0.08;
      border-radius: 50%;
      z-index: 0;
      transition: opacity 0.2s;
    }

    .menu-item:hover {
      transform: translateY(-7px) scale(1.03);
      box-shadow: 0 10px 28px rgba(44,62,80,0.18);
      background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
      border: 2px solid #38f9d7;
    }
    .menu-item:hover::before {
      opacity: 0.18;
    }

    .menu-icon {
      font-size: 56px;
      color: #43e97b;
      margin-bottom: 10px;
      filter: drop-shadow(0 2px 8px #38f9d755);
      transition: color 0.2s, filter 0.2s;
      z-index: 1;
      position: relative;
      display: inline-block;
      animation: popicon 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    .menu-item:hover .menu-icon {
      color: #38f9d7;
      filter: drop-shadow(0 4px 16px #43e97b55);
      animation: popicon 0.4s;
    }

    @keyframes popicon {
      0% { transform: scale(0.7);}
      70% { transform: scale(1.15);}
      100% { transform: scale(1);}
    }

    .menu-item h3 {
      margin-top: 18px;
      font-size: 1.25rem;
      font-weight: 600;
      color: #2e7d32;
      letter-spacing: 0.5px;
      z-index: 1;
      position: relative;
    }

    @media (max-width: 700px) {
      .menu-container {
        grid-template-columns: 1fr;
        padding: 24px 6vw 24px 6vw;
        gap: 18px;
      }
      .header h1 {
        font-size: 1.2rem;
      }
      .menu-item {
        padding: 28px 10px 22px 10px;
      }
      .menu-icon {
        font-size: 38px;
      }
    }
  </style>
</head>
<body>

<div class="header">
  <h1>📋 Menu Utama Sistem Absensi Jagat Arsy</h1>
</div>

<div class="menu-container">
  <a href="scan_kartu.php" class="menu-item">
    <div class="menu-icon">📡</div>
    <h3>Scan Kartu</h3>
  </a>
  
  <a href="guru.php" class="menu-item">
    <div class="menu-icon">👨‍🏫</div>
    <h3>Data Guru</h3>
  </a>

  <a href="murid.php" class="menu-item">
    <div class="menu-icon">🎓</div>
    <h3>Data Murid</h3>
  </a>

  <a href="tambah_data.php" class="menu-item">
    <div class="menu-icon">➕</div>
    <h3>Tambah Data</h3>
  </a>

  <a href="log_rekap.php" class="menu-item">
    <div class="menu-icon">📊</div>
    <h3>Rekap Absensi</h3>
  </a>

  <a href="profile.php?id_kartu=123456789" class="menu-item">
    <div class="menu-icon">👤</div>
    <h3>Profil Saya</h3>
  </a>

  <a href="index.php" class="menu-item">
    <div class="menu-icon">🏠</div>
    <h3>Beranda</h3>
    
  </a>
   <a href="profile.php?id_kartu=123456789" class="menu-item">
    <div class="menu-icon">📚</div>
    <h3>Jadwal Pelajaran</h3>
  </a>
</div>

</body>
</html>
