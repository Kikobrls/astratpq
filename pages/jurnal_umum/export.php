<?php
/**
 * Export Jurnal Umum
 *  - type=excel (default) : .xlsx asli via PhpSpreadsheet
 *  - type=pdf             : PDF ber-kop surat resmi via Dompdf
 * Sistem Administrasi Keuangan Sekolah
 */

session_start();
require_once '../../config/app.php';
require_once '../../config/database.php';
require_once '../../includes/kop_surat.php';
require_once '../../vendor/autoload.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Settings untuk kop
$kop            = kopSuratData();
$nama_sekolah   = $kop['nama'];
$alamat_sekolah = $kop['alamat'];

// Filter (sama seperti index.php)
$filter_pos    = isset($_GET['filter_pos']) ? sanitize($_GET['filter_pos']) : '';
$filter_dari   = isset($_GET['dari'])    ? sanitize($_GET['dari'])    : '';
$filter_sampai = isset($_GET['sampai'])  ? sanitize($_GET['sampai'])  : '';
$filter_jenis  = isset($_GET['jenis'])   ? sanitize($_GET['jenis'])   : '';

$where_parts = [];
if (!empty($filter_pos))    $where_parts[] = "j.id_pos = '$filter_pos'";
if (!empty($filter_dari))   $where_parts[] = "j.tanggal >= '$filter_dari'";
if (!empty($filter_sampai)) $where_parts[] = "j.tanggal <= '$filter_sampai'";
if (!empty($filter_jenis))  $where_parts[] = "j.jenis = '$filter_jenis'";
$where = !empty($where_parts) ? 'WHERE ' . implode(' AND ', $where_parts) : '';

$query = "SELECT j.*, p.nama_pos
          FROM jurnal_umum j
          JOIN pos_keuangan p ON j.id_pos = p.id_pos
          $where
          ORDER BY j.tanggal DESC, j.id_jurnal DESC";
$result = mysqli_query($conn, $query);

$total_masuk  = 0;
$total_keluar = 0;

$type = isset($_GET['type']) ? $_GET['type'] : 'excel';

/* =========================================================
 * Export PDF (ber-kop surat resmi TPQ)
 * ========================================================= */
if ($type === 'pdf') {
    // Baris data dikumpulkan dulu supaya bisa dipakai dua kali:
    // sekali untuk menghitung total, sekali untuk mencetak tabel.
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $nominal = (float) $row['nominal'];
        if ($row['jenis'] === 'Pemasukan') {
            $total_masuk += $nominal;
        } else {
            $total_keluar += $nominal;
        }
        $rows[] = $row;
    }
    $saldo_akhir = $total_masuk - $total_keluar;

    // Keterangan periode pada judul laporan
    $periode_text = 'Semua Periode';
    if (!empty($filter_dari) && !empty($filter_sampai)) {
        $periode_text = tanggalIndo($filter_dari) . ' s.d. ' . tanggalIndo($filter_sampai);
    } elseif (!empty($filter_dari)) {
        $periode_text = 'Mulai ' . tanggalIndo($filter_dari);
    } elseif (!empty($filter_sampai)) {
        $periode_text = 'Sampai ' . tanggalIndo($filter_sampai);
    }

    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', false);
    $options->set('defaultFont', 'times');

    $dompdf = new \Dompdf\Dompdf($options);

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Jurnal Umum</title>
        <style>
            @page { margin: 18mm 15mm 15mm 15mm; }
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 10px;
                color: #222;
                line-height: 1.3;
            }
            <?php echo kopSuratCss(true); ?>
            .report-title { text-align: center; margin-bottom: 12px; }
            .report-title h2 {
                font-size: 13px;
                margin: 0 0 3px 0;
                font-weight: bold;
                text-transform: uppercase;
            }
            .report-title p { font-size: 10px; margin: 0; color: #555; }
            .data-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
            .data-table th {
                border: 1px solid #444;
                background-color: #f2f2f2;
                padding: 5px 4px;
                font-weight: bold;
                text-align: center;
            }
            .data-table td { border: 1px solid #555; padding: 4px; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .font-bold { font-weight: bold; }
            .bg-totals { background-color: #f9f9f9; font-weight: bold; }
            .signature-table { width: 100%; margin-top: 28px; border-collapse: collapse; }
            .signature-table td { border: none; width: 50%; }
            .signature-box { text-align: center; font-size: 10px; }
            .signature-space { height: 50px; }
        </style>
    </head>
    <body>
        <?php echo kopSuratHtml(); ?>

        <div class="report-title">
            <h2>Laporan Jurnal Umum</h2>
            <p>Periode: <strong><?php echo htmlspecialchars($periode_text); ?></strong></p>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 13%;">Tanggal</th>
                    <th style="width: 20%;">Pos Keuangan</th>
                    <th>Uraian</th>
                    <th style="width: 13%;">Jenis</th>
                    <th style="width: 17%;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="6" class="text-center">Tidak ada data pada periode ini.</td></tr>
                <?php else: ?>
                    <?php $no = 1;
                    foreach ($rows as $row): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td class="text-center"><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_pos']); ?></td>
                            <td><?php echo htmlspecialchars($row['uraian']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['jenis']); ?></td>
                            <td class="text-right"><?php echo formatRupiah($row['nominal']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr class="bg-totals">
                    <td colspan="5" class="text-right">Total Pemasukan</td>
                    <td class="text-right"><?php echo formatRupiah($total_masuk); ?></td>
                </tr>
                <tr class="bg-totals">
                    <td colspan="5" class="text-right">Total Pengeluaran</td>
                    <td class="text-right"><?php echo formatRupiah($total_keluar); ?></td>
                </tr>
                <tr class="bg-totals">
                    <td colspan="5" class="text-right">Saldo Akhir</td>
                    <td class="text-right"><?php echo formatRupiah($saldo_akhir); ?></td>
                </tr>
            </tfoot>
        </table>

        <table class="signature-table">
            <tr>
                <td></td>
                <td>
                    <div class="signature-box">
                        <p><?php echo htmlspecialchars(kopSuratTempatTanggal()); ?></p>
                        <p><?php echo htmlspecialchars(kopSuratJabatan()); ?></p>
                        <div class="signature-space"></div>
                        <p><u><?php echo htmlspecialchars(kopSuratPenandaTangan()); ?></u></p>
                    </div>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    $html = ob_get_clean();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('Jurnal_Umum_' . date('Y-m-d') . '.pdf', ['Attachment' => true]);
    exit;
}

$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Jurnal Umum');

// Kop / Judul
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', strtoupper($nama_sekolah));
$sheet->mergeCells('A2:F2');
$sheet->setCellValue('A2', 'DATA JURNAL UMUM');

$titleStyle = [
    'font' => ['bold' => true, 'size' => 12],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
];
$sheet->getStyle('A1')->applyFromArray($titleStyle);
$sheet->getStyle('A2')->applyFromArray($titleStyle);

$rowNum = 4;
if (!empty($alamat_sekolah)) {
    $sheet->mergeCells('A3:F3');
    $sheet->setCellValue('A3', $alamat_sekolah);
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $rowNum = 5;
}

// Header tabel
$headers = ['No', 'Tanggal', 'Pos Keuangan', 'Uraian', 'Jenis', 'Nominal'];
$col = 'A';
foreach ($headers as $h) {
    $sheet->setCellValue($col . $rowNum, $h);
    $col++;
}
$headerRange = 'A' . $rowNum . ':F' . $rowNum;
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4E73DF']],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
]);

// Data
$dataStart = $rowNum + 1;
$r = $dataStart;
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $nominal = (float)$row['nominal'];
    if ($row['jenis'] == 'Pemasukan') {
        $total_masuk += $nominal;
    } else {
        $total_keluar += $nominal;
    }

    $sheet->setCellValue('A' . $r, $no++);
    $sheet->setCellValueExplicit('B' . $r, date('d/m/Y', strtotime($row['tanggal'])), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('C' . $r, $row['nama_pos']);
    $sheet->setCellValue('D' . $r, $row['uraian']);
    $sheet->setCellValue('E' . $r, $row['jenis']);
    $sheet->setCellValue('F' . $r, $nominal);
    $sheet->getStyle('A' . $r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $r++;
}

$dataEnd = $r - 1;

// Baris total
$saldo_akhir = $total_masuk - $total_keluar;

$sheet->mergeCells('A' . $r . ':E' . $r);
$sheet->setCellValue('A' . $r, 'Total Pemasukan');
$sheet->setCellValue('F' . $r, $total_masuk);
$rMasuk = $r;
$r++;

$sheet->mergeCells('A' . $r . ':E' . $r);
$sheet->setCellValue('A' . $r, 'Total Pengeluaran');
$sheet->setCellValue('F' . $r, $total_keluar);
$rKeluar = $r;
$r++;

$sheet->mergeCells('A' . $r . ':E' . $r);
$sheet->setCellValue('A' . $r, 'Saldo Akhir');
$sheet->setCellValue('F' . $r, $saldo_akhir);
$rSaldo = $r;

$sheet->getStyle('A' . $rMasuk . ':F' . $rSaldo)->applyFromArray([
    'font' => ['bold' => true],
    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
]);

// Border seluruh tabel + format angka
$tableRange = 'A' . $rowNum . ':F' . $rSaldo;
$sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
if ($dataEnd >= $dataStart) {
    $sheet->getStyle('F' . $dataStart . ':F' . $dataEnd)->getNumberFormat()->setFormatCode('#,##0');
}
$sheet->getStyle('F' . $rMasuk . ':F' . $rSaldo)->getNumberFormat()->setFormatCode('#,##0');

// Lebar kolom
$sheet->getColumnDimension('A')->setWidth(6);
$sheet->getColumnDimension('B')->setWidth(14);
$sheet->getColumnDimension('C')->setWidth(24);
$sheet->getColumnDimension('D')->setWidth(40);
$sheet->getColumnDimension('E')->setWidth(14);
$sheet->getColumnDimension('F')->setWidth(18);

$filename = 'Jurnal_Umum_' . date('Y-m-d') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
exit;
