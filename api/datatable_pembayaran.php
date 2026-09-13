<?php
/**
 * DataTables server-side endpoint — Riwayat Pembayaran
 *
 * Mirrors the fixed filters (bulan/tahun/kelas/iuran) that
 * pages/pembayaran/index.php already uses for its summary cards, so the
 * table and the "Total Pembayaran" / "Jumlah Detail" cards always agree.
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../config/app.php';
require_once '../includes/DataTablesServerSide.php';

if (!isset($_SESSION['login'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}
if (($_SESSION['level'] ?? '') === 'kepala_tpq') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// --- same fixed filters as the page (bulan/tahun as a date range so
// idx_pembayaran_tgl_bayar can be used, plus optional kelas/iuran) ---
$filter_bulan = isset($_GET['bulan']) ? sanitize($_GET['bulan']) : date('m');
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
$filter_kelas = isset($_GET['kelas']) ? (int) $_GET['kelas'] : 0;
$filter_iuran = isset($_GET['iuran']) ? (int) $_GET['iuran'] : 0;

$bulan_int = (int) $filter_bulan;
$tahun_int = (int) $filter_tahun;
$periode_start = sprintf('%04d-%02d-01', $tahun_int, $bulan_int);
$periode_end = date('Y-m-d', strtotime($periode_start . ' +1 month'));

$where = "p.tgl_bayar >= '" . mysqli_real_escape_string($conn, $periode_start) . "'"
    . " AND p.tgl_bayar < '" . mysqli_real_escape_string($conn, $periode_end) . "'";
if ($filter_kelas > 0) {
    $where .= " AND s.id_kelas = $filter_kelas";
}
if ($filter_iuran > 0) {
    $where .= " AND p.id_iuran = $filter_iuran";
}

// Columns MUST be in the same order as the <thead> in pages/pembayaran/index.php
// (No, Tanggal, Santri, Kelas, Periode, Tagihan/Iuran, Jumlah, Pengguna, Aksi)
$columns = [
    ['db' => null,            'search' => false, 'order' => false], // No
    ['db' => 'p.tgl_bayar',   'search' => false, 'order' => true],  // Tanggal
    ['db' => 's.nama',        'search' => true,  'order' => true],  // Santri
    ['db' => 'k.nama_kelas',  'search' => true,  'order' => true],  // Kelas
    ['db' => null,            'search' => false, 'order' => false], // Periode (computed)
    ['db' => 'b.nama_iuran',  'search' => true,  'order' => true],  // Tagihan/Iuran
    ['db' => 'p.jumlah_bayar', 'search' => false, 'order' => true], // Jumlah
    ['db' => 'g.nama',        'search' => true,  'order' => true],  // Pengguna
    ['db' => null,            'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "p.id_pembayaran, p.tgl_bayar, p.jumlah_bayar, p.periode_tipe, p.bulan_dibayar, p.tahun_dibayar,
                 s.nama AS nama_santri, k.nama_kelas, b.nama_iuran, b.tahun AS tahun_iuran, g.nama AS nama_user",
    'from' => "pembayaran p
               JOIN santri s ON p.id_santri = s.id_santri
               JOIN kelas k ON s.id_kelas = k.id_kelas
               JOIN iuran b ON p.id_iuran = b.id_iuran
               LEFT JOIN users g ON p.id_user = g.id_user",
    'where' => $where,
    'columns' => $columns,
]);

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_pembayaran'];

    $periode = formatPeriodeTagihan($row['periode_tipe'] ?: 'bulanan', $row['bulan_dibayar'], $row['tahun_dibayar']);

    $tagihan = '<strong>' . htmlspecialchars($row['nama_iuran']) . '</strong><br>'
        . '<small class="text-muted">Periode Iuran ' . htmlspecialchars((string) $row['tahun_iuran']) . '</small>';

    $aksi = '<div class="d-flex justify-content-center align-items-center">'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\')" title="Hapus Pembayaran"><i class="far fa-trash-alt"></i></button>'
        . '</div>';

    $data[] = [
        $no++,
        date('d/m/Y', strtotime($row['tgl_bayar'])),
        '<span class="font-weight-bold">' . htmlspecialchars($row['nama_santri']) . '</span>',
        htmlspecialchars($row['nama_kelas']),
        htmlspecialchars($periode),
        $tagihan,
        '<span class="font-weight-bold text-success">' . formatRupiah($row['jumlah_bayar']) . '</span>',
        htmlspecialchars($row['nama_user'] ?: '-'),
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
