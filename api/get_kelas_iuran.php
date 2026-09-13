<?php
/**
 * API Get Kelas Iuran
 * Returns list of active iuran for an entire class
 */
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../config/app.php';

requirePaymentAccess(true);

if (!isset($_GET['id_kelas'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing Kelas ID']);
    exit;
}

$id_kelas_raw = sanitize($_GET['id_kelas']);
$is_all_kelas = ($id_kelas_raw === 'all' || $id_kelas_raw === '0');
$id_kelas = $is_all_kelas ? 0 : (int)$id_kelas_raw;
if ($id_kelas <= 0 && !$is_all_kelas) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Kelas ID']);
    exit;
}

$scope = paymentClassScope();
if (!$is_all_kelas) {
    $class_scope = paymentClassScope('id_kelas');
    $class_result = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE id_kelas = $id_kelas AND $class_scope");
    if (mysqli_num_rows($class_result) !== 1) {
        jsonResponse(['status' => 'error', 'message' => 'Kelas ini tidak ditugaskan kepada Anda.'], 403);
    }
}
$items = [];
$hasPeriodeTipe = appColumnExists('iuran', 'periode_tipe');
$periodeExpr = $hasPeriodeTipe ? "IFNULL(b.periode_tipe, 'bulanan')" : "'bulanan'";
$mode = isset($_GET['mode']) ? strtolower(sanitize($_GET['mode'])) : 'tahunan';
if ($mode !== 'bulanan' && $mode !== 'tahunan') {
    $mode = 'tahunan';
}

if (appTableExists('santri_iuran')) {
    $where_kelas = $is_all_kelas ? "" : "s.id_kelas = '$id_kelas' AND ";
    $query = "SELECT DISTINCT b.id_iuran, b.nama_iuran, b.tahun, b.nominal, $periodeExpr as periode_tipe
              FROM santri_iuran sb
              JOIN iuran b ON sb.id_iuran = b.id_iuran
              JOIN santri s ON sb.id_santri = s.id_santri
              WHERE $where_kelas s.status = 'active' AND $scope AND sb.is_active = 1
              ORDER BY b.nama_iuran ASC, b.tahun DESC";
} else {
    // Fallback legacy schema
    $where_kelas = $is_all_kelas ? "" : "s.id_kelas = '$id_kelas' AND ";
    $query = "SELECT DISTINCT b.id_iuran, b.nama_iuran, b.tahun, b.nominal,
                     $periodeExpr as periode_tipe
              FROM santri s
              JOIN iuran b ON s.id_iuran = b.id_iuran
              WHERE $where_kelas s.status = 'active' AND $scope";
}

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $periode_tipe = $row['periode_tipe'] === 'tahunan' ? 'tahunan' : 'bulanan';
    if ($periode_tipe !== $mode) {
        continue;
    }

    if ($periode_tipe === 'tahunan') {
        $tahun = date('Y');
        $bulan = '';
    } else {
        $tahun = date('Y');
        $bulan = bulanIndo((int)date('n'));
    }

    $periode_key = buildPeriodeKey($periode_tipe, $tahun, $bulan);
    $id_iuran = (int)$row['id_iuran'];
    $nominal = (float)$row['nominal'];

    $items[] = [
        'id_iuran' => $id_iuran,
        'nama_iuran' => $row['nama_iuran'],
        'tahun_iuran' => (int)$row['tahun'],
        'nominal' => (int)$nominal,
        'periode_tipe' => $periode_tipe,
        'default_bulan' => $bulan,
        'default_tahun' => (string)$tahun,
        'default_periode_key' => $periode_key
    ];
}

if (empty($items)) {
    $target_text = $is_all_kelas ? 'semua kelas yang diizinkan' : 'kelas ini';
    $msg = $mode === 'bulanan' ? 'Tidak ada tagihan bulanan aktif untuk ' . $target_text : 'Tidak ada tagihan tahunan aktif untuk ' . $target_text;
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'data' => $items
]);
?>
