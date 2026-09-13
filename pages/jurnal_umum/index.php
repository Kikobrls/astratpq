<?php
/**
 * Jurnal Umum (CRUD)
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

    if (mysqli_query($conn, "DELETE FROM jurnal_umum WHERE id_jurnal = '$id'")) {
        logActivity('Menghapus jurnal umum', 'jurnal_umum', $id);
        setFlash('success', 'Data jurnal berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus data jurnal!');
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit - BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_jurnal = isset($_POST['id_jurnal']) ? (int) $_POST['id_jurnal'] : 0;
    $tanggal = sanitize($_POST['tanggal'] ?? date('Y-m-d'));
    $id_pos = (int) ($_POST['id_pos'] ?? 0);
    $uraian = sanitize($_POST['uraian'] ?? '');
    $jenis = sanitize($_POST['jenis'] ?? 'Pemasukan');
    $nominal = (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $_POST['nominal'] ?? '0'));

    if ($jenis !== 'Pemasukan' && $jenis !== 'Pengeluaran') {
        $jenis = 'Pemasukan';
    }

    if (empty($tanggal)) {
        setFlash('danger', 'Tanggal harus diisi!');
    } elseif ($id_pos <= 0) {
        setFlash('danger', 'Pos keuangan harus dipilih!');
    } elseif (empty($uraian)) {
        setFlash('danger', 'Uraian harus diisi!');
    } elseif ($nominal <= 0) {
        setFlash('danger', 'Nominal harus lebih dari 0!');
    } else {
        if ($id_jurnal <= 0) {
            $query = "INSERT INTO jurnal_umum (tanggal, id_pos, uraian, jenis, nominal)
                      VALUES ('$tanggal', '$id_pos', '$uraian', '$jenis', '$nominal')";
            $msg = 'ditambahkan';
        } else {
            $query = "UPDATE jurnal_umum SET
                        tanggal = '$tanggal',
                        id_pos = '$id_pos',
                        uraian = '$uraian',
                        jenis = '$jenis',
                        nominal = '$nominal'
                      WHERE id_jurnal = '$id_jurnal'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan jurnal umum ($msg)", 'jurnal_umum', $id_jurnal ?: mysqli_insert_id($conn));
            setFlash('success', "Data jurnal berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data jurnal!');
        }
    }
    header("Location: index.php");
    exit;
}

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Jurnal Umum';
$extra_css = '
<link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Data for filters & modal
$pos_list = mysqli_query($conn, "SELECT * FROM pos_keuangan ORDER BY nama_pos ASC");

// Filters from GET
$filter_pos = sanitize($_GET['filter_pos'] ?? '');
$filter_jenis = sanitize($_GET['jenis'] ?? '');
$filter_dari = sanitize($_GET['dari'] ?? '');
$filter_sampai = sanitize($_GET['sampai'] ?? '');

// Saldo summary honoring filters
$where_parts = ['1=1'];
if ($filter_pos !== '') {
    $where_parts[] = "id_pos = '$filter_pos'";
}
if ($filter_dari !== '') {
    $where_parts[] = "tanggal >= '$filter_dari'";
}
if ($filter_sampai !== '') {
    $where_parts[] = "tanggal <= '$filter_sampai'";
}
if ($filter_jenis !== '') {
    $where_parts[] = "jenis = '$filter_jenis'";
}
$where = implode(' AND ', $where_parts);

$total_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(nominal), 0) as total FROM jurnal_umum WHERE $where AND jenis = 'Pemasukan'"))['total'];
$total_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(nominal), 0) as total FROM jurnal_umum WHERE $where AND jenis = 'Pengeluaran'"))['total'];
$saldo = $total_masuk - $total_keluar;
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-book-open"></i></div>
                        Jurnal Umum
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <?php
                    // Filter aktif ikut dibawa ke export supaya isi file
                    // sama persis dengan yang sedang tampil di layar.
                    $export_qs = http_build_query([
                        'filter_pos' => $filter_pos,
                        'jenis'      => $filter_jenis,
                        'dari'       => $filter_dari,
                        'sampai'     => $filter_sampai,
                    ]);
                    ?>
                    <a href="export.php?<?php echo $export_qs; ?>&type=excel" class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                    <a href="export.php?<?php echo $export_qs; ?>&type=pdf" target="_blank" class="btn btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jurnalModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Jurnal
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo formatRupiah($total_masuk); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo formatRupiah($total_keluar); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saldo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo formatRupiah($saldo); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Jurnal</h6>
            <form method="GET" class="d-flex gap-2 align-items-center" id="filterForm">
                <select name="filter_pos" class="form-control form-control-sm" style="width: 150px;">
                    <option value="">Semua Pos</option>
                    <?php mysqli_data_seek($pos_list, 0); while ($p = mysqli_fetch_assoc($pos_list)): ?>
                        <option value="<?php echo (int) $p['id_pos']; ?>" <?php echo ($filter_pos == $p['id_pos']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p['nama_pos']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <select name="jenis" class="form-control form-control-sm" style="width: 130px;">
                    <option value="">Semua Jenis</option>
                    <option value="Pemasukan" <?php echo ($filter_jenis === 'Pemasukan') ? 'selected' : ''; ?>>Pemasukan</option>
                    <option value="Pengeluaran" <?php echo ($filter_jenis === 'Pengeluaran') ? 'selected' : ''; ?>>Pengeluaran</option>
                </select>
                <input type="date" name="dari" class="form-control form-control-sm" style="width: 140px;" value="<?php echo htmlspecialchars($filter_dari); ?>" title="Dari tanggal">
                <input type="date" name="sampai" class="form-control form-control-sm" style="width: 140px;" value="<?php echo htmlspecialchars($filter_sampai); ?>" title="Sampai tanggal">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-filter"></i></button>
                <a href="index.php" class="btn btn-sm btn-secondary" title="Reset filter"><i class="fas fa-undo"></i></a>
            </form>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="jurnalLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="jurnalTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Tanggal</th>
                            <th>Pos Keuangan</th>
                            <th>Uraian</th>
                            <th width="12%">Jenis</th>
                            <th width="15%">Nominal</th>
                            <th width="8%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_jurnal_umum.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<!-- Modal -->
<div class="modal fade" id="jurnalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Jurnal</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_jurnal" id="id_jurnal">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Tanggal <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Pos Keuangan <span class="text-danger">*</span></label>
                                <select name="id_pos" id="id_pos" class="form-control" required>
                                    <option value="">-- Pilih Pos --</option>
                                    <?php mysqli_data_seek($pos_list, 0); while ($p = mysqli_fetch_assoc($pos_list)): ?>
                                        <option value="<?php echo (int) $p['id_pos']; ?>">
                                            <?php echo htmlspecialchars($p['nama_pos']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Uraian <span class="text-danger">*</span></label>
                        <textarea name="uraian" id="uraian" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" id="jenis" class="form-control" required>
                                    <option value="Pemasukan">Pemasukan</option>
                                    <option value="Pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nominal (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="nominal" id="nominal" class="form-control" min="0" step="500" required>
                            </div>
                        </div>
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
    document.getElementById("modalTitle").textContent = "Tambah Jurnal";
    document.getElementById("id_jurnal").value = "";
    document.getElementById("tanggal").value = "' . date('Y-m-d') . '";
    document.getElementById("id_pos").value = "";
    document.getElementById("uraian").value = "";
    document.getElementById("jenis").value = "Pemasukan";
    document.getElementById("nominal").value = "";
}

function editJurnal(data) {
    document.getElementById("modalTitle").textContent = "Edit Jurnal";
    document.getElementById("id_jurnal").value = data.id_jurnal;
    document.getElementById("tanggal").value = data.tanggal;
    document.getElementById("id_pos").value = data.id_pos;
    document.getElementById("uraian").value = data.uraian;
    document.getElementById("jenis").value = data.jenis;
    document.getElementById("nominal").value = parseFloat(data.nominal);
    $("#jurnalModal").modal("show");
}

$(function () {
    var table = $("#jurnalTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_jurnal_umum.php",
            data: function (d) {
                d.filter_pos = "' . addslashes($filter_pos) . '";
                d.jenis = "' . addslashes($filter_jenis) . '";
                d.dari = "' . addslashes($filter_dari) . '";
                d.sampai = "' . addslashes($filter_sampai) . '";
            },
            error: function () {
                $("#jurnalLoadingOverlay").fadeOut(150);
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1, className: "text-center" },
            { data: 2 },
            { data: 3 },
            { data: 4, className: "text-center" },
            { data: 5, className: "text-end" },
            { data: 6, orderable: false, searchable: false, className: "text-center" }
        ],
        order: [[1, "desc"]],
        initComplete: function () {
            $("#jurnalLoadingOverlay").fadeOut(150);
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

    // Auto reload when filter form submitted (reload page for summary cards too)
    $("#filterForm select, #filterForm input").on("change", function () {
        $("#filterForm").submit();
    });
});
</script>
';

require_once '../../includes/footer.php';
?>
