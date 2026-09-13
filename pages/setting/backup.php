<?php
/**
 * Backup & Restore Database
 * Sistem Keuangan TPQ
 */

$page_title = 'Backup Database';
require_once '../../includes/header.php';

// Check admin level
if ($_SESSION['level'] != 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$backup_dir = '../../backups/';

// Ensure backup directory exists
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

// Handle backup
if (isset($_POST['backup'])) {
    $tables = [];
    $result = mysqli_query($conn, "SHOW TABLES");

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $sqlScript = "-- Database Backup\n";
    $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sqlScript .= "-- Database: " . DB_NAME . "\n\n";
    $sqlScript .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    foreach ($tables as $table) {
        // Get create table statement
        $result = mysqli_query($conn, "SHOW CREATE TABLE `$table`");
        $row = mysqli_fetch_row($result);

        $sqlScript .= "-- Table: $table\n";
        $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
        $sqlScript .= $row[1] . ";\n\n";

        // Get table data
        $result = mysqli_query($conn, "SELECT * FROM `$table`");
        $columnCount = mysqli_num_fields($result);

        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO `$table` VALUES(";
            for ($j = 0; $j < $columnCount; $j++) {
                if (isset($row[$j])) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= 'NULL';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
        $sqlScript .= "\n";
    }

    $sqlScript .= "SET FOREIGN_KEY_CHECKS = 1;\n";

    $filename = 'backup_' . date('Y-m-d_His') . '.sql';
    $filepath = $backup_dir . $filename;

    if (file_put_contents($filepath, $sqlScript)) {
        logActivity('Backup database', 'backup', $filename);
        setFlash('success', 'Backup database berhasil! File: ' . $filename);
    } else {
        setFlash('danger', 'Gagal membuat backup!');
    }

    header("Location: backup.php");
    exit;
}

// Handle download
if (isset($_GET['download'])) {
    $filename = basename(sanitize($_GET['download']));
    $filepath = $backup_dir . $filename;

    if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) == 'sql') {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $filename = basename(sanitize($_GET['delete']));
    $filepath = $backup_dir . $filename;

    if (file_exists($filepath) && pathinfo($filepath, PATHINFO_EXTENSION) == 'sql') {
        if (unlink($filepath)) {
            logActivity('Menghapus file backup', 'backup', $filename);
            setFlash('success', 'File backup berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus file backup!');
        }
    }

    header("Location: backup.php");
    exit;
}

// Handle restore
if (isset($_POST['restore'])) {
    if (isset($_FILES['backup_file']) && $_FILES['backup_file']['error'] == 0) {
        $file_ext = pathinfo($_FILES['backup_file']['name'], PATHINFO_EXTENSION);

        if ($file_ext == 'sql') {
            $sql = file_get_contents($_FILES['backup_file']['tmp_name']);

            // Execute SQL
            mysqli_multi_query($conn, $sql);

            // Wait for all queries to complete
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));

            logActivity('Restore database dari file backup', 'backup', $_FILES['backup_file']['name']);
            setFlash('success', 'Database berhasil di-restore!');
        } else {
            setFlash('danger', 'Format file tidak valid! Hanya file .sql yang diperbolehkan.');
        }
    } else {
        setFlash('danger', 'Gagal upload file backup!');
    }

    header("Location: backup.php");
    exit;
}

// Get backup files
$backup_files = [];
if (is_dir($backup_dir)) {
    $files = glob($backup_dir . '*.sql');
    foreach ($files as $file) {
        $backup_files[] = [
            'name' => basename($file),
            'size' => filesize($file),
            'date' => filemtime($file)
        ];
    }
    // Sort by date descending
    usort($backup_files, function($a, $b) {
        return $b['date'] - $a['date'];
    });
}

// Database stats
$db_size = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
    FROM information_schema.tables
    WHERE table_schema = '" . DB_NAME . "'
"));

$table_count = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) as total
    FROM information_schema.tables
    WHERE table_schema = '" . DB_NAME . "'
"));
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-database"></i></div>
                        Backup & Restore Database
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">



                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Backup -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-download mr-2"></i>Backup Database
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p>Backup database akan menyimpan semua data dalam format SQL yang dapat digunakan untuk restore.</p>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Info Database:</strong><br>
                                        Ukuran: <?php echo $db_size['size_mb']; ?> MB |
                                        Jumlah Tabel: <?php echo $table_count['total']; ?>
                                    </div>

                                    <form method="POST" action="">
                                        <button type="submit" name="backup" class="btn btn-primary">
                                            <i class="fas fa-database"></i> Backup Sekarang
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Restore -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-warning">
                                        <i class="fas fa-upload mr-2"></i>Restore Database
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Peringatan!</strong> Restore database akan menghapus semua data yang ada dan menggantinya dengan data dari file backup. Pastikan Anda sudah membuat backup sebelum melakukan restore.
                                    </div>

                                    <form method="POST" action="" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Pilih File Backup (.sql)</label>
                                            <input type="file" name="backup_file" class="form-control" accept=".sql" required>
                                        </div>
                                        <button type="submit" name="restore" class="btn btn-warning" onclick="return confirm('Apakah Anda yakin ingin me-restore database? Semua data saat ini akan dihapus!')">
                                            <i class="fas fa-undo"></i> Restore Database
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Backup Files List -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-folder mr-2"></i>Daftar File Backup
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if (count($backup_files) > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Nama File</th>
                                                    <th>Ukuran</th>
                                                    <th>Tanggal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($backup_files as $file): ?>
                                                <tr>
                                                    <td><code><?php echo htmlspecialchars($file['name']); ?></code></td>
                                                    <td><?php echo number_format($file['size'] / 1024, 2); ?> KB</td>
                                                    <td><?php echo date('d/m/Y H:i', $file['date']); ?></td>
                                                    <td>
                                                        <a href="?download=<?php echo urlencode($file['name']); ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <button onclick="confirmDelete('?delete=<?php echo urlencode($file['name']); ?>')" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <p class="text-muted text-center mb-0">Belum ada file backup</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Menu Links -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-link mr-2"></i>Menu Pengaturan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <a href="general.php" class="btn btn-outline-primary btn-block mb-2">
                                        <i class="fas fa-school"></i> Umum
                                    </a>
                                    <a href="backup.php" class="btn btn-info btn-block">
                                        <i class="fas fa-database"></i> Backup Database
                                    </a>
                                </div>
                            </div>

                            <!-- Tips -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-lightbulb mr-2"></i>Tips
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="small mb-0">
                                        <li>Lakukan backup secara rutin (minimal seminggu sekali)</li>
                                        <li>Simpan file backup di tempat yang aman</li>
                                        <li>Test restore di lingkungan testing sebelum production</li>
                                        <li>Jangan hapus backup yang masih diperlukan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<?php require_once '../../includes/footer.php'; ?>
