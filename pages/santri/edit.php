<?php
/**
 * Edit Santri
 * Sistem Keuangan TPQ
 */

// Start session and require config FIRST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    setFlash('danger', 'Data santri tidak valid!');
    header("Location: index.php");
    exit;
}

$santri = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM santri WHERE id_santri = '$id'"));
if (!$santri) {
    setFlash('danger', 'Data santri tidak ditemukan!');
    header("Location: index.php");
    exit;
}

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
            $query = "UPDATE santri SET
                        nama = '$nama',
                        tempat_lahir = '$tempat_lahir',
                        tanggal_lahir = $tanggal_lahir_sql,
                        id_kelas = '$id_kelas',
                        alamat = '$alamat',
                        id_iuran = $legacy_iuran_sql,
                        status = '$status'
                      WHERE id_santri = '$id'";
            mysqli_query($conn, $query);

            // Sync relasi santri <-> iuran
            if (appTableExists('santri_iuran')) {
                mysqli_query($conn, "DELETE FROM santri_iuran WHERE id_santri = '$id'");
                foreach ($iuran_ids as $id_iuran) {
                    if ($id_iuran > 0) {
                        mysqli_query($conn, "INSERT INTO santri_iuran (id_santri, id_iuran, is_active) VALUES ('$id', '$id_iuran', 1)");
                    }
                }
            }

            mysqli_commit($conn);
            logActivity('Mengubah data santri', 'santri', $id);
            setFlash('success', 'Data santri berhasil diubah!');
            header("Location: index.php");
            exit;
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            setFlash('danger', 'Gagal mengubah data santri!');
        }
    }
    header("Location: edit.php?id=$id");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Edit Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
$iuran_list = mysqli_query($conn, "SELECT * FROM iuran ORDER BY periode_tipe ASC, nama_iuran ASC, tahun DESC");

// Current iuran assignments for this santri
$current_iuran = [];
if (appTableExists('santri_iuran')) {
    $res = mysqli_query($conn, "SELECT id_iuran FROM santri_iuran WHERE id_santri = '$id' AND is_active = 1");
    while ($row = mysqli_fetch_assoc($res)) {
        $current_iuran[] = (int) $row['id_iuran'];
    }
}
// Fallback to legacy single-iuran column
if (empty($current_iuran) && !empty($santri['id_iuran'])) {
    $current_iuran[] = (int) $santri['id_iuran'];
}
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-edit"></i></div>
                        Edit Santri
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/index.php">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="index.php">Data Santri</a></li>
                            <li class="breadcrumb-item active">Edit Santri</li>
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
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Santri: <?php echo htmlspecialchars($santri['nama']); ?></h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group mb-3">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control"
                                value="<?php echo htmlspecialchars($santri['nama']); ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control"
                                        value="<?php echo htmlspecialchars($santri['tempat_lahir'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        value="<?php echo !empty($santri['tanggal_lahir']) && $santri['tanggal_lahir'] !== '0000-00-00' ? htmlspecialchars($santri['tanggal_lahir']) : ''; ?>">
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
                                                <?php echo ((int) $santri['id_kelas'] === (int) $k['id_kelas']) ? 'selected' : ''; ?>>
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
                                        <option value="active" <?php echo ($santri['status'] === 'active') ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="nonaktif" <?php echo ($santri['status'] === 'nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"><?php echo htmlspecialchars($santri['alamat'] ?? ''); ?></textarea>
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
                                                <?php echo in_array((int) $b['id_iuran'], $current_iuran, true) ? 'checked' : ''; ?>>
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
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
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
