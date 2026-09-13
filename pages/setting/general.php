<?php
/**
 * Pengaturan Umum
 * Sistem Keuangan TPQ
 */

$page_title = 'Pengaturan Umum';
require_once '../../includes/header.php';

// Check admin level
if ($_SESSION['level'] != 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated = 0;

    // Update each setting
    if (isset($_POST['nama_sekolah'])) {
        updateSetting('nama_sekolah', sanitize($_POST['nama_sekolah']));
        $updated++;
    }
    if (isset($_POST['alamat_sekolah'])) {
        updateSetting('alamat_sekolah', sanitize($_POST['alamat_sekolah']));
        $updated++;
    }
    if (isset($_POST['telp_sekolah'])) {
        updateSetting('telp_sekolah', sanitize($_POST['telp_sekolah']));
        $updated++;
    }
    if (isset($_POST['email_sekolah'])) {
        updateSetting('email_sekolah', sanitize($_POST['email_sekolah']));
        $updated++;
    }
    if (isset($_POST['website_sekolah'])) {
        updateSetting('website_sekolah', sanitize($_POST['website_sekolah']));
        $updated++;
    }
    // Dipakai pada kop surat & blok tanda tangan laporan/PDF
    if (isset($_POST['kota_sekolah'])) {
        updateSetting('kota_sekolah', sanitize($_POST['kota_sekolah']));
        $updated++;
    }
    if (isset($_POST['kepala_tpq'])) {
        updateSetting('kepala_tpq', sanitize($_POST['kepala_tpq']));
        $updated++;
    }

    if ($updated > 0) {
        logActivity('Mengubah pengaturan umum', 'settings', 'general');
        setFlash('success', 'Pengaturan berhasil disimpan!');
    }

    header("Location: general.php");
    exit;
}

// Get current settings
$settings = getSettingsByGroup('general');
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-cogs"></i></div>
                        Pengaturan Umum
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
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-school mr-2"></i>Informasi Sekolah
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="form-group">
                                            <label>Nama Sekolah <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_sekolah" class="form-control" value="<?php echo htmlspecialchars($settings['nama_sekolah']['setting_value'] ?? ''); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Sekolah</label>
                                            <textarea name="alamat_sekolah" class="form-control" rows="2"><?php echo htmlspecialchars($settings['alamat_sekolah']['setting_value'] ?? ''); ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No. Telepon</label>
                                                    <input type="text" name="telp_sekolah" class="form-control" value="<?php echo htmlspecialchars($settings['telp_sekolah']['setting_value'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email_sekolah" class="form-control" value="<?php echo htmlspecialchars($settings['email_sekolah']['setting_value'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Website</label>
                                            <input type="url" name="website_sekolah" class="form-control" value="<?php echo htmlspecialchars($settings['website_sekolah']['setting_value'] ?? ''); ?>" placeholder="https://">
                                        </div>

                                        <hr>
                                        <h6 class="font-weight-bold text-primary mb-3">
                                            <i class="fas fa-file-signature mr-2"></i>Kop Surat &amp; Tanda Tangan
                                        </h6>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Kota (untuk tanggal surat)</label>
                                                    <input type="text" name="kota_sekolah" class="form-control" value="<?php echo htmlspecialchars($settings['kota_sekolah']['setting_value'] ?? 'Parung'); ?>" placeholder="Parung">
                                                    <small class="form-text text-muted">Contoh hasil: <em>Parung, <?php echo tanggalIndo(date('Y-m-d')); ?></em></small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Kepala TPQ</label>
                                                    <input type="text" name="kepala_tpq" class="form-control" value="<?php echo htmlspecialchars($settings['kepala_tpq']['setting_value'] ?? ''); ?>" placeholder="Nama penanda tangan laporan">
                                                    <small class="form-text text-muted">Dipakai sebagai penanda tangan pada laporan PDF. Kosongkan untuk memakai nama petugas yang login.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info py-2">
                                            <small>
                                                <i class="fas fa-info-circle mr-1"></i>
                                                Logo pada kop surat diambil dari berkas
                                                <code>assets/img/logo.jpg</code>. Ganti berkas tersebut untuk mengubah logo.
                                            </small>
                                        </div>

                                        <hr>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Pengaturan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Quick Links -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-link mr-2"></i>Menu Pengaturan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <a href="general.php" class="btn btn-primary btn-block mb-2">
                                        <i class="fas fa-school"></i> Umum
                                    </a>
                                    <a href="backup.php" class="btn btn-outline-info btn-block">
                                        <i class="fas fa-database"></i> Backup Database
                                    </a>
                                </div>
                            </div>

                            <!-- System Info -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-info-circle mr-2"></i>Info Sistem
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Versi:</strong> <?php echo APP_VERSION; ?></p>
                                    <p class="mb-1"><strong>PHP:</strong> <?php echo phpversion(); ?></p>
                                    <p class="mb-0"><strong>MySQL:</strong> <?php echo mysqli_get_server_info($conn); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

<?php require_once '../../includes/footer.php'; ?>
