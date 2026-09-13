<?php
ob_start();
/**
 * Application Configuration
 * Sistem Administrasi Keuangan Sekolah
 */

// Application settings
define('APP_NAME', 'Administrasi Keuangan');
define('APP_VERSION', '1.0.0');

// Time zone
date_default_timezone_set('Asia/Jakarta');

// Session configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// Error reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Paths
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Calculate dynamic subfolder and APP_URL
$doc_root_check = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? ''), '/');
$base_path_check = rtrim(str_replace('\\', '/', BASE_PATH), '/');
$app_subfolder = '';
if (!empty($doc_root_check) && strpos($base_path_check, $doc_root_check) === 0) {
    $app_subfolder = substr($base_path_check, strlen($doc_root_check));
}
$app_subfolder = '/' . trim($app_subfolder, '/');
if ($app_subfolder === '/') {
    $app_subfolder = '';
}
$protocol_check = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$host_check = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('APP_URL', $protocol_check . '://' . $host_check . $app_subfolder);

// Role based access control (RBAC)
if (isset($_SESSION['login']) && isset($_SESSION['level'])) {
    $current_script = $_SERVER['SCRIPT_NAME'] ?? '';
    
    // Check access for level bendahara (nilai role, bukan nama tabel)
    if ($_SESSION['level'] === 'bendahara') {
        $is_restricted = false;
        $restricted_paths = [
            '/pages/santri/',
            '/pindahkelas.php',
            '/pages/kelas/',
            '/pages/iuran/',
            '/pages/users/',
            '/pages/setting/'
        ];
        
        foreach ($restricted_paths as $path) {
            if (strpos($current_script, $path) !== false) {
                $is_restricted = true;
                break;
            }
        }
        
        // Exception for profile page
        if (strpos($current_script, '/pages/users/profile.php') !== false) {
            $is_restricted = false;
        }
        
        if ($is_restricted) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                http_response_code(403);
                echo json_encode(['error' => 'Anda tidak memiliki akses ke halaman ini.']);
                exit;
            }
            
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Anda tidak memiliki akses ke halaman master data!'
            ];
            header("Location: " . APP_URL . "/index.php");
            exit;
        }
    }
}

// Months array (Indonesian)
$bulan_indo = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

// Alert types
$alert_types = ['success', 'danger', 'warning', 'info'];

/**
 * Convert date to Indonesian format
 */
function tanggalIndo($date)
{
    global $bulan_indo;
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $bulan_indo[date('n', $timestamp) - 1];
    $year = date('Y', $timestamp);
    return "$day $month $year";
}

/**
 * Get Indonesian month name
 */
function bulanIndo($month_number)
{
    global $bulan_indo;
    return $bulan_indo[$month_number - 1];
}

/**
 * Flash message
 */
function setFlash($type, $message)
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Redirect with flash
 */
function redirect($url, $type = null, $message = null)
{
    if ($type && $message) {
        setFlash($type, $message);
    }
    header("Location: $url");
    exit;
}

/**
 * Check if request is AJAX
 */
function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * JSON response
 */
function jsonResponse($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get base URL
 */
function baseUrl($path = '')
{
    return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Get assets URL
 */
function assetUrl($path = '')
{
    return baseUrl('assets/' . ltrim($path, '/'));
}

/**
 * Convert month name to month number string
 */
function bulanKeAngka($bulan)
{
    global $bulan_indo;
    $index = array_search($bulan, $bulan_indo, true);
    if ($index === false) {
        return null;
    }
    return str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT);
}

/**
 * Build normalized period key
 */
function buildPeriodeKey($periode_tipe, $tahun, $bulan = '')
{
    $tahun = preg_replace('/\D/', '', (string)$tahun);
    if ($periode_tipe === 'tahunan') {
        return $tahun;
    }

    if (is_numeric($bulan)) {
        $bulan_num = str_pad((string)((int)$bulan), 2, '0', STR_PAD_LEFT);
    } else {
        $bulan_num = bulanKeAngka($bulan);
    }

    if (empty($bulan_num) || empty($tahun)) {
        return '';
    }

    return $tahun . '-' . $bulan_num;
}

/**
 * Build readable period label
 */
function formatPeriodeTagihan($periode_tipe, $bulan, $tahun)
{
    if ($periode_tipe === 'tahunan') {
        return (string)$tahun;
    }
    return trim((string)$bulan . ' ' . (string)$tahun);
}

/**
 * Check table existence in current database.
 * Cached per-request in a static array — the schema doesn't change
 * mid-request, so there's no reason to re-query information_schema
 * every time this is called (it used to run on every page load of
 * the santri listing, and on every AJAX call to get_kelas_iuran.php
 * / get_santri_iuran.php).
 */
function appTableExists($table_name)
{
    static $cache = [];
    if (array_key_exists($table_name, $cache)) {
        return $cache[$table_name];
    }

    global $conn;
    $table_name_safe = mysqli_real_escape_string($conn, $table_name);
    $db_res = mysqli_query($conn, "SELECT DATABASE() as db");
    $db = mysqli_fetch_assoc($db_res)['db'] ?? '';
    $db = mysqli_real_escape_string($conn, $db);

    $sql = "SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table_name_safe' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    return $cache[$table_name] = ($res && mysqli_num_rows($res) > 0);
}

/**
 * Check column existence in current database.
 * Cached per-request in a static array — see appTableExists() above.
 */
function appColumnExists($table_name, $column_name)
{
    static $cache = [];
    $cache_key = $table_name . '.' . $column_name;
    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }

    global $conn;
    $table_name_safe = mysqli_real_escape_string($conn, $table_name);
    $column_name_safe = mysqli_real_escape_string($conn, $column_name);
    $db_res = mysqli_query($conn, "SELECT DATABASE() as db");
    $db = mysqli_fetch_assoc($db_res)['db'] ?? '';
    $db = mysqli_real_escape_string($conn, $db);

    $sql = "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table_name_safe' AND COLUMN_NAME = '$column_name_safe' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    return $cache[$cache_key] = ($res && mysqli_num_rows($res) > 0);
}

/**
 * Calculate monthly payment progress for many santri.
 * Month is considered paid when all active monthly fees are fully paid in that month.
 */
function getSantriMonthlyProgressMap($santri_ids, $year = null)
{
    global $conn;

    $year = $year ?: (int)date('Y');
    $santri_ids = array_values(array_unique(array_filter(array_map('intval', (array)$santri_ids))));
    if (empty($santri_ids)) {
        return [];
    }

    $result = [];
    foreach ($santri_ids as $id_santri) {
        $result[$id_santri] = [
            'status_key' => 'no_tagihan',
            'status_text' => 'Tidak ada tagihan bulanan aktif',
            'badge' => 'secondary',
            'lunas_sampai' => null,
            'bulan_lunas_tahun_berjalan' => 0,
            'bulan_lunas_berurutan' => 0,
        ];
    }

    $id_list = implode(',', $santri_ids);
    $has_period_type = appColumnExists('iuran', 'periode_tipe');
    $periode_filter = $has_period_type ? "IFNULL(b.periode_tipe, 'bulanan') = 'bulanan'" : "1=1";

    $fee_rows = null;
    if (appTableExists('santri_iuran')) {
        $fee_rows = mysqli_query($conn, "
            SELECT sb.id_santri, b.id_iuran, b.nominal
            FROM santri_iuran sb
            JOIN iuran b ON sb.id_iuran = b.id_iuran
            WHERE sb.is_active = 1 AND $periode_filter AND sb.id_santri IN ($id_list)
        ");
    } else {
        $fee_rows = mysqli_query($conn, "
            SELECT s.id_santri, b.id_iuran, b.nominal
            FROM santri s
            JOIN iuran b ON s.id_iuran = b.id_iuran
            WHERE $periode_filter AND s.id_santri IN ($id_list)
        ");
    }

    $fee_nominal = [];
    while ($fee_rows && ($row = mysqli_fetch_assoc($fee_rows))) {
        $id_santri = (int)$row['id_santri'];
        $id_iuran = (int)$row['id_iuran'];
        $fee_nominal[$id_santri][$id_iuran] = (float)$row['nominal'];
    }

    $sum_rows = mysqli_query($conn, "
        SELECT id_santri, periode_key, id_iuran, COALESCE(SUM(jumlah_bayar), 0) as total
        FROM pembayaran
        WHERE id_santri IN ($id_list)
          AND periode_tipe = 'bulanan'
          AND periode_key LIKE '" . (int)$year . "-%'
        GROUP BY id_santri, periode_key, id_iuran
    ");

    $paid_sum = [];
    while ($sum_rows && ($row = mysqli_fetch_assoc($sum_rows))) {
        $id_santri = (int)$row['id_santri'];
        $periode_key = $row['periode_key'];
        $id_iuran = (int)$row['id_iuran'];
        $paid_sum[$id_santri][$periode_key][$id_iuran] = (float)$row['total'];
    }

    foreach ($santri_ids as $id_santri) {
        $fees = $fee_nominal[$id_santri] ?? [];
        if (empty($fees)) {
            continue;
        }

        $complete_months = [];
        for ($m = 1; $m <= 12; $m++) {
            $periode_key = $year . '-' . str_pad((string)$m, 2, '0', STR_PAD_LEFT);
            $is_complete = true;
            foreach ($fees as $id_iuran => $nominal) {
                $total = $paid_sum[$id_santri][$periode_key][$id_iuran] ?? 0;
                if ($total < $nominal) {
                    $is_complete = false;
                    break;
                }
            }
            if ($is_complete) {
                $complete_months[$m] = true;
            }
        }

        $berurutan = 0;
        for ($m = 12; $m >= 1; $m--) {
            if (!empty($complete_months[$m])) {
                $berurutan = $m;
                break;
            }
        }

        $jumlah_lunas = count($complete_months);
        if ($jumlah_lunas === 0) {
            $has_any_payment = !empty($paid_sum[$id_santri]);
            $result[$id_santri] = [
                'status_key' => $has_any_payment ? 'belum_lunas' : 'belum_bayar',
                'status_text' => $has_any_payment ? 'Belum lunas' : 'Belum ada pembayaran bulanan',
                'badge' => $has_any_payment ? 'warning' : 'secondary',
                'lunas_sampai' => null,
                'bulan_lunas_tahun_berjalan' => 0,
                'bulan_lunas_berurutan' => 0,
            ];
            continue;
        }

        if ($berurutan > 0) {
            $result[$id_santri] = [
                'status_key' => 'lunas_berurutan',
                'status_text' => 'Lunas s/d ' . bulanIndo($berurutan) . ' ' . $year,
                'badge' => 'success',
                'lunas_sampai' => $year . '-' . str_pad((string)$berurutan, 2, '0', STR_PAD_LEFT),
                'bulan_lunas_tahun_berjalan' => $jumlah_lunas,
                'bulan_lunas_berurutan' => $berurutan,
            ];
        } else {
            $result[$id_santri] = [
                'status_key' => 'belum_lunas',
                'status_text' => 'Belum lunas',
                'badge' => 'warning',
                'lunas_sampai' => null,
                'bulan_lunas_tahun_berjalan' => $jumlah_lunas,
                'bulan_lunas_berurutan' => 0,
            ];
        }
    }

    return $result;
}

/**
 * Calculate monthly payment progress for one santri.
 */
function getSantriMonthlyProgress($id_santri, $year = null)
{
    $map = getSantriMonthlyProgressMap([(int)$id_santri], $year);
    return $map[(int)$id_santri] ?? [
        'status_key' => 'no_tagihan',
        'status_text' => 'Tidak ada tagihan bulanan aktif',
        'badge' => 'secondary',
        'lunas_sampai' => null,
        'bulan_lunas_tahun_berjalan' => 0,
        'bulan_lunas_berurutan' => 0,
    ];
}

/**
 * Calculate yearly payment progress for many santri.
 * Year is considered paid when all active yearly fees are fully paid in that year.
 */
function getSantriYearlyProgressMap($santri_ids, $year = null)
{
    global $conn;

    $year = $year ?: (int)date('Y');
    $santri_ids = array_values(array_unique(array_filter(array_map('intval', (array)$santri_ids))));
    if (empty($santri_ids)) {
        return [];
    }

    $result = [];
    foreach ($santri_ids as $id_santri) {
        $result[$id_santri] = [
            'status_key' => 'no_tagihan',
            'status_text' => 'Tidak ada tagihan tahunan aktif',
            'badge' => 'secondary',
            'tahun' => $year,
        ];
    }

    $id_list = implode(',', $santri_ids);
    $has_period_type = appColumnExists('iuran', 'periode_tipe');
    $periode_filter = $has_period_type ? "IFNULL(b.periode_tipe, 'bulanan') = 'tahunan'" : "1=0";

    $fee_rows = null;
    if (appTableExists('santri_iuran')) {
        $fee_rows = mysqli_query($conn, "
            SELECT sb.id_santri, b.id_iuran, b.nominal
            FROM santri_iuran sb
            JOIN iuran b ON sb.id_iuran = b.id_iuran
            WHERE sb.is_active = 1 AND $periode_filter AND sb.id_santri IN ($id_list)
        ");
    } else {
        $fee_rows = mysqli_query($conn, "
            SELECT s.id_santri, b.id_iuran, b.nominal
            FROM santri s
            JOIN iuran b ON s.id_iuran = b.id_iuran
            WHERE $periode_filter AND s.id_santri IN ($id_list)
        ");
    }

    $fee_nominal = [];
    while ($fee_rows && ($row = mysqli_fetch_assoc($fee_rows))) {
        $id_santri = (int)$row['id_santri'];
        $id_iuran = (int)$row['id_iuran'];
        $fee_nominal[$id_santri][$id_iuran] = (float)$row['nominal'];
    }

    $sum_rows = mysqli_query($conn, "
        SELECT id_santri, id_iuran, COALESCE(SUM(jumlah_bayar), 0) as total
        FROM pembayaran
        WHERE id_santri IN ($id_list)
          AND periode_tipe = 'tahunan'
          AND periode_key = '" . (int)$year . "'
        GROUP BY id_santri, id_iuran
    ");

    $paid_sum = [];
    while ($sum_rows && ($row = mysqli_fetch_assoc($sum_rows))) {
        $id_santri = (int)$row['id_santri'];
        $id_iuran = (int)$row['id_iuran'];
        $paid_sum[$id_santri][$id_iuran] = (float)$row['total'];
    }

    foreach ($santri_ids as $id_santri) {
        $fees = $fee_nominal[$id_santri] ?? [];
        if (empty($fees)) {
            continue;
        }

        $all_complete = true;
        $has_any_payment = false;
        foreach ($fees as $id_iuran => $nominal) {
            $total = $paid_sum[$id_santri][$id_iuran] ?? 0;
            if ($total > 0) {
                $has_any_payment = true;
            }
            if ($total < $nominal) {
                $all_complete = false;
            }
        }

        if ($all_complete) {
            $result[$id_santri] = [
                'status_key' => 'lunas',
                'status_text' => 'Lunas tahun ' . $year,
                'badge' => 'success',
                'tahun' => $year,
            ];
        } else {
            $result[$id_santri] = [
                'status_key' => $has_any_payment ? 'belum_lunas' : 'belum_bayar',
                'status_text' => $has_any_payment ? 'Belum lunas tahun ' . $year : 'Belum ada pembayaran tahunan',
                'badge' => $has_any_payment ? 'warning' : 'secondary',
                'tahun' => $year,
            ];
        }
    }

    return $result;
}

/**
 * Calculate yearly payment progress for one santri.
 */
function getSantriYearlyProgress($id_santri, $year = null)
{
    $map = getSantriYearlyProgressMap([(int)$id_santri], $year);
    return $map[(int)$id_santri] ?? [
        'status_key' => 'no_tagihan',
        'status_text' => 'Tidak ada tagihan tahunan aktif',
        'badge' => 'secondary',
        'tahun' => $year ?: (int)date('Y'),
    ];
}
/** Return the live role, so disabled/deleted accounts cannot keep using a session. */
function paymentUserRole()
{
    global $conn;
    if (empty($_SESSION['login']) || empty($_SESSION['id_user'])) {
        return '';
    }
    $id = (int) $_SESSION['id_user'];
    $result = mysqli_query($conn, "SELECT level FROM users WHERE id_user = $id AND status = 'active'");
    $user = mysqli_fetch_assoc($result);
    return $user['level'] ?? '';
}

function requirePaymentAccess($json = false)
{
    $role = paymentUserRole();
    if (!in_array($role, ['admin', 'bendahara'], true)) {
        $status = empty($_SESSION['login']) ? 401 : 403;
        if ($json) {
            jsonResponse(['status' => 'error', 'message' => 'Anda tidak memiliki akses pembayaran.'], $status);
        }
        http_response_code($status);
        exit('Anda tidak memiliki akses pembayaran. Silakan login dengan akun admin atau bendahara aktif.');
    }
    $_SESSION['level'] = $role;
}

/** SQL column must be a trusted application identifier, never request input. */
function paymentClassScope($column = 's.id_kelas')
{
    if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_.]*$/', $column)) {
        throw new InvalidArgumentException('Invalid class column');
    }
    $role = paymentUserRole();
    if ($role === 'admin') {
        return '1=1';
    }
    // Fail closed before migration and when no classes have been assigned.
    if ($role !== 'bendahara' || !appTableExists('bendahara_kelas')) {
        return '1=0';
    }
    $id = (int) $_SESSION['id_user'];
    return "$column IN (SELECT bk.id_kelas FROM bendahara_kelas bk WHERE bk.id_user = $id)";
}

function paymentSantriAllowed($id_santri, $activeOnly = true)
{
    global $conn;
    $id = (int) $id_santri;
    $scope = paymentClassScope();
    $active = $activeOnly ? " AND s.status = 'active'" : '';
    $result = mysqli_query($conn, "SELECT s.id_santri FROM santri s WHERE s.id_santri = $id AND $scope $active");
    return mysqli_num_rows($result) === 1;
}

/** Use the same active fee assignment for individual and collective payments. */
function paymentSantriFees($id_santri)
{
    global $conn;
    $id = (int) $id_santri;
    if (!paymentSantriAllowed($id)) {
        return [];
    }
    $period = appColumnExists('iuran', 'periode_tipe') ? "IFNULL(b.periode_tipe, 'bulanan')" : "'bulanan'";
    if (appTableExists('santri_iuran')) {
        $sql = "SELECT b.*, $period AS periode_tipe FROM santri_iuran sb
                JOIN iuran b ON b.id_iuran = sb.id_iuran
                WHERE sb.id_santri = $id AND sb.is_active = 1";
    } else {
        $sql = "SELECT b.*, $period AS periode_tipe FROM santri s
                JOIN iuran b ON b.id_iuran = s.id_iuran WHERE s.id_santri = $id";
    }
    $fees = [];
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $fees[(int) $row['id_iuran']] = $row;
    }
    return $fees;
}

function paymentCsrfToken()
{
    if (empty($_SESSION['payment_csrf'])) {
        $_SESSION['payment_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['payment_csrf'];
}

function paymentCsrfValid()
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && isset($_SESSION['payment_csrf'])
        && hash_equals($_SESSION['payment_csrf'], $token);
}

function paymentScopeNotice()
{
    if (($_SESSION['level'] ?? '') !== 'bendahara') {
        return;
    }
    global $conn;
    $scope = paymentClassScope('id_kelas');
    $result = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE $scope ORDER BY nama_kelas");
    $names = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $names[] = $row['nama_kelas'];
    }
    $message = $names
        ? 'Kelas tugas Anda: ' . implode(', ', $names) . '. Pembayaran hanya untuk santri di kelas tersebut dan iuran aktif yang ditetapkan admin.'
        : 'Belum ada kelas yang ditugaskan. Hubungi admin untuk mengatur kelas Anda melalui Data Pengguna.';
    echo '<div class="alert alert-info" role="status">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
}
?>
