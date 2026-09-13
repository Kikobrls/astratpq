<?php
/**
 * Detail Santri
 * Sistem Keuangan TPQ
 */

$page_title = 'Detail Santri';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Check ID
if (!isset($_GET['id'])) {
    setFlash('danger', 'ID Santri tidak ditemukan!');
    echo "<script>window.location='index.php'</script>";
    exit;
}

$id = sanitize($_GET['id']);

// Get student data
$query = "SELECT s.*, k.nama_kelas,
                 COALESCE(sb_data.nama_iuran_utama, b_legacy.nama_iuran) as nama_iuran,
                 COALESCE(sb_data.tahun_iuran, b_legacy.tahun) as tahun_iuran,
                 COALESCE(sb_data.nominal_utama, b_legacy.nominal) as nominal,
                 sb_data.daftar_iuran
          FROM santri s
          JOIN kelas k ON s.id_kelas = k.id_kelas
          LEFT JOIN iuran b_legacy ON s.id_iuran = b_legacy.id_iuran
          LEFT JOIN (
              SELECT sb.id_santri,
                     SUBSTRING_INDEX(GROUP_CONCAT(b.nama_iuran ORDER BY b.nama_iuran SEPARATOR '||'), '||', 1) as nama_iuran_utama,
                     SUBSTRING_INDEX(GROUP_CONCAT(b.tahun ORDER BY b.nama_iuran SEPARATOR '||'), '||', 1) as tahun_iuran,
                     SUBSTRING_INDEX(GROUP_CONCAT(b.nominal ORDER BY b.nama_iuran SEPARATOR '||'), '||', 1) as nominal_utama,
                     GROUP_CONCAT(CONCAT(b.nama_iuran, ' (', IFNULL(b.periode_tipe, 'bulanan'), ')') ORDER BY b.nama_iuran SEPARATOR ', ') as daftar_iuran
              FROM santri_iuran sb
              JOIN iuran b ON sb.id_iuran = b.id_iuran
              WHERE sb.is_active = 1
              GROUP BY sb.id_santri
          ) sb_data ON sb_data.id_santri = s.id_santri
          WHERE s.id_santri = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    setFlash('danger', 'Data santri tidak ditemukan!');
    echo "<script>window.location='index.php'</script>";
    exit;
}

$monthly_progress = getSantriMonthlyProgress((int)$id);

// Get payment history
$history = mysqli_query($conn, "SELECT p.*, g.nama as nama_user, b.nama_iuran, b.periode_tipe
                               FROM pembayaran p
                               JOIN users g ON p.id_user = g.id_user
                               LEFT JOIN iuran b ON p.id_iuran = b.id_iuran
                               WHERE p.id_santri = '$id'
                               ORDER BY p.tgl_bayar DESC, p.id_pembayaran DESC");
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user-circle"></i></div>
                        Profil Santri
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <a href="edit.php?id=<?php echo $data['id_santri']; ?>" class="btn btn-sm btn-warning me-1">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="index.php" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <div class="row">
        <!-- Profile Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold text-primary"><?php echo htmlspecialchars($data['nama']); ?></h4>
                    <p class="text-muted mb-1"><?php echo htmlspecialchars($data['nama_kelas']); ?></p>
                    <span class="badge bg-<?php echo $data['status'] == 'active' ? 'success' : 'secondary'; ?> text-white px-3 py-2">
                        <?php echo $data['status'] == 'active' ? 'AKTIF' : 'NONAKTIF'; ?>
                    </span>
                </div>
            </div>

            <!-- Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Personal</h6>
                </div>
                <div class="card-body">
                    <div class="row no-gutters mb-3">
                        <div class="col-5 text-muted small">Tempat Lahir</div>
                        <div class="col-7 font-weight-bold"><?php echo htmlspecialchars($data['tempat_lahir'] ?: '-'); ?></div>
                    </div>
                    <div class="row no-gutters mb-3">
                        <div class="col-5 text-muted small">Tanggal Lahir</div>
                        <div class="col-7 font-weight-bold"><?php echo !empty($data['tanggal_lahir']) ? date('d/m/Y', strtotime($data['tanggal_lahir'])) : '-'; ?></div>
                    </div>
                    <div class="row no-gutters mb-3">
                        <div class="col-5 text-muted small">Periode</div>
                        <div class="col-7 font-weight-bold"><?php echo $data['tahun_iuran']; ?></div>
                    </div>
                    <div class="row no-gutters mb-3">
                        <div class="col-5 text-muted small">Tarif</div>
                        <div class="col-7 font-weight-bold text-success"><?php echo formatRupiah($data['nominal']); ?></div>
                    </div>
                    <div class="row no-gutters mb-3">
                        <div class="col-5 text-muted small">Status SPP Bulanan</div>
                        <div class="col-7">
                            <?php
                            $bg_class_v = $monthly_progress['badge'];
                            $text_class_v = in_array($bg_class_v, ['warning', 'info', 'light']) ? 'text-dark' : 'text-white';
                            ?>
                            <span class="badge bg-<?php echo $bg_class_v; ?> <?php echo $text_class_v; ?> badge-status">
                                <?php echo htmlspecialchars($monthly_progress['status_text']); ?>
                            </span>
                            <div class="small text-muted mt-1">
                                Lunas tahun berjalan: <?php echo (int)$monthly_progress['bulan_lunas_tahun_berjalan']; ?> bulan
                            </div>
                        </div>
                    </div>
                    <div class="row no-gutters">
                        <div class="col-5 text-muted small">Alamat</div>
                        <div class="col-7 font-weight-bold"><?php echo nl2br(htmlspecialchars($data['alamat'] ?: '-')); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
                    <a href="../pembayaran/add.php?id_santri=<?php echo $data['id_santri']; ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus fa-sm"></i> Bayar Tagihan
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" width="100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Iuran</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($history)): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($row['nama_iuran'] ?? '-'); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars(formatPeriodeTagihan($row['periode_tipe'] ?: 'bulanan', $row['bulan_dibayar'], $row['tahun_dibayar'])); ?></small>
                                    </td>
                                    <td class="text-success font-weight-bold"><?php echo formatRupiah($row['jumlah_bayar']); ?></td>

                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php require_once '../../includes/footer.php'; ?>
