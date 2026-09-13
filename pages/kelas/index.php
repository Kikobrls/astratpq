<?php
/**
 * Data Kelas
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
    $id = sanitize($_GET['delete']);

    // Check if class has students
    $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM santri WHERE id_kelas = '$id'"));

    if ($check['total'] > 0) {
        setFlash('danger', 'Kelas tidak dapat dihapus karena memiliki data santri!');
    } else {
        if (mysqli_query($conn, "DELETE FROM kelas WHERE id_kelas = '$id'")) {
            logActivity('Menghapus data kelas', 'kelas', $id);
            setFlash('success', 'Data kelas berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus data kelas!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit - BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kelas = isset($_POST['id_kelas']) ? sanitize($_POST['id_kelas']) : '';
    $nama_kelas = sanitize($_POST['nama_kelas']);

    if (empty($nama_kelas)) {
        setFlash('danger', 'Nama kelas harus diisi!');
    } else {
        if (empty($id_kelas)) {
            // Add new
            $query = "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $query = "UPDATE kelas SET nama_kelas = '$nama_kelas' WHERE id_kelas = '$id_kelas'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan data kelas ($msg)", 'kelas', $id_kelas ?: mysqli_insert_id($conn));
            setFlash('success', "Data kelas berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data kelas!');
        }
    }
    header("Location: index.php");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Data Kelas';
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
                        <div class="page-header-icon"><i class="fas fa-chalkboard"></i></div>
                        Data Kelas
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <a href="<?php echo $base_url; ?>/pages/santri/pindahkelas.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-exchange-alt me-1"></i> Pindah Kelas Santri
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kelasModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Kelas
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kelas</h6>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="kelasLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="kelasTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kelas</th>
                            <th width="20%">Jumlah Santri</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_kelas.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<!-- Modal -->
<div class="modal fade" id="kelasModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Kelas</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_kelas" id="id_kelas">
                    <div class="form-group">
                        <label>Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" required>
                        <small class="text-muted">Contoh: X RPL 1, XI TKJ 2</small>
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
    document.getElementById("modalTitle").textContent = "Tambah Kelas";
    document.getElementById("id_kelas").value = "";
    document.getElementById("nama_kelas").value = "";
}

function editKelas(data) {
    document.getElementById("modalTitle").textContent = "Edit Kelas";
    document.getElementById("id_kelas").value = data.id_kelas;
    document.getElementById("nama_kelas").value = data.nama_kelas;
    $("#kelasModal").modal("show");
}

$(function () {
    $("#kelasTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_kelas.php",
            error: function () {
                $("#kelasLoadingOverlay").fadeOut(150);
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
            $("#kelasLoadingOverlay").fadeOut(150);
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
