<?php
/**
 * Data Pos Keuangan (CRUD)
 * Sistem Keuangan TPQ
 */

// Start session and require config FIRST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Block kepala_tpq (read-only role)
if (($_SESSION['level'] ?? '') === 'kepala_tpq') {
    header("Location: ../laporan/index.php");
    exit;
}

// Handle delete - BEFORE any HTML output
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Protect journal history: block delete if the pos is used in jurnal_umum
    $checkJurnal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jurnal_umum WHERE id_pos = '$id'"));

    if ($checkJurnal['total'] > 0) {
        setFlash('danger', 'Pos keuangan tidak dapat dihapus karena sudah dipakai di jurnal umum!');
    } else {
        if (mysqli_query($conn, "DELETE FROM pos_keuangan WHERE id_pos = '$id'")) {
            logActivity('Menghapus pos keuangan', 'pos_keuangan', $id);
            setFlash('success', 'Pos keuangan berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus pos keuangan!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit - BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pos = isset($_POST['id_pos']) ? (int) $_POST['id_pos'] : 0;
    $nama_pos = sanitize($_POST['nama_pos'] ?? '');

    if (empty($nama_pos)) {
        setFlash('danger', 'Nama pos keuangan harus diisi!');
    } else {
        // Prevent duplicate names
        $dup = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pos_keuangan WHERE nama_pos = '$nama_pos' AND id_pos != '$id_pos'"));
        if ($dup['total'] > 0) {
            setFlash('danger', 'Nama pos keuangan sudah ada!');
        } else {
            if ($id_pos <= 0) {
                $query = "INSERT INTO pos_keuangan (nama_pos) VALUES ('$nama_pos')";
                $msg = 'ditambahkan';
            } else {
                $query = "UPDATE pos_keuangan SET nama_pos = '$nama_pos' WHERE id_pos = '$id_pos'";
                $msg = 'diubah';
            }

            if (mysqli_query($conn, $query)) {
                logActivity("Menyimpan pos keuangan ($msg)", 'pos_keuangan', $id_pos ?: mysqli_insert_id($conn));
                setFlash('success', "Pos keuangan berhasil $msg!");
            } else {
                setFlash('danger', 'Gagal menyimpan pos keuangan!');
            }
        }
    }
    header("Location: index.php");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Pos Keuangan';
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
                        <div class="page-header-icon"><i class="fas fa-chart-pie"></i></div>
                        Pos Keuangan
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#posModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Pos
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pos Keuangan</h6>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="posLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="posTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Pos</th>
                            <th width="15%">Jumlah Transaksi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_pos_keuangan.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<!-- Modal -->
<div class="modal fade" id="posModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Pos Keuangan</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_pos" id="id_pos">
                    <div class="form-group mb-3">
                        <label>Nama Pos <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pos" id="nama_pos" class="form-control" required>
                        <small class="text-muted">Contoh: SPP, Gaji Guru, Infak Jumat</small>
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
    document.getElementById("modalTitle").textContent = "Tambah Pos Keuangan";
    document.getElementById("id_pos").value = "";
    document.getElementById("nama_pos").value = "";
}

function editPos(data) {
    document.getElementById("modalTitle").textContent = "Edit Pos Keuangan";
    document.getElementById("id_pos").value = data.id_pos;
    document.getElementById("nama_pos").value = data.nama_pos;
    $("#posModal").modal("show");
}

$(function () {
    $("#posTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_pos_keuangan.php",
            error: function () {
                $("#posLoadingOverlay").fadeOut(150);
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2, orderable: false, searchable: false, className: "text-center" },
            { data: 3, orderable: false, searchable: false, className: "text-center" }
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#posLoadingOverlay").fadeOut(150);
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
