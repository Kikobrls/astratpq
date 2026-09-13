<?php
/**
 * API Get Santri Iuran
 * Returns list of active iuran for a specific santri
 */
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../config/app.php';

requirePaymentAccess(true);

if (!isset($_GET['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    exit;
}

$id_santri = (int)$_GET['id'];
if ($id_santri <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
    exit;
}

if (!paymentSantriAllowed($id_santri)) {
    jsonResponse(['status' => 'error', 'message' => 'Santri tidak aktif atau kelas tidak ditugaskan kepada Anda.'], 403);
}
$items = [];
$mode = isset($_GET['mode']) ? strtolower(sanitize($_GET['mode'])) : 'tahunan';
if ($mode !== 'bulanan' && $mode !== 'tahunan') {
    $mode = 'tahunan';
}
foreach (paymentSantriFees($id_santri) as $row) {
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

    $sumQuery = mysqli_query($conn, "
        SELECT COALESCE(SUM(jumlah_bayar), 0) as total
        FROM pembayaran
        WHERE id_santri = '$id_santri'
          AND id_iuran = '$id_iuran'
          AND periode_key = '" . mysqli_real_escape_string($conn, $periode_key) . "'
    ");
    $sum = mysqli_fetch_assoc($sumQuery);
    $sudah_bayar = (float)$sum['total'];
    $nominal = (float)$row['nominal'];

    $items[] = [
        'id_iuran' => $id_iuran,
        'nama_iuran' => $row['nama_iuran'],
        'tahun_iuran' => (int)$row['tahun'],
        'nominal' => (int)$nominal,
        'periode_tipe' => $periode_tipe,
        'default_bulan' => $bulan,
        'default_tahun' => (string)$tahun,
        'default_periode_key' => $periode_key,
        'sudah_bayar' => (int)$sudah_bayar,
        'sisa_tagihan' => (int)max($nominal - $sudah_bayar, 0)
    ];
}

if (empty($items)) {
    $msg = $mode === 'bulanan' ? 'Tidak ada tagihan bulanan aktif untuk santri ini' : 'Tidak ada tagihan tahunan aktif untuk santri ini';
    echo json_encode(['status' => 'error', 'message' => $msg]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'data' => $items
]);
?>
