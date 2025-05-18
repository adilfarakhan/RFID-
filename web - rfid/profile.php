<!-- filepath: c:\xampp\htdocs\web - rfid\profile.php -->
<?php 
// Tidak ada koneksi database yang diperlukan di sini
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesantren Peradaban Dunia Jagat 'Arsy</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            color: #333;
        }
        .header {
            position: relative;
            width: 100%;
            height: 340px;
            background: url('jagat.webp') no-repeat center center/cover;
            display: flex;
            align-items: flex-end;
            box-shadow: 0 4px 18px rgba(44,62,80,0.10);
        }
        .header h1 {
            margin: 0 0 28px 38px;
            color: #fff;
            background: rgba(44,62,80,0.55);
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            box-shadow: 0 2px 12px rgba(44,62,80,0.13);
        }
        .menu {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            padding: 12px 0;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(44,62,80,0.06);
        }
        .menu a {
            color: #fff;
            text-decoration: none;
            margin: 0 18px;
            font-size: 1.08rem;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 8px 18px;
            border-radius: 6px;
            transition: background 0.18s, color 0.18s;
            display: inline-block;
        }
        .menu a:hover, .menu a.active {
            background: #fff;
            color: #43e97b;
            text-decoration: none;
        }
        .container {
            max-width: 900px;
            margin: 32px auto 32px auto;
            padding: 32px 28px 28px 28px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(44,62,80,0.10);
        }
        h2 {
            color: #219a6f;
            margin-top: 0;
            margin-bottom: 18px;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        h3 {
            color: #2e7d32;
            margin-bottom: 10px;
            font-size: 1.1rem;
            font-weight: 600;
        }
        p {
            line-height: 1.7;
            font-size: 1.05rem;
        }
        .team {
            display: flex;
            gap: 24px;
            justify-content: center;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        .team-member {
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
            padding: 22px 18px 18px 18px;
            text-align: center;
            flex: 1 1 220px;
            max-width: 240px;
            min-width: 180px;
            margin: 0 8px;
            transition: transform 0.18s, box-shadow 0.18s;
        }
        .team-member:hover {
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 8px 24px rgba(44,62,80,0.16);
        }
        .team-member img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin-bottom: 12px;
            border: 3px solid #43e97b;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(44,62,80,0.10);
        }
        .team-member h3 {
            margin: 10px 0 6px 0;
            font-size: 1.08rem;
            color: #219a6f;
        }
        .team-member p {
            margin: 4px 0;
            font-size: 0.98rem;
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 14px 0 10px 0;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            font-size: 1rem;
            letter-spacing: 0.5px;
            margin-top: 32px;
        }
        /* Responsive */
        @media (max-width: 900px) {
            .header { height: 200px; }
            .header h1 { font-size: 1.2rem; margin: 0 0 12px 12px; padding: 8px 14px; }
            .container { padding: 18px 6vw; }
            .team { flex-direction: column; gap: 16px; }
            .team-member { max-width: 98vw; margin: 0 auto; }
        }
        @media (max-width: 600px) {
            .container { padding: 12px 2vw; }
            .header { height: 120px; }
            .header h1 { font-size: 1rem; }
            .menu a { font-size: 0.95rem; padding: 7px 8px; }
        }
        /* Button style for alamat */
        .container a.button {
            display: inline-block;
            background: #43e97b;
            color: #fff !important;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.97rem;
            margin: 2px 0;
            text-decoration: none;
            transition: background 0.18s, color 0.18s;
        }
        .container a.button:hover {
            background: #38f9d7;
            color: #219a6f !important;
        }
    </style>
</head>
<body>

<!-- Gambar Sampul -->
<div class="header">
    <h1>Pesantren Peradaban Dunia Jagat 'Arsy</h1>
</div>
<div class="menu">
    <a href="menu_utama.php">Menu Utama</a>
    <a href="profile.php" class="active">Profile</a>
</div>

<!-- Konten Profil Perusahaan -->
<div class="container" id="about">
    <h2>Tentang Kami</h2>
    <p>
        Selamat Datang di <strong>Pesantren Peradaban Dunia Jagat 'Arsy</strong>. Lembaga pendidikan Islam modern yang mengintegrasikan nilai-nilai keislaman dengan perkembangan ilmu pengetahuan dan teknologi. Didirikan dengan visi membangun peradaban Islam yang unggul dan berdaya saing global.
    </p>

    <h2 id="visi">Visi Kami</h2>
    <p>
        Mewujudkan generasi Rabbani yang unggul dalam iman, ilmu, dan akhlak menuju peradaban dunia yang berkeadilan dan berkemajuan.
    </p>

    <h2 id="misi">Misi Kami</h2>
    <p>
        Pesantren Jagat 'Arsy memiliki misi untuk menyelenggarakan pendidikan berbasis Al-Qur’an dan Sunnah yang terintegrasi dengan sains dan teknologi, membangun karakter santri yang berakhlak mulia, mandiri, dan berwawasan global, mengembangkan lingkungan pesantren sebagai pusat peradaban Islam modern, serta mendorong inovasi dalam proses pembelajaran melalui pemanfaatan teknologi informasi dan sistem pendidikan digital.
    </p>

    <h2 id="kontak">Kontak Kami</h2>
    <p>
        <strong>Alamat :</strong> <a href="https://www.google.com/maps/dir//Komp.+Nusaloka+BSD,+Jl.+Yapen+Jl.+Pam+No.21+Sektor14-6,+Rw.+Mekar+Jaya,+Kec.+Serpong,+Kota+Tangerang+Selatan,+Banten+15310/@-6.3107292,106.6138705,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x2e69e5374064f5eb:0xe20dee9138fed9c5!2m2!1d106.6962724!2d-6.3107357?entry=ttu&g_ep=EgoyMDI1MDUxMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="button">Komp. Nusaloka BSD, Jl. Yapen Jl. Pam No.21 Sektor14-6, Rw. Mekar Jaya, Kec. Serpong, Kota Tangerang Selatan, Banten 15310</a><br>
        <strong>Kontak :</strong> 08111543738 - 02175872121<br>
        <strong>Email :</strong> info@jagatarsy.sch.id<br>
        <strong>Youtube :</strong> <a href="https://www.youtube.com/@ArsyEduTainment/featured" target="_blank">Pesantren Peradaban Dunia Jagat 'Arsy</a><br>
        <strong>Website :</strong> <a href="https://m.jagatarsy.sch.id/" target="_blank">https://m.jagatarsy.sch.id/</a>
    </p>
</div>

<!-- Konten Profil Tim -->
<div class="container" id="team">
    <h2>Identitas Team Kami</h2>
    <div class="team">
        <!-- Member 1 -->
        <div class="team-member">
            <img src="willy.jpg" alt="Willy Adreansyach">
            <h3>Willy Adreansyach</h3>
            <p>Kontak : 081323877723</p>
            <p>Email: 19210185@bsi.ac.id</p>
        </div>
        <!-- Member 2 -->
        <div class="team-member">
            <img src="adil.jpg" alt="Muhammad Adil Farakhan">
            <h3>Muhammad Adil Farakhan</h3>
            <p>Kontak : 085706506949</p>
            <p>Email: 19210347@bsi.ac.id</p>
        </div>
        <!-- Member 3 -->
        <div class="team-member">
            <img src="zaky.jpg" alt="Zaky Putranto">
            <h3>Zaky Putranto</h3>
            <p>Kontak : 089502838420</p>
            <p>Email: 19210204@bsi.ac.id</p>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> AWZ Group. All rights reserved.
</div>

</body>
</html>