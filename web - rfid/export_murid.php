# Buat file export_murid_pdf.php dengan FPDF library untuk generate PDF dari data murid

export_murid_pdf = '''<?php
require('fpdf.php');

$koneksi = new mysqli("localhost", "root", "", "absensi");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Data Murid', 0, 1, 'C');
$pdf->Ln(5);

// Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 10, 'No', 1);
$pdf->Cell(40, 10, 'UID Kartu', 1);
$pdf->Cell(60, 10, 'Nama', 1);
$pdf->Cell(30, 10, 'Kelas', 1);
$pdf->Cell(40, 10, 'NIS', 1);
$pdf->Ln();

// Data
$pdf->SetFont('Arial', '', 12);
$no = 1;
$res = $koneksi->query("SELECT * FROM murid ORDER BY id ASC");
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $pdf->Cell(10, 10, $no, 1);
        $pdf->Cell(40, 10, $row['id_kartu'], 1);
        $pdf->Cell(60, 10, $row['nama'], 1);
        $pdf->Cell(30, 10, $row['kelas'], 1);
        $pdf->Cell(40, 10, $row['nis'], 1);
        $pdf->Ln();
        $no++;
    }
} else {
    $pdf->Cell(180, 10, 'Tidak ada data murid.', 1, 1, 'C');
}

$pdf->Output();
?>
'''

# Simpan file PHP ini ke dalam ZIP agar bisa diunduh
import zipfile

zip_path = "/mnt/data/export_murid_pdf.zip"
with zipfile.ZipFile(zip_path, 'w') as zipf:
    zipf.writestr("export_murid_pdf.php", export_murid_pdf)

zip_path
