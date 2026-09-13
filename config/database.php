<?php
/**
 * Database Configuration
 * sistem keuangan Sekolah
 */

// Database credentials
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'keuangan_tpq');

// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8mb4
mysqli_set_charset($conn, "utf8mb4");

/**
 * Get setting value from database
 */
function getSetting($key, $default = '')
{
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $query = "SELECT setting_value FROM settings WHERE setting_key = '$key'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['setting_value'];
    }
    return $default;
}

/**
 * Update setting value
 */
function updateSetting($key, $value)
{
    global $conn;
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);

    $query = "UPDATE settings SET setting_value = '$value' WHERE setting_key = '$key'";
    return mysqli_query($conn, $query);
}

/**
 * Get all settings by group
 */
function getSettingsByGroup($group)
{
    global $conn;
    $group = mysqli_real_escape_string($conn, $group);
    $query = "SELECT * FROM settings WHERE setting_group = '$group' ORDER BY id";
    $result = mysqli_query($conn, $query);

    $settings = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $settings[$row['setting_key']] = $row;
        }
    }
    return $settings;
}

/**
 * Log activity
 */
function logActivity($aktivitas, $tabel = 'NULL', $data_id = 'NULL')
{
    global $conn;

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $aktivitas = mysqli_real_escape_string($conn, $aktivitas);
    $tabel = $tabel === 'NULL' ? 'NULL' : "'" . mysqli_real_escape_string($conn, $tabel) . "'";
    $data_id = $data_id === 'NULL' ? 'NULL' : "'" . mysqli_real_escape_string($conn, $data_id) . "'";
    $id_user = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : 'NULL';

    // Convert to null string if empty
    if ($id_user === '')
        $id_user = 'NULL';

    $query = "INSERT INTO log_aktivitas (id_user, aktivitas, tabel, data_id, ip_address, user_agent)
              VALUES ($id_user, '$aktivitas', $tabel, $data_id, '$ip_address', '$user_agent')";

    mysqli_query($conn, $query);
}

/**
 * Format currency
 */
function formatRupiah($number)
{
    return 'Rp ' . number_format($number, 0, ',', '.');
}

/**
 * Sanitize input for safe use inside a SQL string.
 *
 * IMPORTANT: this only makes the value SQL-safe (trim + escape).
 * It intentionally does NOT run htmlspecialchars() here — doing so
 * used to corrupt stored data, because every edit re-encoded
 * characters like '&' again ('&' -> '&amp;' -> '&amp;amp;' -> ...).
 * HTML-escaping must happen once, at display time, using
 * htmlspecialchars() in the template — which every page in this
 * project already does. Do not add htmlspecialchars() back here.
 *
 * Note: for new code, prefer prepared statements
 * (mysqli_prepare / bound params) over string interpolation.
 */
function sanitize($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

/**
 * One-time repair for values that were corrupted by the old
 * double-escaping sanitize(). Decodes HTML entities repeatedly
 * until the value stops changing (undoes N layers of encoding),
 * then re-encodes exactly once back to raw form for storage.
 * Safe to run multiple times.
 */
function repairDoubleEscapedValue($value)
{
    if ($value === null || $value === '') {
        return $value;
    }
    $prev = $value;
    for ($i = 0; $i < 10; $i++) {
        $decoded = html_entity_decode($prev, ENT_QUOTES, 'UTF-8');
        if ($decoded === $prev) {
            break;
        }
        $prev = $decoded;
    }
    return $prev;
}

?>
