<?php
/**
 * Export Laporan Pembayaran, Jurnal, dan Kas
 * Sistem Administrasi Keuangan Sekolah
 */

session_start();
require_once '../../config/database.php';
require_once '../../config/app.php';
require_once '../../includes/kop_surat.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Get Settings for Header (Kop Surat)
// Kop surat pada output PDF/cetak dirender oleh includes/kop_surat.php
// supaya bentuknya sama persis dengan kop surat resmi TPQ.
$kop = kopSuratData();
$nama_sekolah = $kop['nama'];
$alamat_sekolah = $kop['alamat'];
$telp_sekolah = $kop['telp'];
$email_sekolah = $kop['email'];
$website_sekolah = $kop['website'];

// Filters
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');

$bulan_list = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];

// Aggregation query: 1 query for the whole year instead of 24
// (2 queries x 12 months) — same fix as pages/laporan/index.php.
$report_data = [];
$total_pemasukan_year = 0;
$total_pengeluaran_year = 0;
$total_sisa_kas_year = 0;

for ($m = 1; $m <= 12; $m++) {
    $bulan_str = str_pad($m, 2, '0', STR_PAD_LEFT);
    $report_data[$m] = [
        'bulan' => $bulan_list[$bulan_str],
        'total_in' => 0.0,
        'jurnal_out' => 0.0,
        'sisa_kas' => 0.0,
    ];
}

$tahun_int = (int) $filter_tahun;
$agg_query = mysqli_query($conn, "
    SELECT MONTH(tanggal) as bulan, jenis, SUM(nominal) as total
    FROM jurnal_umum
    WHERE tanggal >= '{$tahun_int}-01-01' AND tanggal < '" . ($tahun_int + 1) . "-01-01'
    GROUP BY MONTH(tanggal), jenis
");

while ($agg_query && ($row = mysqli_fetch_assoc($agg_query))) {
    $m = (int) $row['bulan'];
    if (!isset($report_data[$m])) {
        continue;
    }
    $total = (float) $row['total'];
    if ($row['jenis'] === 'Pemasukan') {
        $report_data[$m]['total_in'] = $total;
    } else {
        $report_data[$m]['jurnal_out'] = $total;
    }
}

foreach ($report_data as $m => &$data) {
    $data['sisa_kas'] = $data['total_in'] - $data['jurnal_out'];
    $total_pemasukan_year += $data['total_in'];
    $total_pengeluaran_year += $data['jurnal_out'];
    $total_sisa_kas_year += $data['sisa_kas'];
}
unset($data);

$type = isset($_GET['type']) ? $_GET['type'] : 'print';

// Handle Export to Excel (.xlsx asli via PhpSpreadsheet)
if ($type == 'excel') {
    require_once '../../vendor/autoload.php';

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Laporan ' . $filter_tahun);

    // Kop / Judul
    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue('A1', strtoupper($nama_sekolah));
    $sheet->mergeCells('A2:E2');
    $sheet->setCellValue('A2', 'LAPORAN REKAPITULASI KEUANGAN BULANAN');
    $sheet->mergeCells('A3:E3');
    $sheet->setCellValue('A3', 'Tahun Keuangan: ' . $filter_tahun);

    $titleStyle = [
        'font' => ['bold' => true, 'size' => 12],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    ];
    $sheet->getStyle('A1')->applyFromArray($titleStyle);
    $sheet->getStyle('A2')->applyFromArray($titleStyle);
    $sheet->getStyle('A3:E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $rowNum = 5;
    if (!empty($alamat_sekolah)) {
        $sheet->mergeCells('A4:E4');
        $sheet->setCellValue('A4', $alamat_sekolah);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $rowNum = 6;
    }

    // Header tabel
    $headers = ['No', 'Bulan', 'Total Pemasukan', 'Total Pengeluaran', 'Sisa Kas'];
    $col = 'A';
    foreach ($headers as $h) {
        $sheet->setCellValue($col . $rowNum, $h);
        $col++;
    }
    $headerRange = 'A' . $rowNum . ':E' . $rowNum;
    $sheet->getStyle($headerRange)->applyFromArray([
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4E73DF']],
        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
    ]);

    // Data
    $dataStart = $rowNum + 1;
    $r = $dataStart;
    foreach ($report_data as $m => $row) {
        $sheet->setCellValue('A' . $r, $m);
        $sheet->setCellValue('B' . $r, $row['bulan']);
        $sheet->setCellValue('C' . $r, $row['total_in']);
        $sheet->setCellValue('D' . $r, $row['jurnal_out']);
        $sheet->setCellValue('E' . $r, $row['sisa_kas']);
        $sheet->getStyle('A' . $r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $r++;
    }

    // Total
    $sheet->mergeCells('A' . $r . ':B' . $r);
    $sheet->setCellValue('A' . $r, 'TOTAL TAHUNAN');
    $sheet->setCellValue('C' . $r, $total_pemasukan_year);
    $sheet->setCellValue('D' . $r, $total_pengeluaran_year);
    $sheet->setCellValue('E' . $r, $total_sisa_kas_year);
    $sheet->getStyle('A' . $r . ':E' . $r)->applyFromArray([
        'font' => ['bold' => true],
        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
    ]);
    $sheet->getStyle('A' . $r)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // Border seluruh tabel + format angka rupiah
    $tableRange = 'A' . $rowNum . ':E' . $r;
    $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $sheet->getStyle('C' . $dataStart . ':E' . $r)->getNumberFormat()->setFormatCode('#,##0');

    // Lebar kolom
    $sheet->getColumnDimension('A')->setWidth(6);
    $sheet->getColumnDimension('B')->setWidth(16);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(20);

    $filename = 'laporan_keuangan_bulanan_' . $filter_tahun . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

// Handle Export to PDF via Dompdf
if ($type == 'pdf') {
    require_once '../../vendor/autoload.php';
    
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', false);
    // Kop surat resmi memakai huruf serif (Times New Roman), jadi font
    // bawaan dokumen diselaraskan agar hasil PDF mirip surat aslinya.
    $options->set('defaultFont', 'times');

    $dompdf = new \Dompdf\Dompdf($options);

    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Laporan Keuangan Bulanan <?php echo $filter_tahun; ?></title>
        <style>
            @page {
                margin: 18mm 15mm 15mm 15mm;
            }
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 10px;
                color: #222;
                line-height: 1.3;
            }
            <?php echo kopSuratCss(true); ?>
            .report-title {
                text-align: center;
                margin-bottom: 15px;
            }
            .report-title h2 {
                font-size: 13px;
                margin: 0 0 3px 0;
                font-weight: bold;
                text-transform: uppercase;
            }
            .report-title p {
                font-size: 10px;
                margin: 0;
                color: #555;
            }
            .data-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .data-table th {
                border: 1px solid #444;
                background-color: #f2f2f2;
                padding: 6px 4px;
                font-weight: bold;
                text-align: center;
            }
            .data-table td {
                border: 1px solid #555;
                padding: 5px 4px;
            }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
            .font-bold { font-weight: bold; }
            .bg-totals {
                background-color: #f9f9f9;
                font-weight: bold;
            }
            .signature-table {
                width: 100%;
                margin-top: 30px;
                border-collapse: collapse;
            }
            .signature-table td {
                border: none;
                width: 50%;
            }
            .signature-box {
                text-align: center;
                font-size: 10px;
            }
            .signature-space {
                height: 50px;
            }
        </style>
    </head>
    <body>
        <!-- Kop Surat resmi (logo + nama lembaga + alamat + garis ganda) -->
        <?php echo kopSuratHtml(); ?>

        <!-- Title -->
        <div class="report-title">
            <h2>Laporan Rekapitulasi Keuangan Bulanan</h2>
            <p>Tahun: <strong><?php echo $filter_tahun; ?></strong></p>
        </div>

        <!-- Table Data -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Bulan</th>
                    <th>Total Pemasukan</th>
                    <th>Total Pengeluaran</th>
                    <th>Sisa Kas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report_data as $m => $row): ?>
                    <tr>
                        <td class="text-center"><?php echo $m; ?></td>
                        <td class="font-bold"><?php echo $row['bulan']; ?></td>
                        <td class="text-right font-bold"><?php echo formatRupiah($row['total_in']); ?></td>
                        <td class="text-right"><?php echo formatRupiah($row['jurnal_out']); ?></td>
                        <td class="text-right font-bold"><?php echo formatRupiah($row['sisa_kas']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="bg-totals">
                    <td colspan="2" class="text-center">TOTAL TAHUNAN</td>
                    <td class="text-right font-bold"><?php echo formatRupiah($total_pemasukan_year); ?></td>
                    <td class="text-right"><?php echo formatRupiah($total_pengeluaran_year); ?></td>
                    <td class="text-right font-bold"><?php echo formatRupiah($total_sisa_kas_year); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Signature -->
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
    $dompdf->stream("laporan_keuangan_bulanan_" . $filter_tahun . ".pdf", ["Attachment" => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan Bulanan <?php echo $filter_tahun; ?> - <?php echo htmlspecialchars($nama_sekolah); ?></title>
    <!-- Add Font Awesome for buttons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        /* Control Panel Styles (Hidden in Print) */
        .control-panel {
            background-color: #f1f3f5;
            border-bottom: 1px solid #dee2e6;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-primary { background-color: #4e73df; color: #fff; }
        .btn-primary:hover { background-color: #2e59d9; }
        .btn-secondary { background-color: #858796; color: #fff; }
        .btn-secondary:hover { background-color: #717384; }
        .btn-success { background-color: #1cc88a; color: #fff; }
        .btn-success:hover { background-color: #17a673; }
        .btn-danger { background-color: #e74a3b; color: #fff; }
        .btn-danger:hover { background-color: #be2617; }
        .btn i { margin-right: 8px; }

        /* Kop Surat (Letterhead) — sama dengan kop surat resmi TPQ */
        <?php echo kopSuratCss(false); ?>

        /* Report Header */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .report-header h2 {
            font-size: 16px;
            margin: 0 0 5px 0;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #222;
        }
        .report-header p {
            font-size: 13px;
            margin: 0;
            color: #666;
        }

        /* Table Design */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 8px;
        }
        th {
            background-color: #f8f9fc;
            font-weight: 600;
            color: #495057;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .bg-totals {
            background-color: #f8f9fc;
            font-weight: bold;
        }

        /* Signature block */
        .signature-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            font-size: 13px;
        }
        .signature-box {
            text-align: center;
            width: 250px;
        }
        .signature-date {
            margin-bottom: 60px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Print optimization */
        @media print {
            body {
                background-color: #fff;
            }
            .control-panel {
                display: none !important;
            }
            .container {
                box-shadow: none;
                padding: 0;
                margin: 0 auto;
                max-width: 100%;
            }
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Control bar for preview mode -->
    <div class="control-panel no-print">
        <a href="index.php?tahun=<?php echo $filter_tahun; ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <div>
            <a href="export.php?tahun=<?php echo $filter_tahun; ?>&type=excel" class="btn btn-success" style="margin-right: 10px;">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="export.php?tahun=<?php echo $filter_tahun; ?>&type=pdf" class="btn btn-danger" style="margin-right: 10px;">
                <i class="fas fa-file-pdf"></i> Download PDF (Dompdf)
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="container">
        <!-- Kop Surat resmi (logo + nama lembaga + alamat + garis ganda) -->
        <?php echo kopSuratHtml(); ?>

        <!-- Report Header -->
        <div class="report-header">
            <h2>LAPORAN REKAPITULASI KEUANGAN BULANAN</h2>
            <p>Tahun Buku/Keuangan: <strong><?php echo $filter_tahun; ?></strong></p>
        </div>

        <!-- Report Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Bulan</th>
                    <th>Total Pemasukan</th>
                    <th>Total Pengeluaran</th>
                    <th>Sisa Kas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($report_data as $m => $row): ?>
                    <tr>
                        <td class="text-center"><?php echo $m; ?></td>
                        <td class="font-bold"><?php echo $row['bulan']; ?></td>
                        <td class="text-right font-bold" style="color: #2e59d9;"><?php echo formatRupiah($row['total_in']); ?></td>
                        <td class="text-right" style="color: #e74a3b;"><?php echo formatRupiah($row['jurnal_out']); ?></td>
                        <td class="text-right font-bold" style="color: #1cc88a;"><?php echo formatRupiah($row['sisa_kas']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="bg-totals">
                    <td colspan="2" class="text-center">TOTAL TAHUNAN</td>
                    <td class="text-right font-bold" style="color: #2e59d9;"><?php echo formatRupiah($total_pemasukan_year); ?></td>
                    <td class="text-right" style="color: #e74a3b;"><?php echo formatRupiah($total_pengeluaran_year); ?></td>
                    <td class="text-right font-bold" style="color: #1cc88a;"><?php echo formatRupiah($total_sisa_kas_year); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Signature Area -->
        <div class="signature-container">
            <div class="signature-box">
                <div><?php echo htmlspecialchars(kopSuratTempatTanggal()); ?></div>
                <div class="signature-date"><?php echo htmlspecialchars(kopSuratJabatan()); ?></div>
                <div style="height: 60px;"></div>
                <div class="signature-name"><?php echo htmlspecialchars(kopSuratPenandaTangan()); ?></div>
            </div>
        </div>
    </div>
</body>
</html>


