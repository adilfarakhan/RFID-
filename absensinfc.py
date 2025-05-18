import mysql.connector
from smartcard.System import readers
import time

# Fungsi koneksi database
def connect_db():
    try:
        db = mysql.connector.connect(
            host="127.0.0.1",
            user="root",
            password="",
        )
        cursor = db.cursor()
        cursor.execute("CREATE DATABASE IF NOT EXISTS absensi")
        db.database = "absensi"

        # Membuat tabel users (untuk guru)
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_kartu VARCHAR(50) NOT NULL UNIQUE,
            nama VARCHAR(100) NOT NULL,
            jabatan VARCHAR(100) NOT NULL
        )
        """)

        # Membuat tabel murid
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS murid (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_kartu VARCHAR(50) NOT NULL UNIQUE,
            nama VARCHAR(100) NOT NULL,
            kelas VARCHAR(50) NOT NULL,
            nis VARCHAR(20) NOT NULL UNIQUE
        )
        """)

        # Membuat tabel absensi_log
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS absensi_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_kartu VARCHAR(50) NOT NULL,
            role ENUM('guru', 'murid') NOT NULL,
            waktu DATETIME NOT NULL
        )
        """)

        print("Database dan tabel siap ✅")
        return db
    except mysql.connector.Error as err:
        print(f"Database Error: {err}")
        exit()

# Fungsi koneksi NFC reader
def connect_reader():
    r = readers()
    if len(r) == 0:
        print("❌ NFC Reader tidak ditemukan! Pastikan reader terhubung dan Smart Card Service menyala.")
        exit()
    reader = r[0]
    print(f"✅ Menggunakan reader: {reader}")
    connection = reader.createConnection()
    connection.connect()
    return connection

# Fungsi membaca kartu
def baca_kartu(connection):
    try:
        data, sw1, sw2 = connection.transmit([0xFF, 0xCA, 0x00, 0x00, 0x00])
        uid = ''.join(format(x, '02X') for x in data)
        return uid
    except Exception as e:
        print(f"Error membaca kartu: {e}")
        return None

# Main program
db = connect_db()
cursor = db.cursor()
connection = connect_reader()

print("\n📡 Siap untuk scan kartu...\nTekan CTRL+C untuk keluar.")

try:
    while True:
        uid = baca_kartu(connection)
        if uid:
            print(f"🔍 UID Terdeteksi: {uid}")

            # Cek di tabel guru
            cursor.execute("SELECT nama, jabatan FROM users WHERE id_kartu = %s", (uid,))
            guru = cursor.fetchone()

            # Cek di tabel murid
            cursor.execute("SELECT nama, kelas FROM murid WHERE id_kartu = %s", (uid,))
            murid = cursor.fetchone()

            # Cek di tabel profile
            cursor.execute("SELECT nama, jabatan_kelas FROM profile WHERE id_kartu = %s", (uid,))
            profile = cursor.fetchone()

            if guru:
                nama, jabatan = guru
                print(f"✅ Selamat datang, Guru {nama} ({jabatan})")
                # Catat absensi guru
                cursor.execute("INSERT INTO absensi_log (id_kartu, role, waktu) VALUES (%s, 'guru', NOW())", (uid,))
                db.commit()
                print("📝 Absensi guru dicatat.\n")
            
            elif murid:
                nama, kelas = murid
                print(f"✅ Selamat datang, Murid {nama} (Kelas: {kelas})")
                # Catat absensi murid
                cursor.execute("INSERT INTO absensi_log (id_kartu, role, waktu) VALUES (%s, 'murid', NOW())", (uid,))
                db.commit()
                print("📝 Absensi murid dicatat.\n")
            
            elif profile:
                nama, jabatan_kelas = profile
                print(f"✅ Selamat datang, {nama} ({jabatan_kelas})")
                # Catat absensi berdasarkan profil
                cursor.execute("INSERT INTO absensi_log (id_kartu, role, waktu) VALUES (%s, 'murid', NOW())", (uid,))
                db.commit()
                print("📝 Absensi profil dicatat.\n")
            
            else:
                print("⚠️ Kartu tidak terdaftar!\n")

            time.sleep(2)  # Delay sedikit supaya tidak dobel scan
        else:
            time.sleep(1)

except KeyboardInterrupt:
    print("\nProgram dihentikan. Sampai jumpa!")
finally:
    db.close()