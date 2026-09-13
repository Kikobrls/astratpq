<?php
/**
 * Data Santri (CRUD)
 * Sistem Keuangan TPQ
 */

// Start session and require config FIRST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Handle delete - BEFORE any HTML output
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $santri = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama FROM santri WHERE id_santri = '$id'"));
    if (!$santri) {
        setFlash('danger', 'Data santri tidak ditemukan!');
        header("Location: index.php");
        exit;
    }

    // Delete payment history as well (as warned in the UI), then the santri row
    mysqli_begin_transaction($conn);
    try {
        mysqli_query($conn, "DELETE FROM pembayaran WHERE id_santri = '$id'");
        if (appTableExists('santri_iuran')) {
            mysqli_query($conn, "DELETE FROM santri_iuran WHERE id_santri = '$id'");
        }
        mysqli_query($conn, "DELETE FROM santri WHERE id_santri = '$id'");
        mysqli_commit($conn);
        logActivity('Menghapus data santri', 'santri', $id);
        setFlash('success', 'Data santri berhasil dihapus!');
    } catch (Throwable $e) {
        mysqli_rollback($conn);
        setFlash('danger', 'Gagal menghapus data santri!');
    }
    header("Location: index.php");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Data Santri';
$extra_css = '
<link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Data for filters
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-graduate"></i></div>
                        Data Santri
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <a href="pindahkelas.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-exchange-alt me-1"></i> Pindah Kelas
                    </a>
                    <a href="add.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Santri
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <!-- DataTales -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Santri</h6>
            <div class="d-flex gap-2">
                <select id="filterKelas" class="form-control form-control-sm" style="width: 150px;">
                    <option value="">Semua Kelas</option>
                    <?php while ($k = mysqli_fetch_assoc($kelas_list)): ?>
                        <option value="<?php echo (int) $k['id_kelas']; ?>">
                            <?php echo htmlspecialchars($k['nama_kelas']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <select id="filterStatus" class="form-control form-control-sm" style="width: 140px;">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="santriLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="santriTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Santri</th>
                            <th width="10%">Umur</th>
                            <th width="10%">Kelas</th>
                            <th width="15%">Status Bulanan</th>
                            <th width="15%">Status Tahunan</th>
                            <th width="10%">Status</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_santri.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<?php
$extra_js = '
<script src="https://cdn.jsdelivr.net/npm/datatables.net@2/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
    var table = $("#santriTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_santri.php",
            data: function (d) {
                d.id_kelas = $("#filterKelas").val();
                d.status = $("#filterStatus").val();
            },
            error: function () {
                $("#santriLoadingOverlay").fadeOut(150);
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2, className: "text-center" },
            { data: 3, className: "text-center" },
            { data: 4, orderable: false, searchable: false, className: "text-center" },
            { data: 5, orderable: false, searchable: false, className: "text-center" },
            { data: 6, className: "text-center" },
            { data: 7, orderable: false, searchable: false, className: "text-center" }
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#santriLoadingOverlay").fadeOut(150);
        },
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Data tidak ditemukan",
            paginate: { previous: "Sebelumnya", next: "Berikutnya" }
        }
    });

    $("#filterKelas, #filterStatus").on("change", function () {
        table.ajax.reload();
    });
});
</script>
';

require_once '../../includes/footer.php';
?>
