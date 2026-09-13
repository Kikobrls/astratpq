<?php
/**
 * Tambah Santri
 * Sistem Keuangan TPQ
 */

// Start session and require config FIRST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Handle form submission - BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $tempat_lahir = sanitize($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir = sanitize($_POST['tanggal_lahir'] ?? '');
    $id_kelas = (int) ($_POST['id_kelas'] ?? 0);
    $alamat = sanitize($_POST['alamat'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');
    $iuran_ids = array_map('intval', (array) ($_POST['iuran_ids'] ?? []));

    if ($status !== 'active' && $status !== 'nonaktif') {
        $status = 'active';
    }

    if (empty($nama)) {
        setFlash('danger', 'Nama santri harus diisi!');
    } elseif ($id_kelas <= 0) {
        setFlash('danger', 'Kelas harus dipilih!');
    } else {
        $tanggal_lahir_sql = !empty($tanggal_lahir) ? "'$tanggal_lahir'" : "NULL";
        $legacy_iuran_sql = !empty($iuran_ids) ? "'" . (int) $iuran_ids[0] . "'" : "NULL";

        mysqli_begin_transaction($conn);
        try {
            $query = "INSERT INTO santri (nama, tempat_lahir, tanggal_lahir, id_kelas, alamat, id_iuran, status)
                      VALUES ('$nama', '$tempat_lahir', $tanggal_lahir_sql, '$id_kelas', '$alamat', $legacy_iuran_sql, '$status')";
            mysqli_query($conn, $query);
            $id_santri = mysqli_insert_id($conn);

            // Simpan relasi santri <-> iuran (multi iuran)
            if (!empty($iuran_ids) && appTableExists('santri_iuran')) {
                foreach ($iuran_ids as $id_iuran) {
                    if ($id_iuran > 0) {
                        mysqli_query($conn, "INSERT INTO santri_iuran (id_santri, id_iuran, is_active) VALUES ('$id_santri', '$id_iuran', 1)");
                    }
                }
            }

            mysqli_commit($conn);
            logActivity('Menambah data santri', 'santri', $id_santri);
            setFlash('success', 'Data santri berhasil ditambahkan!');
            header("Location: index.php");
            exit;
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            setFlash('danger', 'Gagal menyimpan data santri!');
        }
    }
    header("Location: add.php");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Tambah Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$iuran_list = mysqli_query($conn, "SELECT * FROM iuran ORDER BY periode_tipe ASC, nama_iuran ASC, tahun DESC");
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-plus"></i></div>
                        Tambah Santri
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="index.php">Data Santri</a></li>
                            <li class="breadcrumb-item active">Tambah Santri</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Santri</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group mb-3">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control"
                                value="<?php echo htmlspecialchars($_POST['nama'] ?? ''); ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control"
                                        value="<?php echo htmlspecialchars($_POST['tempat_lahir'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="<?php echo htmlspecialchars($_POST['tanggal_lahir'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Kelas <span class="text-danger">*</span></label>
                                    <select name="id_kelas" class="form-control" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php while ($k = mysqli_fetch_assoc($kelas_list)): ?>
                                            <option value="<?php echo (int) $k['id_kelas']; ?>"
                                                <?php echo (($_POST['id_kelas'] ?? '') == $k['id_kelas']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($k['nama_kelas']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="active" <?php echo (($_POST['status'] ?? 'active') === 'active') ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="nonaktif" <?php echo (($_POST['status'] ?? '') === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?php echo htmlspecialchars($_POST['alamat'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label>Iuran yang Dibebankan</label>
                            <div class="border rounded p-3">
                                <?php if (mysqli_num_rows($iuran_list) > 0): ?>
                                    <?php while ($b = mysqli_fetch_assoc($iuran_list)): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="iuran_ids[]"
                                                id="iuran_<?php echo (int) $b['id_iuran']; ?>"
                                                value="<?php echo (int) $b['id_iuran']; ?>"
                                                <?php echo in_array((int) $b['id_iuran'], array_map('intval', (array) ($_POST['iuran_ids'] ?? [])), true) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="iuran_<?php echo (int) $b['id_iuran']; ?>">
                                                <?php echo htmlspecialchars($b['nama_iuran']); ?>
                                                (<?php echo htmlspecialchars($b['tahun']); ?>,
                                                <?php echo ucfirst($b['periode_tipe']); ?>,
                                                <?php echo formatRupiah($b['nominal']); ?>)
                                            </label>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada data iuran. Tambahkan dulu di menu Data Iuran.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.container-xl -->

<?php
require_once '../../includes/footer.php';
?>
