<?php
$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id_guru = isset($_GET['id_guru']) ? intval($_GET['id_guru']) : 0;

// Ambil nama guru
$nama_guru = "";
$qGuru = $koneksi->query("SELECT nama FROM users WHERE id=$id_guru LIMIT 1");
if ($qGuru && $qGuru->num_rows > 0) {
    $nama_guru = $qGuru->fetch_assoc()['nama'];
}

// Tambah jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $hari = $koneksi->real_escape_string($_POST['hari']);
    $jam = $koneksi->real_escape_string($_POST['jam']);
    $mapel = $koneksi->real_escape_string($_POST['mapel']);
    $ruangan = $koneksi->real_escape_string($_POST['ruangan']);
    $koneksi->query("INSERT INTO jadwal (id_guru, hari, jam, mapel, ruangan) VALUES ($id_guru, '$hari', '$jam', '$mapel', '$ruangan')");
    echo "<script>window.location='jadwal.php?id_guru=$id_guru';</script>";
    exit;
}

// Ambil data untuk form edit jika ada request edit
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $q = $koneksi->query("SELECT * FROM jadwal WHERE id=$edit_id AND id_guru=$id_guru LIMIT 1");
    if ($q && $q->num_rows > 0) {
        $edit_data = $q->fetch_assoc();
    }
}

// Proses update jadwal (bukan AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id']) && !isset($_POST['edit_popup'])) {
    $id_jadwal = intval($_POST['edit_id']);
    $hari = $koneksi->real_escape_string($_POST['hari']);
    $jam = $koneksi->real_escape_string($_POST['jam']);
    $mapel = $koneksi->real_escape_string($_POST['mapel']);
    $ruangan = $koneksi->real_escape_string($_POST['ruangan']);
    $koneksi->query("UPDATE jadwal SET hari='$hari', jam='$jam', mapel='$mapel', ruangan='$ruangan' WHERE id=$id_jadwal AND id_guru=$id_guru");
    echo "<script>window.location='jadwal.php?id_guru=$id_guru';</script>";
    exit;
}

// Hapus jadwal (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_id'])) {
    $id_jadwal = intval($_POST['hapus_id']);
    $koneksi->query("DELETE FROM jadwal WHERE id=$id_jadwal AND id_guru=$id_guru");
    echo "OK";
    exit;
}

// Ambil data jadwal
$jadwal = [];
$qJadwal = $koneksi->query("SELECT * FROM jadwal WHERE id_guru=$id_guru ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam");
while ($row = $qJadwal->fetch_assoc()) {
    $jadwal[] = $row;
}

// Untuk AJAX: ambil data jadwal untuk edit
if (isset($_GET['get_jadwal']) && isset($_GET['id_jadwal'])) {
    $id_jadwal = intval($_GET['id_jadwal']);
    $q = $koneksi->query("SELECT * FROM jadwal WHERE id=$id_jadwal AND id_guru=$id_guru LIMIT 1");
    if ($q && $q->num_rows > 0) {
        $data = $q->fetch_assoc();
        echo json_encode($data);
    } else {
        echo "{}";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Guru</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #e0f7fa 0%, #e8f5e9 100%);
            margin: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 820px;
            margin: 48px auto 32px auto;
            background: rgba(255,255,255,0.98);
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(44,62,80,0.13);
            padding: 38px 32px 24px 32px;
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
            opacity: 0.07;
            border-radius: 50%;
            z-index: 0;
        }
        h2 {
            color: #219a6f;
            margin-bottom: 18px;
            text-align: center;
            font-size: 2.1rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-shadow: 0 2px 8px #38f9d755;
            position: relative;
            z-index: 1;
        }
        table {
            margin: 0 auto 18px auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            background: rgba(255,255,255,0.98);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 18px rgba(44,62,80,0.10);
            position: relative;
            z-index: 1;
        }
        th, td {
            border: none;
            padding: 13px 10px;
            text-align: center;
        }
        th {
            font-size: 1.08rem;
            font-weight: 700;
            letter-spacing: 0.7px;
            border-bottom: 3px solid #e0f7fa;
        }
        th:nth-child(1) { background: linear-gradient(90deg, #ff9800 0%, #ffc107 100%); color: #fff; }
        th:nth-child(2) { background: linear-gradient(90deg, #2196f3 0%, #00bcd4 100%); color: #fff; }
        th:nth-child(3) { background: linear-gradient(90deg, #e91e63 0%, #f44336 100%); color: #fff; }
        th:nth-child(4) { background: linear-gradient(90deg, #4caf50 0%, #43e97b 100%); color: #fff; }
        th:nth-child(5) { background: linear-gradient(90deg, #607d8b 0%, #00bcd4 100%); color: #fff; }

        /* Baris warna-warni */
        tr:nth-child(even) td:nth-child(1) { background: #fff8e1; }
        tr:nth-child(even) td:nth-child(2) { background: #e3f2fd; }
        tr:nth-child(even) td:nth-child(3) { background: #fce4ec; }
        tr:nth-child(even) td:nth-child(4) { background: #e8f5e9; }
        tr:nth-child(even) td:nth-child(5) { background: #eceff1; }

        tr:nth-child(odd) td:nth-child(1) { background: #ffe0b2; }
        tr:nth-child(odd) td:nth-child(2) { background: #b3e5fc; }
        tr:nth-child(odd) td:nth-child(3) { background: #f8bbd0; }
        tr:nth-child(odd) td:nth-child(4) { background: #c8e6c9; }
        tr:nth-child(odd) td:nth-child(5) { background: #cfd8dc; }

        tr:hover td {
            background: linear-gradient(90deg, #e0f7fa 0%, #e8f5e9 100%) !important;
            transition: background 0.18s;
        }
        td {
            font-size: 1.01rem;
            color: #232526;
            font-weight: 500;
            border-bottom: 1.5px solid #e0f7fa;
        }
        .btn {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 22px;
            font-weight: bold;
            cursor: pointer;
            margin: 0 6px;
            font-size: 1rem;
            box-shadow: 0 2px 8px #38f9d733;
            transition: background 0.18s, color 0.18s, transform 0.18s, box-shadow 0.18s;
            letter-spacing: 0.5px;
        }
        .btn:hover {
            background: #fff;
            color: #43e97b;
            border: 1.5px solid #43e97b;
            transform: scale(1.07);
            box-shadow: 0 4px 16px #43e97b33;
        }
        .btn-cancel {
            background: #e53935;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 22px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 10px;
            font-size: 1rem;
            transition: background 0.18s, color 0.18s, transform 0.18s;
        }
        .btn-cancel:hover {
            background: #fff;
            color: #e53935;
            border: 1.5px solid #e53935;
            transform: scale(1.04);
        }
        form { margin-bottom: 18px; }
        input[type="text"] {
            width: 97%;
            padding: 10px;
            border-radius: 7px;
            border: 1.5px solid #43e97b;
            font-size: 1rem;
            background: #f9f9f9;
            transition: border 0.18s, background 0.18s;
        }
        input[type="text"]:focus {
            border-color: #219a6f;
            background: #fff;
        }
        label {
            display: block;
            margin-bottom: 4px;
            color: #219a6f;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .form-row {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
        }
        .form-row > div { flex: 1; }
        /* Modal */
        #editModal, #tambahModal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(44,62,80,0.18);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(2px);
        }
        #editModal .modal-content, #tambahModal .modal-content {
            background: #fff;
            padding: 34px 28px 22px 28px;
            border-radius: 18px;
            min-width: 340px;
            max-width: 98vw;
            position: relative;
            box-shadow: 0 8px 32px #38f9d733;
            animation: popIn 0.22s;
        }
        @keyframes popIn {
            0% { transform: scale(0.85); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        #editModal h3, #tambahModal h3 {
            color: #219a6f;
            margin-bottom: 18px;
            text-align: center;
            font-size: 1.18rem;
            font-weight: 700;
        }
        #editModal label, #tambahModal label {
            font-weight: 600;
            color: #219a6f;
            margin-bottom: 4px;
            display: block;
            text-align: left;
        }
        #editModal input[type="text"], #tambahModal input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 7px;
            border: 1.5px solid #43e97b;
            margin-bottom: 14px;
            font-size: 1rem;
            background: #f9f9f9;
            transition: border 0.18s;
        }
        #editModal input[type="text"]:focus, #tambahModal input[type="text"]:focus {
            border-color: #219a6f;
            background: #fff;
        }
        #editModal .btn-cancel, #tambahModal .btn-cancel {
            margin-left: 0;
            margin-top: 8px;
        }
        /* Notifikasi */
        #notif {
            display: none;
            margin-bottom: 16px;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1.05rem;
            box-shadow: 0 2px 8px #38f9d733;
        }
        /* Accent */
        .container::after {
            content: "";
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #e91e63 0%, #ffc107 100%);
            opacity: 0.07;
            border-radius: 50%;
            z-index: 0;
        }
        /* Responsive */
        @media (max-width: 900px) {
            .container { padding: 18px 6vw; }
            h2 { font-size: 1.2rem; }
            table, th, td { font-size: 0.95rem; }
            #editModal .modal-content, #tambahModal .modal-content { min-width: 90vw; padding: 18px 6vw; }
        }
        @media (max-width: 600px) {
            .container { padding: 12px 2vw; }
            table, th, td { font-size: 0.88rem; }
            #editModal .modal-content, #tambahModal .modal-content { min-width: 98vw; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Jadwal <?= htmlspecialchars($nama_guru) ?></h2>

    <!-- Tombol Tambah Jadwal -->
    <div style="text-align:right; margin-bottom:18px;">
        <button type="button" class="btn" onclick="openTambahModal()">+ Tambah Jadwal</button>
    </div>

    <!-- Tabel Jadwal -->
    <table>
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam</th>
                <th>Mata Pelajaran</th>
                <th>Ruangan</th>
                <th>Aksi</th>
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
                    <td>
                        <button type="button" class="btn" onclick="openEditModal(<?= $row['id'] ?>)">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="color:#bbb;">Belum ada jadwal.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <div style="text-align:center; margin-top:18px;">
        <a href="guru.php" class="btn-cancel">Kembali ke Data Guru</a>
    </div>

    <!-- Edit Data Jadwal (bukan modal) -->
    <?php if ($edit_data): ?>
        <div style="margin:32px auto;max-width:500px;">
            <h3 style="color:#219a6f;text-align:center;">Edit Jadwal</h3>
            <form method="post">
                <input type="hidden" name="edit_id" value="<?= $edit_data['id'] ?>">
                <div style="margin-bottom:10px;">
                    <label>Hari</label>
                    <input type="text" name="hari" value="<?= htmlspecialchars($edit_data['hari']) ?>" required>
                </div>
                <div style="margin-bottom:10px;">
                    <label>Jam</label>
                    <input type="text" name="jam" value="<?= htmlspecialchars($edit_data['jam']) ?>" required>
                </div>
                <div style="margin-bottom:10px;">
                    <label>Mata Pelajaran</label>
                    <input type="text" name="mapel" value="<?= htmlspecialchars($edit_data['mapel']) ?>" required>
                </div>
                <div style="margin-bottom:10px;">
                    <label>Ruangan</label>
                    <input type="text" name="ruangan" value="<?= htmlspecialchars($edit_data['ruangan']) ?>" required>
                </div>
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="jadwal.php?id_guru=<?= $id_guru ?>" class="btn-cancel" style="margin-left:8px;">Batal</a>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Edit Jadwal -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div class="modal-content" style="background:#fff; padding:30px 24px 18px 24px; border-radius:14px; min-width:340px; max-width:98vw; position:relative; box-shadow:0 8px 32px rgba(44,62,80,0.13); margin:auto;">
    <h3 style="color:#219a6f;text-align:center;">Edit Jadwal</h3>
    <form id="editJadwalForm">
      <input type="hidden" name="edit_id" id="edit_id">
      <div style="margin-bottom:10px;">
        <label>Hari</label>
        <input type="text" name="hari" id="edit_hari" required>
      </div>
      <div style="margin-bottom:10px;">
        <label>Jam</label>
        <input type="text" name="jam" id="edit_jam" required>
      </div>
      <div style="margin-bottom:10px;">
        <label>Mata Pelajaran</label>
        <input type="text" name="mapel" id="edit_mapel" required>
      </div>
      <div style="margin-bottom:10px;">
        <label>Ruangan</label>
        <input type="text" name="ruangan" id="edit_ruangan" required>
      </div>
      <button type="submit" class="btn">Simpan Perubahan</button>
      <button type="button" class="btn-cancel" onclick="closeEditModal()" style="margin-left:8px;">Batal</button>
      <button type="button" class="btn-cancel" style="background:#e53935;margin-left:8px;" onclick="hapusJadwal()">Hapus</button>
    </form>
    <form method="post" id="hapusForm" style="display:none;">
      <input type="hidden" name="hapus_id" id="hapus_id">
    </form>
  </div>
</div>

<!-- Modal Tambah Jadwal -->
<div id="tambahModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div class="modal-content" style="background:#fff; padding:30px 24px 18px 24px; border-radius:14px; min-width:340px; max-width:98vw; position:relative; box-shadow:0 8px 32px rgba(44,62,80,0.13); margin:auto;">
    <h3 style="color:#219a6f;text-align:center;">Tambah Jadwal</h3>
    <form id="tambahJadwalForm">
      <div style="margin-bottom:10px;">
        <label>Hari</label>
        <input type="text" name="hari" id="tambah_hari" required>
      </div>
      <div style="margin-bottom:10px;">
        <label>Jam</label>
        <input type="text" name="jam" id="tambah_jam" required>
      </div>
      <div style="margin-bottom:10px;">
        <label>Mata Pelajaran</label>
        <input type="text" name="mapel" id="tambah_mapel" required>
      </div>
      <div style="margin-bottom:10px;">
        <label>Ruangan</label>
        <input type="text" name="ruangan" id="tambah_ruangan" required>
      </div>
      <button type="submit" class="btn">Simpan</button>
      <button type="button" class="btn-cancel" onclick="closeTambahModal()" style="margin-left:8px;">Batal</button>
    </form>
  </div>
</div>

<!-- Notifikasi -->
<div id="notif" style="display:none; margin-bottom:16px; padding:10px 18px; border-radius:8px; font-weight:bold;"></div>

<script>
let jadwalIdToDelete = null;

function openEditModal(id) {
    fetch('jadwal.php?id_guru=<?= $id_guru ?>&get_jadwal=1&id_jadwal=' + id)
      .then(res => res.json())
      .then(data => {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_hari').value = data.hari;
        document.getElementById('edit_jam').value = data.jam;
        document.getElementById('edit_mapel').value = data.mapel;
        document.getElementById('edit_ruangan').value = data.ruangan;
        document.getElementById('hapus_id').value = data.id;
        jadwalIdToDelete = data.id;
        document.getElementById('editModal').style.display = 'flex';
      });
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
function openTambahModal() {
    document.getElementById('tambahModal').style.display = 'flex';
}
function closeTambahModal() {
    document.getElementById('tambahModal').style.display = 'none';
}

// Proses edit jadwal via AJAX
document.getElementById('editJadwalForm').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('edit_popup', '1');
    fetch('jadwal.php?id_guru=<?= $id_guru ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(res => {
        if (res.trim() === "OK") {
            window.location.reload();
        } else {
            alert("Gagal menyimpan perubahan!");
        }
    });
};

// Proses tambah jadwal via AJAX
document.getElementById('tambahJadwalForm').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('tambah', '1');
    fetch('jadwal.php?id_guru=<?= $id_guru ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(res => {
        if (res.trim() === "OK") {
            closeTambahModal();
            showNotif("Jadwal berhasil ditambahkan!", true);
            setTimeout(() => window.location.reload(), 1200);
        } else {
            showNotif("Gagal menambah jadwal!", false);
        }
    });
};

// Proses hapus jadwal via AJAX dari popup
function hapusJadwal() {
    if (confirm('Hapus jadwal ini?')) {
        var formData = new FormData();
        formData.append('hapus_id', document.getElementById('hapus_id').value);
        fetch('jadwal.php?id_guru=<?= $id_guru ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.text())
        .then(res => {
            if (res.trim() === "OK") {
                window.location.reload();
            } else {
                alert("Gagal menghapus jadwal!");
            }
        });
    }
}

// Tutup popup jika klik di luar modal
document.getElementById('editModal').onclick = function(e) {
    if (e.target === this) closeEditModal();
}
document.getElementById('tambahModal').onclick = function(e) {
    if (e.target === this) closeTambahModal();
}

function showNotif(msg, sukses) {
    var notif = document.getElementById('notif');
    notif.innerText = msg;
    notif.style.display = 'block';
    notif.style.background = sukses ? '#d4edda' : '#f8d7da';
    notif.style.color = sukses ? '#155724' : '#721c24';
    notif.style.border = sukses ? '1.5px solid #43e97b' : '1.5px solid #e53935';
    setTimeout(() => { notif.style.display = 'none'; }, 2500);
}
</script>
</body>
</html>