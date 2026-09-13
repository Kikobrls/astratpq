<?php
/**
 * One-time repair script for data corrupted by the old sanitize()
 * bug, which ran htmlspecialchars() on every save. Every time a
 * record was edited, its text fields got HTML-encoded again on
 * top of the previous encoding — e.g. a phone number containing
 * '&' turned into '&amp;', then '&amp;amp;', then '&amp;amp;amp;'
 * after a few edits (this is exactly what happened to at least one
 * `users.no_telp` value in the current data).
 *
 * This script decodes each affected text column back to its
 * original form and re-saves it as PLAIN text (no HTML entities),
 * matching what sanitize() now produces after the fix in
 * config/database.php.
 *
 * USAGE:
 *   php scripts/repair_double_escaped_data.php            (dry run, shows what would change)
 *   php scripts/repair_double_escaped_data.php --apply     (actually updates the database)
 *
 * Safe to run more than once — rows that are already clean are
 * left untouched (0 rows affected on the second run).
 */

require_once __DIR__ . '/../config/database.php';

$apply = in_array('--apply', $argv, true);

// table => [primary key column, [text columns to repair]]
$targets = [
    'santri' => ['id_santri', ['nama', 'tempat_lahir', 'alamat']],
    'users' => ['id_user', ['nama', 'email', 'no_telp']],
    'kelas' => ['id_kelas', ['nama_kelas']],
    'iuran' => ['id_iuran', ['nama_iuran', 'keterangan']],
    'pos_keuangan' => ['id_pos', ['nama_pos']],
    'jurnal_umum' => ['id_jurnal', ['uraian']],
    'pembayaran' => ['id_pembayaran', ['keterangan', 'bulan_dibayar']],
];

$total_changed = 0;

foreach ($targets as $table => [$pk, $columns]) {
    if (!appTableExists($table)) {
        continue;
    }

    $cols_sql = implode(', ', array_map(fn($c) => "`$c`", $columns));
    $result = mysqli_query($conn, "SELECT `$pk`, $cols_sql FROM `$table`");
    if (!$result) {
        fwrite(STDERR, "Gagal membaca tabel $table: " . mysqli_error($conn) . "\n");
        continue;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $updates = [];
        foreach ($columns as $col) {
            $original = $row[$col];
            if ($original === null) {
                continue;
            }
            $repaired = repairDoubleEscapedValue($original);
            if ($repaired !== $original) {
                $updates[$col] = $repaired;
            }
        }

        if (empty($updates)) {
            continue;
        }

        $total_changed++;
        echo "[$table#{$row[$pk]}] ";
        foreach ($updates as $col => $val) {
            echo "$col: " . json_encode($row[$col]) . " -> " . json_encode($val) . "  ";
        }
        echo "\n";

        if ($apply) {
            $set_parts = [];
            foreach ($updates as $col => $val) {
                $safe = mysqli_real_escape_string($conn, $val);
                $set_parts[] = "`$col` = '$safe'";
            }
            $id = (int) $row[$pk];
            $sql = "UPDATE `$table` SET " . implode(', ', $set_parts) . " WHERE `$pk` = $id";
            if (!mysqli_query($conn, $sql)) {
                fwrite(STDERR, "  Gagal update $table#$id: " . mysqli_error($conn) . "\n");
            }
        }
    }
}

echo "\n";
echo $apply
    ? "Selesai. $total_changed baris diperbaiki.\n"
    : "$total_changed baris TERDETEKSI rusak (dry run, belum diubah). Jalankan ulang dengan --apply untuk menerapkan perbaikan.\n";
