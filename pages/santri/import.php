<?php
/**
 * Import Data Santri
 * Sistem Pembayaran SPP Sekolah
 */

$page_title = 'Import Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$import_result = null;

// Handle import
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($ext != 'csv') {
        $import_result = ['type' => 'danger', 'message' => 'Format file tidak valid! Hanya file CSV yang diperbolehkan.'];
    } else if ($file['error'] != 0) {
        $import_result = ['type' => 'danger', 'message' => 'Gagal upload file!'];
    } else {
        $handle = fopen($file['tmp_name'], 'r');

        // Skip header row
        $header = fgetcsv($handle, 0, ',');

        $success_count = 0;
        $error_count = 0;
        $errors = [];

        $row_num = 1;
        while (($data = fgetcsv($handle, 0, ',')) !== FALSE) {
            $row_num++;

            // Minimum required fields: Nama, ID Kelas, ID SPP
            if (count($data) < 6) { 
                $errors[] = "Baris $row_num: Data tidak lengkap. Minimal 6 kolom wajib diisi.";
                $error_count++;
                continue;
            }

            $nama = sanitize($data[0]);
            $tempat_lahir = sanitize($data[1]);
            $tanggal_lahir = sanitize($data[2]);
            $id_kelas = sanitize($data[3]);
            $id_iuran = sanitize($data[4]);
            $alamat = isset($data[5]) ? sanitize($data[5]) : '';

            // Validation
            if (empty($nama) || empty($id_kelas) || empty($id_iuran)) {
                $errors[] = "Baris $row_num: Data wajib tidak lengkap (Nama, ID Kelas, ID Iuran)";
                $error_count++;
                continue;
            }

            // Check if class exists
            $check_kelas = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE id_kelas = '$id_kelas'");
            if (mysqli_num_rows($check_kelas) == 0) {
                $errors[] = "Baris $row_num: ID Kelas tidak ditemukan ($id_kelas)";
                $error_count++;
                continue;
            }

            // Check if Iuran exists
            $check_iuran = mysqli_query($conn, "SELECT id_iuran FROM iuran WHERE id_iuran = '$id_iuran'");
            if (mysqli_num_rows($check_iuran) == 0) {
                $errors[] = "Baris $row_num: ID Iuran tidak ditemukan ($id_iuran)";
                $error_count++;
                continue;
            }

            // Insert
            $query = "INSERT INTO santri (nama, tempat_lahir, tanggal_lahir, id_kelas, id_iuran, alamat, status) 
                      VALUES ('$nama', '$tempat_lahir', '$tanggal_lahir', '$id_kelas', '$id_iuran', '$alamat', 'active')";

            if (mysqli_query($conn, $query)) {
                $new_id = mysqli_insert_id($conn);
                mysqli_query($conn, "
                    INSERT INTO santri_iuran (id_santri, id_iuran, is_active)
                    VALUES ('$new_id', '$id_iuran', 1)
                    ON DUPLICATE KEY UPDATE is_active = 1
                ");
                $success_count++;
            } else {
                $errors[] = "Baris $row_num: Gagal menyimpan data - " . mysqli_error($conn);
                $error_count++;
            }
        }

        fclose($handle);

        $message = "Import selesai! Berhasil: $success_count, Gagal: $error_count";
        if (count($errors) > 0) {
            $message .= "<br><br><strong>Detail Error:</strong><ul>";
            foreach (array_slice($errors, 0, 10) as $err) {
                $message .= "<li>" . htmlspecialchars($err) . "</li>";
            }
            if (count($errors) > 10) {
                $message .= "<li>... dan " . (count($errors) - 10) . " error lainnya</li>";
            }
            $message .= "</ul>";
        }

        logActivity("Import data santri (Berhasil: $success_count, Gagal: $error_count)", 'santri', null);
        $import_result = ['type' => $error_count > 0 ? 'warning' : 'success', 'message' => $message];
    }
}

// Get kelas for reference
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");
$spp_list = mysqli_query($conn, "SELECT * FROM spp ORDER BY tahun DESC");
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-file-import mr-2"></i>Import Data Santri</h1>
        <a href="index.php" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if ($import_result): ?>
        <div class="alert alert-<?php echo $import_result['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $import_result['message']; ?>
            <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <!-- Upload Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-upload mr-2"></i>Upload File CSV
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Pilih File CSV <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".csv" required>
                            <small class="text-muted">Format: CSV (Comma Separated Values)</small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Import Data
                        </button>
                    </form>
                </div>
            </div>

            <!-- Format Guide -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>Format File CSV
                    </h6>
                </div>
                <div class="card-body">
                    <p>File CSV harus memiliki kolom dengan urutan sebagai berikut:</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Nama Kolom</th>
                                    <th>Wajib</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>A</td><td>Nama Lengkap</td><td><span class="badge bg-danger text-white">Ya</span></td><td>Nama lengkap santri</td></tr>
                                <tr><td>B</td><td>Tempat Lahir</td><td><span class="badge bg-secondary text-white">Tidak</span></td><td>Kota kelahiran</td></tr>
                                <tr><td>C</td><td>Tanggal Lahir</td><td><span class="badge bg-secondary text-white">Tidak</span></td><td>Format: YYYY-MM-DD</td></tr>
                                <tr><td>D</td><td>ID Kelas</td><td><span class="badge bg-danger text-white">Ya</span></td><td>Gunakan ID dari tabel referensi</td></tr>
                                <tr><td>E</td><td>ID Iuran</td><td><span class="badge bg-danger text-white">Ya</span></td><td>Gunakan ID dari tabel referensi</td></tr>
                                <tr><td>F</td><td>Alamat</td><td><span class="badge bg-secondary text-white">Tidak</span></td><td>Alamat domisili</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Download Template -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-download mr-2"></i>Download Template
                    </h6>
                </div>
                <div class="card-body">
                    <p>Gunakan template ini agar struktur kolom sesuai.</p>
                    <a href="template.php" class="btn btn-success btn-block">
                        <i class="fas fa-file-csv"></i> Download Template CSV
                    </a>
                </div>
            </div>

            <!-- Reference Data -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Referensi ID Kelas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>ID</th><th>Kelas</th></tr></thead>
                            <tbody>
                                <?php mysqli_data_seek($kelas_list, 0); while ($kelas = mysqli_fetch_assoc($kelas_list)): ?>
                                    <tr><td><?php echo $kelas['id_kelas']; ?></td><td><?php echo htmlspecialchars($kelas['nama_kelas']); ?></td></tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Referensi ID Iuran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table table-sm table-hover">
                            <thead><tr><th>ID</th><th>Nama</th><th>Nominal</th></tr></thead>
                            <tbody>
                                <?php $iuran_list = mysqli_query($conn, "SELECT * FROM iuran ORDER BY nama_iuran ASC, tahun DESC"); 
                                while ($iuran = mysqli_fetch_assoc($iuran_list)): ?>
                                    <tr><td><?php echo $iuran['id_iuran']; ?></td><td><?php echo htmlspecialchars($iuran['nama_iuran']); ?></td><td><?php echo formatRupiah($iuran['nominal']); ?></td></tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require_once '../../includes/footer.php'; ?>


