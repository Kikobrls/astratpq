<?php
/**
 * DataTables server-side endpoint — Data Santri
 *
 * Only fetches/renders the ONE PAGE of rows the browser asked for
 * (search + sort + LIMIT/OFFSET all happen in SQL), instead of the old
 * approach of loading every santri row on every page view.
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

// --- fixed filters (kelas dropdown / status dropdown on the page) ---
$where = '1=1';
$idKelas = (int) ($_GET['id_kelas'] ?? 0);
if ($idKelas > 0) {
    $where .= " AND s.id_kelas = $idKelas";
}
$status = $_GET['status'] ?? '';
if ($status === 'active' || $status === 'nonaktif') {
    $where .= " AND s.status = '" . mysqli_real_escape_string($conn, $status) . "'";
}

// Columns MUST be in the same order as the <thead> in pages/santri/index.php
// (No, Nama, Umur, Kelas, Status Bulanan, Status Tahunan, Status, Aksi)
$columns = [
    ['db' => null,             'search' => false, 'order' => false], // No (row number, not a DB column)
    ['db' => 's.nama',         'search' => true,  'order' => true],  // Nama Santri
    ['db' => 's.tanggal_lahir', 'search' => false, 'order' => true], // Umur
    ['db' => 'k.nama_kelas',   'search' => true,  'order' => true],  // Kelas
    ['db' => null,             'search' => false, 'order' => false], // Status Bulanan (computed)
    ['db' => null,             'search' => false, 'order' => false], // Status Tahunan (computed)
    ['db' => 's.status',       'search' => false, 'order' => true],  // Status
    ['db' => null,             'search' => false, 'order' => false], // Aksi
];

$queryResult = runServerSideQuery([
    'conn' => $conn,
    'select' => "s.id_santri, s.nama, s.tanggal_lahir, s.status, k.nama_kelas,
                 (SELECT COUNT(*) FROM pembayaran p WHERE p.id_santri = s.id_santri) AS jml_pembayaran",
    'from' => "santri s JOIN kelas k ON s.id_kelas = k.id_kelas",
    'where' => $where,
    'columns' => $columns,
]);

// Progress badges are computed only for the santri IDs on THIS page —
// not the whole table — same batch functions as before, just scoped down.
$ids = array_map('intval', array_column($queryResult['rows'], 'id_santri'));
$monthlyMap = getSantriMonthlyProgressMap($ids);
$yearlyMap = getSantriYearlyProgressMap($ids);

function dtBadge(?array $progress): string
{
    if (!$progress) {
        return '<span class="badge bg-secondary text-white badge-status">-</span>';
    }
    $text = $progress['status_text'];
    if (stripos($text, 'Belum ada pembayaran') !== false) {
        $text = 'Belum lunas';
    }
    $bg = $progress['badge'];
    $textClass = in_array($bg, ['warning', 'info', 'light'], true) ? 'text-dark' : 'text-white';
    return '<span class="badge bg-' . htmlspecialchars($bg) . ' ' . $textClass . ' badge-status">'
        . htmlspecialchars($text) . '</span>';
}

$no = $queryResult['start'] + 1;
$data = [];
foreach ($queryResult['rows'] as $row) {
    $id = (int) $row['id_santri'];

    $umur = '-';
    if (!empty($row['tanggal_lahir'])) {
        $bday = new DateTime($row['tanggal_lahir']);
        $today = new DateTime();
        $umur = $today->diff($bday)->y . ' Tahun';
    }

    $konfirmasi = $row['jml_pembayaran'] > 0
        ? "Hapus santri \"{$row['nama']}\"?\n\n{$row['jml_pembayaran']} riwayat pembayarannya JUGA akan terhapus dan Sisa Kas akan berkurang. Aksi ini tidak dapat dibatalkan."
        : "Hapus santri \"{$row['nama']}\"? Aksi ini tidak dapat dibatalkan.";

    $statusBadge = $row['status'] === 'active'
        ? '<span class="badge bg-success text-white">Aktif</span>'
        : '<span class="badge bg-secondary text-white">Nonaktif</span>';

    $aksi = '<div class="d-flex justify-content-center align-items-center">'
        . '<div class="dropdown no-caret me-2">'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_' . $id . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>'
        . '<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_' . $id . '">'
        . '<div class="dropdown-header">Aksi:</div>'
        . '<a class="dropdown-item" href="view.php?id=' . $id . '"><i class="fas fa-eye fa-sm fa-fw mr-2 text-gray-400"></i> Detail</a>'
        . '<a class="dropdown-item" href="edit.php?id=' . $id . '"><i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit</a>'
        . '</div></div>'
        . '<button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete(\'index.php?delete=' . $id . '\', this.dataset.pesan)" data-pesan="' . htmlspecialchars($konfirmasi, ENT_QUOTES) . '"><i class="far fa-trash-alt"></i></button>'
        . '</div>';

    $data[] = [
        $no++,
        '<span class="font-weight-bold text-primary">' . htmlspecialchars($row['nama']) . '</span>',
        $umur,
        htmlspecialchars($row['nama_kelas']),
        dtBadge($monthlyMap[$id] ?? null),
        dtBadge($yearlyMap[$id] ?? null),
        '<div class="text-center">' . $statusBadge . '</div>',
        $aksi,
    ];
}

echo json_encode(buildDataTablesResponse($queryResult, $data));
