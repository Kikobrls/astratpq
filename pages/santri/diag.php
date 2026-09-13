<?php
/**
 * TEMPORARY diagnostic - delete this file after debugging.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

echo "<pre>";
echo "Target id_santri = $id\n\n";

// 1. List tables that reference santri via foreign keys
$res = mysqli_query($conn, "
    SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME,
           CONSTRAINT_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE REFERENCED_TABLE_NAME = 'santri'
      AND TABLE_SCHEMA = DATABASE()
");
echo "=== Foreign keys pointing to `santri` ===\n";
while ($r = mysqli_fetch_assoc($res)) {
    echo "- {$r['TABLE_NAME']}.{$r['COLUMN_NAME']} -> {$r['REFERENCED_TABLE_NAME']}.{$r['REFERENCED_COLUMN_NAME']} (constraint {$r['CONSTRAINT_NAME']})\n";
}

// 2. Show row counts in each referencing table for this id
echo "\n=== Row counts for id_santri = $id ===\n";
$res2 = mysqli_query($conn, "
    SELECT TABLE_NAME, COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE REFERENCED_TABLE_NAME = 'santri'
      AND TABLE_SCHEMA = DATABASE()
");
while ($r = mysqli_fetch_assoc($res2)) {
    $t = $r['TABLE_NAME'];
    $c = $r['COLUMN_NAME'];
    $cnt = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS n FROM `$t` WHERE `$c` = '$id'"));
    echo "- $t.$c : " . $cnt['n'] . " rows\n";
}

// 3. Try the delete and capture the real error
echo "\n=== Attempt delete ===\n";
mysqli_begin_transaction($conn);
$ok = true;
$err = '';
if (appTableExists('santri_iuran')) {
    if (!mysqli_query($conn, "DELETE FROM santri_iuran WHERE id_santri = '$id'")) {
        $ok = false; $err = "santri_iuran: " . mysqli_error($conn);
    }
}
if ($ok && !mysqli_query($conn, "DELETE FROM santri WHERE id_santri = '$id'")) {
    $ok = false; $err = "santri: " . mysqli_error($conn);
}
if ($ok) {
    mysqli_commit($conn);
    echo "DELETE SUCCEEDED (rolled back for safety so data is preserved)\n";
    mysqli_begin_transaction($conn);
    // re-insert nothing; just rollback to keep data
    mysqli_rollback($conn);
} else {
    mysqli_rollback($conn);
    echo "DELETE FAILED: $err\n";
}
echo "</pre>";
