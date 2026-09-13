<?php
/**
 * Pindah Kelas Santri (Bulk Class Update)
 * Sistem Keuangan TPQ
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/app.php';
require_once '../../config/database.php';

$pindah_kelas_url = baseUrl('pages/santri/pindahkelas.php');

// Handle Class update submit
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pindahkan_kelas'])) {
    $kelas_asal = isset($_POST['kelas_asal']) ? (int) sanitize($_POST['kelas_asal']) : 0;
    $kelas_tujuan = isset($_POST['kelas_tujuan']) ? (int) sanitize($_POST['kelas_tujuan']) : 0;
    $selected_santri = isset($_POST['santri_ids']) ? $_POST['santri_ids'] : [];

    if ($kelas_tujuan <= 0) {
        $error_msg = 'Silakan pilih kelas tujuan!';
    } elseif (empty($selected_santri)) {
        $error_msg = 'Silakan pilih minimal satu santri yang ingin dipindahkan!';
    } elseif ($kelas_asal === $kelas_tujuan) {
        $error_msg = 'Kelas tujuan tidak boleh sama dengan kelas asal!';
    } else {
        // Convert to array of ints
        $santri_ids_clean = array_map('intval', $selected_santri);
        $ids_string = implode(',', $santri_ids_clean);

        // Fetch details for logging
        $kelas_tujuan_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id_kelas = $kelas_tujuan"));
        $nama_kelas_tujuan = $kelas_tujuan_res['nama_kelas'] ?? 'Tidak Diketahui';

        $query = "UPDATE santri SET id_kelas = $kelas_tujuan WHERE id_santri IN ($ids_string)";
        if (mysqli_query($conn, $query)) {
            $total_moved = mysqli_affected_rows($conn);
            logActivity("Memindahkan $total_moved santri ke kelas $nama_kelas_tujuan", 'santri', $ids_string);
            setFlash('success', "Berhasil memindahkan $total_moved santri ke kelas $nama_kelas_tujuan!");
            header("Location: " . $pindah_kelas_url . '?kelas_asal=' . $kelas_asal);
            exit;
        } else {
            $error_msg = 'Gagal memindahkan kelas santri. Silakan coba lagi!';
        }
    }
}

// Handle bulk nonaktifkan / delete submit (biar bisa bersih-bersih santri sebelum pindah kelas)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_action_type'])) {
    $kelas_asal = isset($_POST['kelas_asal']) ? (int) sanitize($_POST['kelas_asal']) : 0;
    $action = $_POST['bulk_action_type'];
    $selected_santri = isset($_POST['santri_ids']) ? $_POST['santri_ids'] : [];
    $ids_clean = array_filter(array_map('intval', $selected_santri));

    if (empty($ids_clean)) {
        $error_msg = 'Silakan pilih minimal satu santri untuk melakukan aksi ini!';
    } else {
        $ids_string = implode(',', $ids_clean);
        $total = count($ids_clean);

        if ($action === 'nonaktifkan') {
            mysqli_query($conn, "UPDATE santri SET status = 'nonaktif' WHERE id_santri IN ($ids_string)");
            mysqli_query($conn, "UPDATE santri_iuran SET is_active = 0 WHERE id_santri IN ($ids_string)");
            logActivity("Menonaktifkan $total santri secara massal dari halaman pindah kelas", 'santri', $ids_string);
            setFlash('success', "Berhasil menonaktifkan $total santri!");
                header("Location: " . $pindah_kelas_url . '?kelas_asal=' . $kelas_asal);
            exit;
        } elseif ($action === 'delete') {
            // Riwayat pembayaran & tagihan ikut terhapus otomatis oleh database
            // (FK ON DELETE CASCADE — lihat migrations/2026_08_05_cascade_delete_santri.sql)
            $jml_bayar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembayaran WHERE id_santri IN ($ids_string)"))['total'];

            if (mysqli_query($conn, "DELETE FROM santri WHERE id_santri IN ($ids_string)")) {
                $deleted = mysqli_affected_rows($conn);
                logActivity("Menghapus $deleted santri secara massal beserta $jml_bayar riwayat pembayaran", 'santri', $ids_string);
                setFlash('success', $jml_bayar > 0
                    ? "Berhasil menghapus $deleted santri beserta $jml_bayar riwayat pembayarannya!"
                    : "Berhasil menghapus $deleted santri!");
            } else {
                setFlash('danger', 'Gagal menghapus santri! ' . mysqli_error($conn));
            }
                    header("Location: " . $pindah_kelas_url . '?kelas_asal=' . $kelas_asal);
            exit;
        }
    }
}

$page_title = 'Pindah Kelas Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get list of all classes
$kelas_list = [];
$q_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas");
while ($row = mysqli_fetch_assoc($q_kelas)) {
    $kelas_list[] = $row;
}

// Current filter kelas asal
$filter_kelas_asal = isset($_GET['kelas_asal']) ? (int) sanitize($_GET['kelas_asal']) : 0;

// Get students list based on filter
$santri_list = [];
if ($filter_kelas_asal > 0) {
    $q_santri = mysqli_query($conn, "SELECT id_santri, nama, status FROM santri WHERE id_kelas = $filter_kelas_asal ORDER BY status ASC, nama");
    while ($row = mysqli_fetch_assoc($q_santri)) {
        $santri_list[] = $row;
    }
}
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-users-cog"></i></div>
                        Pindah Kelas Santri
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <a href="index.php" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Data Santri
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-xl px-4 mt-4">

    <?php
    $flash = getFlash();
    if ($flash):
        ?>
        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error_msg): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Filter Kelas Asal -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter mr-2"></i>1. Pilih Kelas Asal
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="form-inline row align-items-center">
                        <div class="col-md-4 my-1">
                            <select name="kelas_asal" class="form-control w-100" onchange="this.form.submit()">
                                <option value="0">-- Pilih Kelas Asal --</option>
                                <?php foreach ($kelas_list as $kls): ?>
                                    <option value="<?php echo $kls['id_kelas']; ?>" <?php echo $filter_kelas_asal === (int) $kls['id_kelas'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($kls['nama_kelas']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 my-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Tampilkan Santri
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($filter_kelas_asal > 0): ?>
            <!-- Form pemindahan -->
            <div class="col-lg-12">
                <form method="POST" action="">
                    <input type="hidden" name="kelas_asal" value="<?php echo $filter_kelas_asal; ?>">

                    <div class="row">
                        <!-- List Santri -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap"
                                    style="gap:8px;">
                                    <h6 class="m-0 font-weight-bold text-primary"><i
                                            class="fas fa-user-graduate mr-2"></i>2. Pilih Santri</h6>
                                    <div class="d-flex align-items-center" style="gap:8px;">
                                        <button type="submit" name="bulk_action_type" value="nonaktifkan" formnovalidate
                                            class="btn btn-warning btn-sm"
                                            onclick="return confirm('Nonaktifkan santri terpilih?')">
                                            <i class="fas fa-user-slash"></i> Nonaktifkan
                                        </button>
                                        <button type="submit" name="bulk_action_type" value="delete" formnovalidate
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus permanen santri terpilih?\n\nSeluruh riwayat pembayarannya JUGA akan terhapus dan Sisa Kas akan berkurang. Aksi ini tidak dapat dibatalkan.')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                        <span class="badge bg-primary text-white"><?php echo count($santri_list); ?>
                                            Santri</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($santri_list)): ?>
                                        <div class="alert alert-warning text-center my-3">
                                            Tidak ada santri di kelas ini.
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%; text-align: center;">
                                                            <input type="checkbox" id="check-all"
                                                                style="transform: scale(1.2); cursor: pointer;">
                                                        </th>
                                                        <th>Nama Santri</th>
                                                        <th style="width: 25%;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($santri_list as $snt): ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="checkbox" name="santri_ids[]"
                                                                    value="<?php echo $snt['id_santri']; ?>" class="santri-checkbox"
                                                                    style="transform: scale(1.2); cursor: pointer;">
                                                            </td>
                                                            <td><strong><?php echo htmlspecialchars($snt['nama']); ?></strong></td>
                                                            <td>
                                                                <span
                                                                    class="badge <?php echo $snt['status'] == 'active' ? 'bg-success' : 'bg-secondary'; ?> text-white">
                                                                    <?php echo $snt['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Target Class and Submit Action -->
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-exchange-alt mr-2"></i>3.
                                        Kelas Tujuan & Aksi</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold mb-2">Pilih Kelas Baru:</label>
                                        <select name="kelas_tujuan" class="form-control" required>
                                            <option value="0">-- Pilih Kelas Tujuan --</option>
                                            <?php foreach ($kelas_list as $kls): ?>
                                                <?php if ($filter_kelas_asal !== (int) $kls['id_kelas']): ?>
                                                    <option value="<?php echo $kls['id_kelas']; ?>">
                                                        <?php echo htmlspecialchars($kls['nama_kelas']); ?>
                                                    </option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        <small class="text-muted d-block mt-1">Santri yang dicentang akan dipindahkan ke
                                            kelas ini.</small>
                                    </div>

                                    <button type="submit" name="pindahkan_kelas"
                                        class="btn btn-success btn-block w-100 py-2 font-weight-bold"
                                        onclick="return confirm('Apakah Anda yakin ingin memindahkan kelas santri terpilih?')">
                                        <i class="fas fa-check-double"></i> Eksekusi Pindah Kelas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkAll = document.getElementById('check-all');
        const checkboxes = document.querySelectorAll('.santri-checkbox');

        if (checkAll) {
            checkAll.addEventListener('change', function () {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
            });
        }

        // Keep checkAll state in sync
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                const checkedCount = document.querySelectorAll('.santri-checkbox:checked').length;
                if (checkAll) {
                    checkAll.checked = (checkedCount === checkboxes.length);
                }
            });
        });
    });
</script>

<?php
require_once '../../includes/footer.php';
?>
