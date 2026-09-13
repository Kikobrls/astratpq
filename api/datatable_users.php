<?php
/**
 * DataTables server-side endpoint — Data Pengguna
 *
 * Same server-side pagination/search/sort pattern as
 * api/datatable_jurnal_umum.php, so pages/users/index.php can use the
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
if (($_SESSION['level'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Columns MUST be in the same order as the <thead> in pages/users/index.php
// (No, Username, Nama, Email, Level, Status, Aksi)
$columns = [
    ['db' => null,          'search' => false, 'order' => false], // No
    ['db' => 'g.username',  'search' => true,  'order' => true],  // Username
    ['db' => 'g.nama',      'search' => true,  'order' => true],  // Nama
    ['db' => 'g.email',     'search' => true,  'order' => true],  // Email
    ['db' => 'g.level',     'search' => true,  'order' => true],  // Level
    ['db' => 'g.status',    'search' => true,  'order' => true],  // Status
    ['db' => null,          'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "g.id_user, g.username, g.nama, g.email, g.no_telp, g.level, g.status",
    'from' => "users g",
    'columns' => $columns,
]);

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_user'];

    $badge_color = 'primary';
    if ($row['level'] == 'admin') {
        $badge_color = 'danger';
    } elseif ($row['level'] == 'kepala_tpq') {
        $badge_color = 'warning';
    }
    $level_text = $row['level'] == 'kepala_tpq' ? 'Kepala TPQ' : ucfirst($row['level']);
    $levelBadge = '<span class="badge bg-' . $badge_color . '">' . $level_text . '</span>';

    $statusBadge = '<span class="badge bg-' . ($row['status'] == 'active' ? 'success' : 'secondary') . '">'
        . ucfirst($row['status']) . '</span>';

    // Same payload editUser() in index.php expects (password intentionally omitted).
    $rowJson = htmlspecialchars(json_encode([
        'id_user' => $id,
        'username' => $row['username'],
        'nama' => $row['nama'],
        'email' => $row['email'],
        'no_telp' => $row['no_telp'],
        'level' => $row['level'],
        'status' => $row['status'],
    ]), ENT_QUOTES, 'UTF-8');

    $aksi = '<div class="d-flex justify-content-center align-items-center">'
        . '<div class="dropdown no-caret me-2">'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_' . $id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'
        . '<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_' . $id . '">'
        . '<div class="dropdown-header">Aksi:</div>'
        . '<a class="dropdown-item" href="javascript:void(0)" onclick=\'editUser(' . $rowJson . ')\'><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit</a>'
        . '</div></div>';
    if ($id != (int) ($_SESSION['id_user'] ?? 0)) {
        $aksi .= '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\')"><i class="far fa-trash-alt"></i></button>';
    }
    $aksi .= '</div>';

    $data[] = [
        $no++,
        '<code>' . htmlspecialchars($row['username']) . '</code>',
        htmlspecialchars($row['nama']),
        htmlspecialchars($row['email']),
        $levelBadge,
        $statusBadge,
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
