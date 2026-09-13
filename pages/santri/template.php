<?php
/**
 * Template CSV Santri
 * Sistem Keuangan TPQ
 */

session_start();
require_once '../../config/database.php';
require_once '../../config/app.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Set filename
$filename = "template_import_santri_" . date('Y-m-d') . ".csv";

// Set header to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create file handle
$output = fopen('php://output', 'w');

// Set CSV headers (columns)
$fields = array(
    'Nama_Lengkap',
    'Tempat_Lahir',
    'Tanggal_Lahir',
    'ID_Kelas',
    'ID_Iuran',
    'Alamat'
);
fputcsv($output, $fields);

// Add example row
fputcsv($output, array(
    'Ahmad Rizki',
    'Jakarta',
    '2015-05-20',
    '1',
    '2',
    'Jl. Mawar No 1'
));

fclose($output);
exit;
