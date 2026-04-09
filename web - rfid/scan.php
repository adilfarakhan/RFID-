<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Scan RFID</title>
  <style>
    body { font-family: Arial; text-align: center; padding-top: 50px; }
    input { font-size: 24px; width: 300px; padding: 10px; }
  </style>
</head>
<body>
  <h2>📡 Silakan scan kartu RFID</h2>
  <form id="f" method="post" action="save_uid.php">
    <input type="text" name="uid" id="uid" autocomplete="off" autofocus>
  </form>

  <script>
    // Setelah submit, kosongkan lagi input & fokus ulang
    document.getElementById('f').addEventListener('submit', e => {
      e.preventDefault();
      fetch('save_uid.php', {
        method:'POST',
        body: new FormData(e.target)
      }).then(r=>r.text()).then(r=>{
        // bisa tampilkan notifikasi kecil, lalu reset
        console.log(r);
        e.target.uid.value = '';
        e.target.uid.focus();
      });
    });
  </script>
</body>
</html>
<!-- ... bagian sebelumnya tetap ... -->

<div id="notif" style="margin-top:20px; font-size:20px; color:green;"></div>



