<?php
/**
 * DataTables server-side endpoint — Data Pos Keuangan
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

// Columns MUST be in the same order as the <thead> in pages/pos_keuangan/index.php
// (No, Nama Pos, Jumlah Transaksi, Aksi)
$columns = [
    ['db' => null,             'search' => false, 'order' => false], // No
    ['db' => 'p.nama_pos',     'search' => true,  'order' => true],  // Nama Pos
    ['db' => null,             'search' => false, 'order' => false], // Jumlah Transaksi
    ['db' => null,             'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "p.id_pos, p.nama_pos, COUNT(j.id_jurnal) as jumlah_transaksi",
    'from' => "pos_keuangan p LEFT JOIN jurnal_umum j ON p.id_pos = j.id_pos",
    'group_by' => 'p.id_pos',
    'columns' => $columns,
]);

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_pos'];

    $rowJson = htmlspecialchars(json_encode([
        'id_pos' => $id,
        'nama_pos' => $row['nama_pos'],
    ]), ENT_QUOTES, 'UTF-8');

    // Aksi (Edit & Hapus)
    // Cek level admin
    $aksi = '<div class="d-flex justify-content-center align-items-center">';
    if (isset($_SESSION['level']) && $_SESSION['level'] !== 'kepala_tpq') {
        $aksi .= '<div class="dropdown no-caret me-2">'
            . '<button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_' . $id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'
            . '<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_' . $id . '">'
            . '<div class="dropdown-header">Aksi:</div>'
            . '<a class="dropdown-item" href="javascript:void(0)" onclick=\'editPos(' . $rowJson . ')\'><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit</a>'
            . '</div></div>'
            . '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\')"><i class="far fa-trash-alt"></i></button>';
    } else {
        $aksi .= '<span class="text-muted">No Action</span>';
    }
    $aksi .= '</div>';

    $data[] = [
        $no++,
        htmlspecialchars($row['nama_pos']),
        '<span class="badge bg-info text-dark">' . (int) $row['jumlah_transaksi'] . '</span>',
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
