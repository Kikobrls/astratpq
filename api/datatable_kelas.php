<?php
/**
 * DataTables server-side endpoint — Data Kelas
 *
 * Same server-side pagination/search/sort pattern as
 * api/datatable_jurnal_umum.php, so pages/kelas/index.php can use the
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

// Columns MUST be in the same order as the <thead> in pages/kelas/index.php
// (No, Nama Kelas, Jumlah Santri, Aksi)
$columns = [
    ['db' => null,             'search' => false, 'order' => false], // No
    ['db' => 'k.nama_kelas',   'search' => true,  'order' => true],  // Nama Kelas
    ['db' => null,             'search' => false, 'order' => false], // Jumlah Santri (aggregated)
    ['db' => null,             'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "k.id_kelas, k.nama_kelas, COUNT(s.id_santri) as jumlah_santri",
    'from' => "kelas k LEFT JOIN santri s ON k.id_kelas = s.id_kelas",
    'group_by' => 'k.id_kelas',
    'columns' => $columns,
]);

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_kelas'];

    $rowJson = htmlspecialchars(json_encode([
        'id_kelas' => $id,
        'nama_kelas' => $row['nama_kelas'],
    ]), ENT_QUOTES, 'UTF-8');

    $aksi = '<div class="d-flex justify-content-center align-items-center">'
        . '<div class="dropdown no-caret me-2">'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_' . $id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'
        . '<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_' . $id . '">'
        . '<div class="dropdown-header">Aksi:</div>'
        . '<a class="dropdown-item" href="javascript:void(0)" onclick=\'editKelas(' . $rowJson . ')\'><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit</a>'
        . '</div></div>'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\')"><i class="far fa-trash-alt"></i></button>'
        . '</div>';

    $data[] = [
        $no++,
        htmlspecialchars($row['nama_kelas']),
        '<span class="badge bg-info text-dark">' . (int) $row['jumlah_santri'] . '</span>',
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
