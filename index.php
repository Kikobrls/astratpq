<?php
/**
 * Dashboard
 * Sistem Administrasi Keuangan Sekolah
 */

$page_title = 'Dashboard';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/topbar.php';

// Statistics queries
$total_santri = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM santri WHERE status = 'active'"));

// Pemasukan Bulan Ini (pembayaran + jurnal_umum)
$pembayaran_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total 
    FROM pembayaran 
    WHERE MONTH(tgl_bayar) = MONTH(CURRENT_DATE()) 
    AND YEAR(tgl_bayar) = YEAR(CURRENT_DATE())
"));
$jurnal_in_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(nominal), 0) as total 
    FROM jurnal_umum 
    WHERE jenis = 'Pemasukan' 
    AND MONTH(tanggal) = MONTH(CURRENT_DATE()) 
    AND YEAR(tanggal) = YEAR(CURRENT_DATE())
"));
$total_pemasukan_bulan_ini = ($pembayaran_bulan_ini['total'] ?? 0) + ($jurnal_in_bulan_ini['total'] ?? 0);

// Pengeluaran Bulan Ini (jurnal_umum)
$pengeluaran_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(nominal), 0) as total 
    FROM jurnal_umum 
    WHERE jenis = 'Pengeluaran' 
    AND MONTH(tanggal) = MONTH(CURRENT_DATE()) 
    AND YEAR(tanggal) = YEAR(CURRENT_DATE())
"));
$total_pengeluaran_bulan_ini = $pengeluaran_bulan_ini['total'] ?? 0;

// Sisa Kas (Total Pemasukan All-Time - Total Pengeluaran All-Time)
$total_pembayaran_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_bayar), 0) as total FROM pembayaran"))['total'] ?? 0;
$total_jurnal_in_all  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(nominal), 0) as total FROM jurnal_umum WHERE jenis = 'Pemasukan'"))['total'] ?? 0;
$total_jurnal_out_all = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(nominal), 0) as total FROM jurnal_umum WHERE jenis = 'Pengeluaran'"))['total'] ?? 0;
$sisa_kas = ($total_pembayaran_all + $total_jurnal_in_all) - $total_jurnal_out_all;

// Recent payments
$recent_payments = mysqli_query($conn, "
    SELECT p.*, s.nama as nama_santri, k.nama_kelas, b.nama_iuran 
    FROM pembayaran p 
    JOIN santri s ON p.id_santri = s.id_santri 
    JOIN kelas k ON s.id_kelas = k.id_kelas 
    JOIN iuran b ON p.id_iuran = b.id_iuran
    ORDER BY p.created_at DESC 
    LIMIT 10
");
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="activity"></i></div>
                        Dashboard
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <a href="pages/laporan/index.php" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Generate Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <!-- Content Row (4 Statistic Cards) -->
    <div class="row">

        <!-- Total Santri -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                TOTAL SANTRI</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($total_santri['total']); ?> santri
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                PEMASUKAN BULAN INI</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($total_pemasukan_bulan_ini); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                PENGELUARAN BULAN INI</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($total_pengeluaran_bulan_ini); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisa Kas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                SISA KAS</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo formatRupiah($sisa_kas); ?>
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

    <!-- Content Row (Pembayaran Iuran Terbaru Table) -->
    <div class="row">

        <!-- Recent Payments -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pembayaran Iuran Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Santri</th>
                                    <th>Kelas</th>
                                    <th>Tagihan</th>
                                    <th>Bulan/Tahun</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($recent_payments)): ?>
                                    <tr>
                                        <td><?php echo date('d-m-Y', strtotime($row['tgl_bayar'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_santri']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_kelas']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($row['nama_iuran']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['bulan_dibayar']) . ' ' . $row['tahun_dibayar']; ?></td>
                                        <td><?php echo formatRupiah($row['jumlah_bayar']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="pages/pembayaran/index.php" class="btn btn-primary btn-sm">Lihat Semua &rarr;</a>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.container-xl -->

<?php
require_once 'includes/footer.php';
?>