<?php
/**
 * Data Iuran (CRUD)
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

    // Protect payment history: block delete if the iuran already has payments
    $checkBayar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembayaran WHERE id_iuran = '$id'"));

    if ($checkBayar['total'] > 0) {
        setFlash('danger', 'Iuran tidak dapat dihapus karena sudah memiliki riwayat pembayaran!');
    } else {
        if (mysqli_query($conn, "DELETE FROM iuran WHERE id_iuran = '$id'")) {
            // Cleanup relations & legacy pointer
            if (appTableExists('santri_iuran')) {
                mysqli_query($conn, "DELETE FROM santri_iuran WHERE id_iuran = '$id'");
            }
            mysqli_query($conn, "UPDATE santri SET id_iuran = NULL WHERE id_iuran = '$id'");
            logActivity('Menghapus data iuran', 'iuran', $id);
            setFlash('success', 'Data iuran berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus data iuran!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit - BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_iuran = isset($_POST['id_iuran']) ? (int) $_POST['id_iuran'] : 0;
    $nama_iuran = sanitize($_POST['nama_iuran'] ?? '');
    $tahun = (int) ($_POST['tahun'] ?? date('Y'));
    $periode_tipe = strtolower(sanitize($_POST['periode_tipe'] ?? 'bulanan'));
    if ($periode_tipe !== 'bulanan' && $periode_tipe !== 'tahunan') {
        $periode_tipe = 'bulanan';
    }
    $nominal = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $_POST['nominal'] ?? '0'));
    $keterangan = sanitize($_POST['keterangan'] ?? '');

    if (empty($nama_iuran)) {
        setFlash('danger', 'Nama iuran harus diisi!');
    } elseif ($tahun <= 0) {
        setFlash('danger', 'Tahun harus diisi dengan benar!');
    } elseif ($nominal <= 0) {
        setFlash('danger', 'Nominal harus lebih dari 0!');
    } else {
        if ($id_iuran <= 0) {
            // Add new
            $query = "INSERT INTO iuran (nama_iuran, tahun, periode_tipe, nominal, keterangan)
                      VALUES ('$nama_iuran', '$tahun', '$periode_tipe', '$nominal', '$keterangan')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $query = "UPDATE iuran SET
                        nama_iuran = '$nama_iuran',
                        tahun = '$tahun',
                        periode_tipe = '$periode_tipe',
                        nominal = '$nominal',
                        keterangan = '$keterangan'
                      WHERE id_iuran = '$id_iuran'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan data iuran ($msg)", 'iuran', $id_iuran ?: mysqli_insert_id($conn));
            setFlash('success', "Data iuran berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data iuran! ' . mysqli_error($conn));
        }
    }
    header("Location: index.php");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Data Iuran';
$extra_css = '
<link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-dollar-sign"></i></div>
                        Data Iuran
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#iuranModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Iuran
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <!-- DataTales -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Iuran</h6>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="iuranLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="iuranTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Iuran</th>
                            <th width="12%">Tipe Periode</th>
                            <th width="10%">Tahun</th>
                            <th width="15%">Nominal</th>
                            <th>Keterangan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_iuran.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<!-- Modal -->
<div class="modal fade" id="iuranModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Iuran</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_iuran" id="id_iuran">
                    <div class="form-group mb-3">
                        <label>Nama Iuran <span class="text-danger">*</span></label>
                        <input type="text" name="nama_iuran" id="nama_iuran" class="form-control" required>
                        <small class="text-muted">Contoh: SPP, Daftar Ulang, Ekskul</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tipe Periode <span class="text-danger">*</span></label>
                                <select name="periode_tipe" id="periode_tipe" class="form-control" required>
                                    <option value="bulanan">Bulanan</option>
                                    <option value="tahunan">Tahunan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tahun <span class="text-danger">*</span></label>
                                <input type="number" name="tahun" id="tahun" class="form-control"
                                    value="<?php echo date('Y'); ?>" min="2000" max="2100" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nominal (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="nominal" id="nominal" class="form-control" min="0" step="500" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$extra_js = '
<script src="https://cdn.jsdelivr.net/npm/datatables.net@2/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/js/dataTables.bootstrap5.min.js"></script>
<script>
function resetForm() {
    document.getElementById("modalTitle").textContent = "Tambah Iuran";
    document.getElementById("id_iuran").value = "";
    document.getElementById("nama_iuran").value = "";
    document.getElementById("periode_tipe").value = "bulanan";
    document.getElementById("tahun").value = "' . date('Y') . '";
    document.getElementById("nominal").value = "";
    document.getElementById("keterangan").value = "";
}

function editIuran(data) {
    document.getElementById("modalTitle").textContent = "Edit Iuran";
    document.getElementById("id_iuran").value = data.id_iuran;
    document.getElementById("nama_iuran").value = data.nama_iuran;
    document.getElementById("periode_tipe").value = data.periode_tipe || "bulanan";
    document.getElementById("tahun").value = data.tahun;
    document.getElementById("nominal").value = parseFloat(data.nominal);
    document.getElementById("keterangan").value = data.keterangan || "";
    $("#iuranModal").modal("show");
}

$(function () {
    $("#iuranTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_iuran.php",
            error: function () {
                $("#iuranLoadingOverlay").fadeOut(150);
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2, className: "text-center" },
            { data: 3, className: "text-center" },
            { data: 4 },
            { data: 5 },
            { data: 6, orderable: false, searchable: false, className: "text-center" }
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#iuranLoadingOverlay").fadeOut(150);
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
});
</script>
';

require_once '../../includes/footer.php';
?>
