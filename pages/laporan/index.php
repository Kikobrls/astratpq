<?php
/**
 * Laporan Pembayaran, Jurnal, dan Kas
 * Sistem Administrasi Keuangan Sekolah
 */

$page_title = 'Laporan';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Filters
$filter_tahun = isset($_GET['tahun']) ? sanitize($_GET['tahun']) : date('Y');

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

// Aggregation query: 1 query for the whole year instead of 24
// (2 queries x 12 months). Uses a date-range filter on `tanggal`
// instead of MONTH()/YEAR() so idx_jurnal_tanggal_jenis can be
// used instead of a full table scan.
$report_data = [];
$total_pemasukan_year = 0;
$total_pengeluaran_year = 0;
$total_sisa_kas_year = 0;

for ($m = 1; $m <= 12; $m++) {
    $bulan_str = str_pad($m, 2, '0', STR_PAD_LEFT);
    $report_data[$m] = [
        'bulan' => $bulan_list[$bulan_str],
        'total_in' => 0.0,
        'jurnal_out' => 0.0,
        'sisa_kas' => 0.0,
    ];
}

$tahun_int = (int) $filter_tahun;
$agg_query = mysqli_query($conn, "
    SELECT MONTH(tanggal) as bulan, jenis, SUM(nominal) as total
    FROM jurnal_umum
    WHERE tanggal >= '{$tahun_int}-01-01' AND tanggal < '" . ($tahun_int + 1) . "-01-01'
    GROUP BY MONTH(tanggal), jenis
");

while ($agg_query && ($row = mysqli_fetch_assoc($agg_query))) {
    $m = (int) $row['bulan'];
    if (!isset($report_data[$m])) {
        continue;
    }
    $total = (float) $row['total'];
    if ($row['jenis'] === 'Pemasukan') {
        $report_data[$m]['total_in'] = $total;
    } else {
        $report_data[$m]['jurnal_out'] = $total;
    }
}

foreach ($report_data as $m => &$data) {
    $data['sisa_kas'] = $data['total_in'] - $data['jurnal_out'];
    $total_pemasukan_year += $data['total_in'];
    $total_pengeluaran_year += $data['jurnal_out'];
    $total_sisa_kas_year += $data['sisa_kas'];
}
unset($data);

$nama_sekolah = getSetting('nama_sekolah', 'Keuangan Sekolah');
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-chart-bar"></i></div>
                        Laporan Keuangan Bulanan
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="export.php?tahun=<?php echo $filter_tahun; ?>&type=excel"
                        class="btn btn-success">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                    <a href="export.php?tahun=<?php echo $filter_tahun; ?>&type=pdf" target="_blank"
                        class="btn btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                    </a>
                    <a href="export.php?tahun=<?php echo $filter_tahun; ?>&type=print" target="_blank"
                        class="btn btn-primary">
                        <i class="fas fa-print me-1"></i> Cetak
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <!-- Filter -->
    <div class="collapse mb-4 <?php echo isset($_GET['tahun']) ? 'show' : ''; ?>" id="collapseFilter">
        <div class="card shadow no-print">
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2">Tahun Keuangan:</label>
                        <select name="tahun" class="form-control">
                            <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                                <option value="<?php echo $y; ?>" <?php echo $filter_tahun == $y ? 'selected' : ''; ?>>
                                    <?php echo $y; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Pemasukan Umum -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pemasukan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($total_pemasukan_year); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pengeluaran -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($total_pengeluaran_year); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sisa Kas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sisa Saldo Kas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($total_sisa_kas_year); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Header -->
    <div class="print-only text-center mb-4" style="display: none;">
        <h3><?php echo htmlspecialchars($nama_sekolah); ?></h3>
        <h4>Laporan Keuangan Bulanan</h4>
        <p>Tahun: <?php echo $filter_tahun; ?></p>
    </div>

    <!-- Report Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Rekapitulasi Keuangan Bulanan - Tahun <?php echo $filter_tahun; ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Bulan</th>
                            <th>Total Pemasukan</th>
                            <th>Total Pengeluaran</th>
                            <th>Sisa Kas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report_data as $m => $row): ?>
                            <tr>
                                <td class="text-center"><?php echo $m; ?></td>
                                <td><strong><?php echo $row['bulan']; ?></strong></td>
                                <td class="text-right font-weight-bold text-success"><?php echo formatRupiah($row['total_in']); ?></td>
                                <td class="text-right text-danger"><?php echo formatRupiah($row['jurnal_out']); ?></td>
                                <td class="text-right font-weight-bold <?php echo $row['sisa_kas'] >= 0 ? 'text-primary' : 'text-danger'; ?>">
                                    <?php echo formatRupiah($row['sisa_kas']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="thead-light font-weight-bold text-right">
                        <tr>
                            <td colspan="2" class="text-center">TOTAL TAHUNAN</td>
                            <td class="text-success"><?php echo formatRupiah($total_pemasukan_year); ?></td>
                            <td class="text-danger"><?php echo formatRupiah($total_pengeluaran_year); ?></td>
                            <td class="<?php echo $total_sisa_kas_year >= 0 ? 'text-primary' : 'text-danger'; ?>">
                                <?php echo formatRupiah($total_sisa_kas_year); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    @media print {
        .no-print,
        .sidebar,
        .topbar,
        .scroll-to-top,
        .footer {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        #content-wrapper {
            margin-left: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

<?php require_once '../../includes/footer.php'; ?>
