<?php
/**
 * DataTables server-side endpoint — Data Jurnal Umum
 *
 * Mirrors the fixed filters (filter_pos/jenis/dari/sampai) that
 * pages/jurnal_umum/index.php already uses for its saldo summary cards,
 * so the table and the totals always agree.
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

$filter_pos    = isset($_GET['filter_pos']) ? sanitize($_GET['filter_pos']) : '';
$filter_dari   = isset($_GET['dari'])    ? sanitize($_GET['dari'])    : '';
$filter_sampai = isset($_GET['sampai'])  ? sanitize($_GET['sampai'])  : '';
$filter_jenis  = isset($_GET['jenis'])   ? sanitize($_GET['jenis'])   : '';

$where_parts = ['1=1'];
if ($filter_pos !== '') {
    $where_parts[] = "j.id_pos = '$filter_pos'";
}
if ($filter_dari !== '') {
    $where_parts[] = "j.tanggal >= '$filter_dari'";
}
if ($filter_sampai !== '') {
    $where_parts[] = "j.tanggal <= '$filter_sampai'";
}
if ($filter_jenis !== '') {
    $where_parts[] = "j.jenis = '$filter_jenis'";
}
$where = implode(' AND ', $where_parts);

// Columns MUST be in the same order as the <thead> in pages/jurnal_umum/index.php
// (No, Tanggal, Pos Keuangan, Uraian, Jenis, Nominal, Aksi)
$columns = [
    ['db' => null,          'search' => false, 'order' => false], // No
    ['db' => 'j.tanggal',   'search' => false, 'order' => true],  // Tanggal
    ['db' => 'p.nama_pos',  'search' => true,  'order' => true],  // Pos Keuangan
    ['db' => 'j.uraian',    'search' => true,  'order' => true],  // Uraian
    ['db' => 'j.jenis',     'search' => true,  'order' => true],  // Jenis
    ['db' => 'j.nominal',   'search' => false, 'order' => true],  // Nominal
    ['db' => null,          'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "j.id_jurnal, j.tanggal, j.id_pos, j.uraian, j.jenis, j.nominal, p.nama_pos",
    'from' => "jurnal_umum j JOIN pos_keuangan p ON j.id_pos = p.id_pos",
    'where' => $where,
    'columns' => $columns,
]);

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_jurnal'];

    $jenisBadge = $row['jenis'] === 'Pemasukan'
        ? '<span class="badge bg-success text-white"><i class="fas fa-arrow-down mr-1"></i>Pemasukan</span>'
        : '<span class="badge bg-danger text-white"><i class="fas fa-arrow-up mr-1"></i>Pengeluaran</span>';

    $nominal = $row['jenis'] === 'Pemasukan'
        ? '<span class="text-success">' . formatRupiah($row['nominal']) . '</span>'
        : '<span class="text-danger">-' . formatRupiah($row['nominal']) . '</span>';

    // Same payload editJurnal() in index.php expects from the old inline json_encode($row).
    $rowJson = htmlspecialchars(json_encode([
        'id_jurnal' => $id,
        'tanggal' => $row['tanggal'],
        'id_pos' => $row['id_pos'],
        'uraian' => $row['uraian'],
        'jenis' => $row['jenis'],
        'nominal' => $row['nominal'],
    ]), ENT_QUOTES, 'UTF-8');

    $aksi = '<div class="d-flex justify-content-center align-items-center">'
        . '<div class="dropdown no-caret me-2">'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_' . $id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'
        . '<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_' . $id . '">'
        . '<div class="dropdown-header">Aksi:</div>'
        . '<a class="dropdown-item" href="javascript:void(0)" onclick=\'editJurnal(' . $rowJson . ')\'><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit</a>'
        . '</div></div>'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\')"><i class="far fa-trash-alt"></i></button>'
        . '</div>';

    $data[] = [
        $no++,
        date('d/m/Y', strtotime($row['tanggal'])),
        htmlspecialchars($row['nama_pos']),
        htmlspecialchars($row['uraian']),
        $jenisBadge,
        $nominal,
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
