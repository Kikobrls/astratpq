<?php
/**
 * Pembayaran Iuran
 * Sistem Keuangan TPQ
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/app.php';
require_once '../../config/database.php';

// Check login
if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    exit;
}

// Block kepala_tpq from accessing this page
if ($_SESSION['level'] == 'kepala_tpq') {
    header("Location: ../../index.php");
    exit;
}

// Handle delete pembayaran
if (isset($_GET['delete'])) {
    $id = (int) sanitize($_GET['delete']);

    if (mysqli_query($conn, "DELETE FROM pembayaran WHERE id_pembayaran = '$id'")) {
        logActivity('Menghapus data pembayaran', 'pembayaran', $id);
        setFlash('success', 'Data pembayaran berhasil dihapus!');
    } else {
        setFlash('danger', 'Gagal menghapus data pembayaran!');
    }
    header('Location: index.php');
    exit;
}

$page_title = 'Pembayaran';
$extra_css = '
<link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$filter_bulan = isset($_GET['bulan']) ? sanitize($_GET['bulan']) : date('m');
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');
$filter_kelas = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';
$filter_iuran = isset($_GET['iuran']) ? sanitize($_GET['iuran']) : '';

$bulan_int = (int) $filter_bulan;
$tahun_int = (int) $filter_tahun;
$periode_start = sprintf('%04d-%02d-01', $tahun_int, $bulan_int);
$periode_end = date('Y-m-d', strtotime($periode_start . ' +1 month'));

// Date-range filter (instead of MONTH()/YEAR()) so idx_pembayaran_tgl_bayar can be used.
// (Still used here for the two summary cards below; the table itself now
// loads via AJAX from api/datatable_pembayaran.php using the same filters.)
$where = "WHERE p.tgl_bayar >= '$periode_start' AND p.tgl_bayar < '$periode_end'";
if (!empty($filter_kelas)) {
    $where .= " AND s.id_kelas = " . (int) $filter_kelas;
}
if (!empty($filter_iuran)) {
    $where .= " AND p.id_iuran = " . (int) $filter_iuran;
}

$total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(p.jumlah_bayar), 0) as total, COUNT(*) as jumlah
    FROM pembayaran p
    JOIN santri s ON p.id_santri = s.id_santri
    $where
"));

$kelas_list = mysqli_query($conn, 'SELECT * FROM kelas ORDER BY nama_kelas');
$iuran_list = mysqli_query($conn, 'SELECT * FROM iuran ORDER BY nama_iuran ASC, tahun DESC');

$bulan_list = [
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember'
];
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-money-bill-wave"></i></div>
                        Pembayaran Iuran
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="add.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Input Pembayaran
                    </a>
                    <a href="bulk.php" class="btn btn-success">
                        <i class="fas fa-layer-group me-1"></i> Pembayaran Kolektif
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <div class="collapse mb-4 <?php echo (!empty($_GET['bulan']) || !empty($_GET['tahun']) || !empty($_GET['kelas']) || !empty($_GET['iuran'])) ? 'show' : ''; ?>"
        id="collapseFilter">
        <div class="card shadow">
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Bulan:</label>
                        <select name="bulan" class="form-control">
                            <?php foreach ($bulan_list as $key => $bulan): ?>
                                <option value="<?php echo $key; ?>" <?php echo $filter_bulan == $key ? 'selected' : ''; ?>>
                                    <?php echo $bulan; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Tahun:</label>
                        <select name="tahun" class="form-control">
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?php echo $y; ?>" <?php echo $filter_tahun == $y ? 'selected' : ''; ?>>
                                    <?php echo $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Kelas:</label>
                        <select name="kelas" class="form-control">
                            <option value="">Semua Kelas</option>
                            <?php while ($kelas = mysqli_fetch_assoc($kelas_list)): ?>
                                <option value="<?php echo $kelas['id_kelas']; ?>" <?php echo $filter_kelas == $kelas['id_kelas'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2">Tagihan:</label>
                        <select name="iuran" class="form-control">
                            <option value="">Semua Tagihan</option>
                            <?php while ($iuran = mysqli_fetch_assoc($iuran_list)): ?>
                                <option value="<?php echo $iuran['id_iuran']; ?>" <?php echo $filter_iuran == $iuran['id_iuran'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($iuran['nama_iuran'] . ' (' . $iuran['tahun'] . ')'); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pembayaran (<?php echo $bulan_list[$filter_bulan] . ' ' . $filter_tahun; ?>)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($total['total']); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Detail Pembayaran
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($total['jumlah']); ?> Item
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="pembayaranLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="pembayaranTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Santri</th>
                            <th>Kelas</th>
                            <th>Periode</th>
                            <th>Tagihan/Iuran</th>
                            <th>Jumlah</th>
                            <th>Pengguna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_pembayaran.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = '
<script src="https://cdn.jsdelivr.net/npm/datatables.net@2/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@2/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
    var pembayaranTable = $("#pembayaranTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_pembayaran.php",
            data: function (d) {
                d.bulan = ' . json_encode($filter_bulan) . ';
                d.tahun = ' . json_encode($filter_tahun) . ';
                d.kelas = ' . json_encode($filter_kelas) . ';
                d.iuran = ' . json_encode($filter_iuran) . ';
            },
            error: function () {
                $("#pembayaranLoadingOverlay").fadeOut(150);
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4, orderable: false, searchable: false },
            { data: 5 },
            { data: 6, className: "text-end" },
            { data: 7 },
            { data: 8, orderable: false, searchable: false, className: "text-center" }
        ],
        order: [[1, "desc"]],
        initComplete: function () {
            $("#pembayaranLoadingOverlay").fadeOut(150);
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
require_once '../../includes/footer.php'; ?>
