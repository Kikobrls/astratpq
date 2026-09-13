<?php
/**
 * DataTables server-side endpoint — Kategori Iuran
 *
 * Same server-side pagination/search/sort pattern as
 * api/datatable_jurnal_umum.php, so pages/iuran/index.php can use the
 * exact same DataTables.net (bootstrap5) look and behaviour.
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

// Columns MUST be in the same order as the <thead> in pages/iuran/index.php
// (No, Nama Iuran, Tipe Periode, Tahun/Periode, Nominal, Keterangan, Aksi)
$columns = [
    ['db' => null,             'search' => false, 'order' => false], // No
    ['db' => 'b.nama_iuran',   'search' => true,  'order' => true],  // Nama Iuran
    ['db' => 'b.periode_tipe', 'search' => true,  'order' => true],  // Tipe Periode
    ['db' => 'b.tahun',        'search' => true,  'order' => true],  // Tahun/Periode
    ['db' => 'b.nominal',      'search' => false, 'order' => true],  // Nominal
    ['db' => 'b.keterangan',   'search' => true,  'order' => false], // Keterangan
    ['db' => null,             'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "b.id_iuran, b.nama_iuran, b.periode_tipe, b.tahun, b.nominal, b.keterangan,
                 COUNT(s.id_santri) as jumlah_santri",
    'from' => "iuran b LEFT JOIN santri s ON b.id_iuran = s.id_iuran",
    'group_by' => 'b.id_iuran',
    'columns' => $columns,
]);

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_iuran'];
    $periode_tipe = $row['periode_tipe'] ?: 'bulanan';

    $rowJson = htmlspecialchars(json_encode([
        'id_iuran' => $id,
        'nama_iuran' => $row['nama_iuran'],
        'tahun' => $row['tahun'],
        'periode_tipe' => $periode_tipe,
        'nominal' => $row['nominal'],
        'keterangan' => $row['keterangan'],
    ]), ENT_QUOTES, 'UTF-8');

    $tipeBadge = '<span class="badge bg-' . ($periode_tipe == 'tahunan' ? 'warning' : 'primary') . '">'
        . ucfirst($periode_tipe) . '</span>';

    $aksi = '<div class="d-flex justify-content-center align-items-center">'
        . '<div class="dropdown no-caret me-2">'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_' . $id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'
        . '<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_' . $id . '">'
        . '<div class="dropdown-header">Aksi:</div>'
        . '<a class="dropdown-item" href="javascript:void(0)" onclick=\'editIuran(' . $rowJson . ')\'><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit</a>'
        . '</div></div>'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\')"><i class="far fa-trash-alt"></i></button>'
        . '</div>';

    $data[] = [
        $no++,
        '<span class="font-weight-bold">' . htmlspecialchars($row['nama_iuran']) . '</span>',
        $tipeBadge,
        '<span class="badge bg-primary text-white">' . htmlspecialchars($row['tahun']) . '</span>',
        '<span class="font-weight-bold text-success">' . formatRupiah($row['nominal']) . '</span>',
        htmlspecialchars($row['keterangan']),
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
