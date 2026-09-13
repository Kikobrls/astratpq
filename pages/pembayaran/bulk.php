<?php
/**
 * Pembayaran Kolektif (Bulk Action) dengan Multi Bayar
 * Sistem Administrasi Keuangan Sekolah
 */

$page_title = 'Pembayaran Kolektif';
require_once '../../config/app.php';
require_once '../../config/database.php';
requirePaymentAccess();
require_once '../../includes/header.php';

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$errors = [];
$success = false;
$success_count = 0;
$success_items = 0;


// Variables from GET for Step 1
$filter_kelas_raw = isset($_GET['kelas']) ? sanitize($_GET['kelas']) : '';
$is_all_kelas = ($filter_kelas_raw === 'all' || $filter_kelas_raw === '0');
$filter_kelas = $is_all_kelas ? 0 : (int) $filter_kelas_raw;
$filter_mode = isset($_GET['mode']) ? sanitize($_GET['mode']) : 'bulanan';
$class_scope = paymentClassScope('id_kelas');
$santri_scope = paymentClassScope();
if ($filter_kelas > 0) {
    $allowed_class = mysqli_query($conn, "SELECT id_kelas FROM kelas WHERE id_kelas = $filter_kelas AND $class_scope");
    if (mysqli_num_rows($allowed_class) !== 1) {
        http_response_code(403);
        exit('Kelas ini tidak ditugaskan kepada Anda.');
    }
}

// Handle POST request (Step 2 - Submit Pembayaran)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_bulk'])) {
    if (!paymentCsrfValid()) {
        http_response_code(403);
        exit('Sesi formulir tidak valid. Muat ulang halaman sebelum menyimpan.');
    }
    foreach (['pilih_item', 'bulan_dibayar', 'bulan_akhir', 'tahun_dibayar', 'jumlah_bayar', 'pilih_santri'] as $field) {
        if (isset($_POST[$field]) && (!is_array($_POST[$field]) || count(array_filter($_POST[$field], 'is_array')) > 0)) {
            http_response_code(400);
            exit('Data formulir tidak valid.');
        }
    }
    $tgl_bayar = date('Y-m-d');
    $metode_bayar = 'tunai';
    $keterangan = trim($_POST['keterangan'] ?? '');
    $id_user = (int) $_SESSION['id_user'];

    $pilih_santri = array_values(array_unique(array_map('intval', $_POST['pilih_santri'] ?? [])));
    sort($pilih_santri, SORT_NUMERIC);
    
    // Items data
    $pilih_item = $_POST['pilih_item'] ?? [];
    $bulan_dibayar = $_POST['bulan_dibayar'] ?? [];
    $bulan_akhir_dibayar = $_POST['bulan_akhir'] ?? [];
    $tahun_dibayar = $_POST['tahun_dibayar'] ?? [];
    $jumlah_bayar = $_POST['jumlah_bayar'] ?? [];
    
    // Inherited mode
    $mode = strtolower(sanitize($_POST['mode_pembayaran'] ?? 'tahunan'));

    if (!in_array($mode, ['bulanan', 'tahunan'], true) || $mode !== $filter_mode || (!$is_all_kelas && $filter_kelas <= 0)) {
        $errors[] = 'Pilih kelas dan mode pembayaran yang valid terlebih dahulu.';
    }
    if (empty($pilih_santri)) {
        $errors[] = 'Pilih minimal 1 santri untuk diproses pembayarannya!';
    }
    if (empty($pilih_item)) {
        $errors[] = 'Pilih minimal 1 tagihan/iuran untuk dibayar!';
    }

    $iuran_map = [];
    if (empty($errors)) {
        $r = mysqli_query($conn, "SELECT * FROM iuran");
        while ($row = mysqli_fetch_assoc($r)) {
            $iuran_map[(int)$row['id_iuran']] = $row;
        }
    }

    if (empty($errors)) {
        mysqli_begin_transaction($conn);
        try {
            $count_success_santri = 0;
            $count_success_items = 0;
            
            // Validate all items first
            $detail_items = [];
            foreach ($pilih_item as $id_iuran_raw => $val) {
                $id_iuran = (int) $id_iuran_raw;
                if ($id_iuran <= 0 || !isset($iuran_map[$id_iuran])) {
                    throw new RuntimeException('Iuran tidak valid.');
                }

                $iuran = $iuran_map[$id_iuran];
                $periode_tipe = isset($iuran['periode_tipe']) && $iuran['periode_tipe'] === 'tahunan' ? 'tahunan' : 'bulanan';
                
                if ($periode_tipe !== $mode) {
                    throw new RuntimeException('Iuran tidak sesuai mode pembayaran.');
                }
                $jumlah_raw = str_replace(['.', ','], '', (string) ($jumlah_bayar[$id_iuran] ?? '0'));
                $jumlah = (float) $jumlah_raw;
                if ($jumlah <= 0) {
                    throw new Exception('Jumlah bayar untuk tagihan ' . htmlspecialchars($iuran['nama_iuran']) . ' harus lebih dari 0.');
                }

                $tahun = sanitize($tahun_dibayar[$id_iuran] ?? '');
                if (!preg_match('/^20[0-9]{2}$|^2100$/', $tahun)) {
                    throw new RuntimeException('Tahun pembayaran tidak valid.');
                }
                $bulan_awal = $periode_tipe === 'bulanan' ? sanitize($bulan_dibayar[$id_iuran] ?? '') : '-';
                $bulan_akhir = $periode_tipe === 'bulanan' ? sanitize($bulan_akhir_dibayar[$id_iuran] ?? '') : '';

                $semua_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $bulan_list_process = [];
                
                if ($periode_tipe === 'bulanan' && !empty($bulan_awal)) {
                    $idx_awal = array_search($bulan_awal, $semua_bulan);
                    $idx_akhir = empty($bulan_akhir) ? $idx_awal : array_search($bulan_akhir, $semua_bulan);
                    
                    if ($idx_awal === false || $idx_akhir === false || $idx_akhir < $idx_awal) {
                        throw new RuntimeException('Rentang bulan pembayaran tidak valid.');
                    } else {
                        for ($i = $idx_awal; $i <= $idx_akhir; $i++) {
                            $bulan_list_process[] = $semua_bulan[$i];
                        }
                    }
                } else {
                    $bulan_list_process = ['-'];
                }

                foreach ($bulan_list_process as $bln) {
                    $periode_key = buildPeriodeKey($periode_tipe, $tahun, $bln);
                    if (empty($periode_key)) {
                        throw new Exception('Periode untuk tagihan ' . htmlspecialchars($iuran['nama_iuran']) . ' tidak lengkap.');
                    }

                    $detail_items[] = [
                        'id_iuran' => $id_iuran,
                        'iuran_info' => $iuran,
                        'periode_tipe' => $periode_tipe,
                        'bulan' => $bln,
                        'tahun' => $tahun,
                        'periode_key' => $periode_key,
                        'jumlah' => $jumlah
                    ];
                }
            }

            if (empty($detail_items)) {
                throw new Exception("Tidak ada item tagihan valid.");
            }

            // Execute for each Santri
            foreach ($pilih_santri as $id_santri_raw) {
                $id_santri = (int) $id_santri_raw;
                mysqli_query($conn, "SELECT id_santri FROM santri WHERE id_santri = $id_santri FOR UPDATE");
                if (!paymentSantriAllowed($id_santri)) {
                    throw new RuntimeException('Santri tidak aktif atau kelas santri tidak ditugaskan kepada Anda.');
                }
                if (!$is_all_kelas) {
                    $student = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_kelas FROM santri WHERE id_santri = $id_santri"));
                    if ((int) $student['id_kelas'] !== $filter_kelas) {
                        throw new RuntimeException('Santri tidak termasuk kelas yang dipilih.');
                    }
                }
                $assigned_fees = paymentSantriFees($id_santri);
                
                $santri_processed = false;

                foreach ($detail_items as $item) {
                    $id_iuran = $item['id_iuran'];
                    if (!isset($assigned_fees[$id_iuran])) {
                        throw new RuntimeException("Iuran belum ditetapkan admin untuk santri ID $id_santri. Pilih santri dengan iuran yang sama.");
                    }
                    $periode_key = $item['periode_key'];
                    $jumlah = $item['jumlah'];
                    $nominal = (float) $item['iuran_info']['nominal'];
                    $nama_iuran = $item['iuran_info']['nama_iuran'];

                    // Get already paid amount
                    $sum_q = mysqli_query($conn, "
                        SELECT COALESCE(SUM(jumlah_bayar), 0) as total
                        FROM pembayaran
                        WHERE id_santri = '$id_santri'
                          AND id_iuran = '$id_iuran'
                          AND periode_key = '" . mysqli_real_escape_string($conn, $periode_key) . "'
                    ");
                    $sum_row = mysqli_fetch_assoc($sum_q);
                    $sudah_bayar = (float) $sum_row['total'];
                    
                    if (($sudah_bayar + $jumlah) > $nominal) {
                        // Throw to stop everything, or skip? We throw to prevent partial invalid states.
                        throw new Exception("Pembayaran santri ID $id_santri untuk tagihan $nama_iuran melebihi sisa tagihan maksimal.");
                    }

                    $insert_detail = "INSERT INTO pembayaran (id_user, id_santri, tgl_bayar, periode_tipe, periode_key, bulan_dibayar, tahun_dibayar, id_iuran, jumlah_bayar, metode_bayar, keterangan)
                                      VALUES ('$id_user', '$id_santri', '$tgl_bayar', '" . $item['periode_tipe'] . "', '" . mysqli_real_escape_string($conn, $periode_key) . "', '" . mysqli_real_escape_string($conn, $item['bulan']) . "', '" . mysqli_real_escape_string($conn, $item['tahun']) . "', '$id_iuran', '$jumlah', '$metode_bayar', '" . mysqli_real_escape_string($conn, $keterangan) . "')";
                    if (!mysqli_query($conn, $insert_detail)) {
                        throw new RuntimeException('Gagal menyimpan rincian pembayaran.');
                    }
                    
                    $last_insert_id = mysqli_insert_id($conn);
                    logActivity("Pembayaran kolektif", 'pembayaran', $last_insert_id);
                    
                    $santri_processed = true;
                    $count_success_items++;
                }

                if ($santri_processed) {
                    $count_success_santri++;
                }
            }

            if ($count_success_items == 0) {
                throw new Exception("Tidak ada santri yang berhasil diproses.");
            }

            mysqli_commit($conn);
            $success = true;
            $success_count = $count_success_santri;
            $success_items = $count_success_items;
            
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $errors[] = 'Gagal menyimpan pembayaran kolektif: ' . $e->getMessage();
        }
    }
}

// Prepare data for forms
$kelas_list = mysqli_query($conn, "SELECT * FROM kelas WHERE $class_scope ORDER BY nama_kelas");
$bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

// Get Santri list
$santri_targets = [];
$nama_kelas_terpilih = "";

if ($filter_kelas > 0 || $is_all_kelas) {
    if ($is_all_kelas) {
        $nama_kelas_terpilih = "Semua Kelas";
        $qs = mysqli_query($conn, "
            SELECT s.id_santri, s.nama, s.status, k.nama_kelas
            FROM santri s
            JOIN kelas k ON s.id_kelas = k.id_kelas
            WHERE s.status = 'active' AND $santri_scope
            ORDER BY k.nama_kelas ASC, s.nama ASC
        ");
    } else {
        // Get class name
        $q_k = mysqli_query($conn, "SELECT nama_kelas FROM kelas WHERE id_kelas = '$filter_kelas'");
        if ($q_k && mysqli_num_rows($q_k) > 0) {
            $k_row = mysqli_fetch_assoc($q_k);
            $nama_kelas_terpilih = $k_row['nama_kelas'];
        }

        $qs = mysqli_query($conn, "
            SELECT id_santri, nama, status
            FROM santri
            WHERE id_kelas = '$filter_kelas' AND status = 'active' AND $class_scope
            ORDER BY nama ASC
        ");
    }

    if ($qs) {
        while ($row = mysqli_fetch_assoc($qs)) {
            $santri_targets[] = $row;
        }
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
                        <div class="page-header-icon"><i class="fas fa-layer-group"></i></div>
                        Pembayaran Kolektif
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <a href="index.php" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-xl px-4 mt-4">
    <?php paymentScopeNotice(); ?>

    <?php if (!empty($errors)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: <?php echo json_encode(implode("\n", $errors), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
                showConfirmButton: true
            });
        </script>
    <?php endif; ?>

    <?php if ($success): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                html: '<p>Pembayaran kolektif multi-tagihan berhasil disimpan untuk <strong><?php echo (int) $success_count; ?></strong> santri.</p><p>Jumlah Item Transaksi Tersimpan: <strong><?php echo (int) $success_items; ?></strong></p>',
                showCancelButton: false,
                confirmButtonText: 'Kembali ke Daftar',
                confirmButtonColor: '#3085d6'
            }).then((result) => {
                window.location.href = 'index.php';
            });
        </script>
    <?php endif; ?>

    <!-- STEP 1: Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Langkah 1: Pilih Kelas & Mode</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="">
                <div class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label>Pilih Kelas <span class="text-danger">*</span></label>
                        <select name="kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="all" <?php echo $is_all_kelas ? 'selected' : ''; ?>>Semua Kelas yang Diizinkan</option>
                            <?php while ($kls = mysqli_fetch_assoc($kelas_list)): ?>
                                <option value="<?php echo $kls['id_kelas']; ?>" <?php echo $filter_kelas == $kls['id_kelas'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($kls['nama_kelas']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Mode Pembayaran <span class="text-danger">*</span></label>
                        <select name="mode" class="form-control" required>
                            <option value="bulanan" <?php echo $filter_mode === 'bulanan' ? 'selected' : ''; ?>>Bulanan</option>
                            <option value="tahunan" <?php echo $filter_mode === 'tahunan' ? 'selected' : ''; ?>>Tahunan</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Tampilkan Form
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- STEP 2: Santri Selection and Payment Input Form -->
    <?php if ($filter_kelas > 0 || $is_all_kelas): ?>
        <div class="card shadow mb-4 border-bottom-primary">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Langkah 2: Lengkapi Pembayaran Kelas <?php echo htmlspecialchars($nama_kelas_terpilih); ?></h6>
            </div>
            <div class="card-body">
                <?php if (empty($santri_targets)): ?>
                    <div class="alert alert-warning text-center">
                        Tidak ada data santri aktif untuk Kelas ini.
                    </div>
                <?php else: ?>
                    <form method="POST" action="" id="formBulkPayment">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(paymentCsrfToken()); ?>">
                        <input type="hidden" name="mode_pembayaran" value="<?php echo htmlspecialchars($filter_mode); ?>">

                        <div class="row">
                            <!-- Left Col: Pengaturan Tagihan -->
                            <div class="col-lg-6 mb-4">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="font-weight-bold mb-3"><i class="fas fa-list-check mr-2"></i>Pilih Tagihan (Harus aktif untuk setiap santri terpilih)</h6>
                                        <div id="tagihanContainer" class="mb-3">
                                            <div class="text-center text-muted">Akan memuat tagihan...</div>
                                        </div>

                                        <hr>


                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Keterangan (Massal)</label>
                                            <textarea name="keterangan" class="form-control form-control-sm" rows="2" placeholder="Opsional"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Col: Santri Checklist -->
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                            <table class="table table-bordered table-hover mb-0" id="bulkTable" width="100%" cellspacing="0">
                                                <thead class="bg-primary text-white" style="position: sticky; top: 0; z-index: 10;">
                                                    <tr>
                                                        <th style="width: 50px;" class="text-center">
                                                                <input type="checkbox" id="checkAllSantri">
                                                        </th>
                                                        <th style="width: 50px;">No</th>
                                                        <th>Nama Santri</th>
                                                        <?php if ($is_all_kelas): ?>
                                                            <th>Kelas</th>
                                                        <?php endif; ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $no = 1;
                                                    foreach ($santri_targets as $santri): 
                                                    ?>
                                                        <tr>
                                                            <td class="text-center">
                                                                <input type="checkbox" class="santri-check" name="pilih_santri[]" value="<?php echo $santri['id_santri']; ?>">
                                                            </td>
                                                            <td><?php echo $no++; ?></td>
                                                            <td class="font-weight-bold"><?php echo htmlspecialchars($santri['nama']); ?></td>
                                                            <?php if ($is_all_kelas): ?>
                                                                <td><?php echo htmlspecialchars($santri['nama_kelas'] ?? '-'); ?></td>
                                                            <?php endif; ?>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white text-muted small text-center border-top-0">
                                        Hanya santri yang dicentang yang akan diproses transaksinya. Pengecekan lunas tidak bisa dilakukan massal karena Sisa Terutang setiap siswa berbeda bergantung pada riwayat masing-masing.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-2 text-right">
                            <button type="submit" name="submit_bulk" class="btn btn-success btn-lg shadow">
                                <i class="fas fa-save mr-2"></i> Simpan Pembayaran Massal
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php
$extra_js = '
<script>
    const filterKelas = "' . addslashes($filter_kelas_raw) . '";
    const filterMode = "' . htmlspecialchars($filter_mode) . '";
    const bulanList = ' . json_encode($bulan_list) . ';

    function buildBulanOptions(selected) {
        let html = "<option value=\"\">-- Pilih Bulan --</option>";
        bulanList.forEach((bulan) => {
            const selectedAttr = selected === bulan ? "selected" : "";
            html += `<option value="${bulan}" ${selectedAttr}>${bulan}</option>`;
        });
        return html;
    }

    function formatRupiahJs(number) {
        return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", minimumFractionDigits: 0 }).format(number || 0);
    }

    function formatCurrency(element) {
        let value = element.value.replace(/[^,\d]/g, \'\');
        let parts = value.split(\',\');
        let sisa = parts[0].length % 3;
        let rupiah = parts[0].substr(0, sisa);
        let ribuan = parts[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? \'.\' : \'\';
            rupiah += separator + ribuan.join(\'.\');
        }

        rupiah = parts[1] !== undefined ? rupiah + \',\' + parts[1] : rupiah;
        element.value = rupiah;
    }

    function renderTagihan(items, mode) {
        if (!items || !items.length) {
            const targetText = (filterKelas === "all" || filterKelas === "0") ? "semua kelas" : "kelas ini";
            const msg = mode === "bulanan"
                ? `Tagihan bulanan aktif tidak ditemukan untuk ${targetText}.`
                : `Tagihan tahunan aktif tidak ditemukan untuk ${targetText}.`;
            document.getElementById("tagihanContainer").innerHTML = `<div class="text-danger small">${msg}</div>`;
            return;
        }

        const currentYear = new Date().getFullYear();
        const isBulanan = mode === "bulanan";

        const html = items.map((item) => {
            const disabledAttr = "";
            const defaultAmount = Math.max(item.nominal || 0, 0);

            return `
                <div class="card mb-2 border-left-info shadow-sm">
                    <div class="card-body p-2">
                        <div class="custom-control custom-checkbox mb-1">
                            <input type="checkbox" class="custom-control-input item-check" id="check_${item.id_iuran}" name="pilih_item[${item.id_iuran}]" value="1" data-id="${item.id_iuran}" checked>
                            <label class="custom-control-label font-weight-bold text-dark" for="check_${item.id_iuran}">
                                ${item.nama_iuran}
                            </label>
                        </div>
                        <div class="small text-muted mb-2 ml-4">Nominal Tagihan: ${formatRupiahJs(item.nominal)}</div>

                        <div class="pl-4">
                            <div class="form-row">
                                ${isBulanan ? `
                                <div class="col-4 mb-1">
                                    <label class="sr-only">Bulan Mulai</label>
                                    <select class="form-control form-control-sm item-field" name="bulan_dibayar[${item.id_iuran}]" data-item="${item.id_iuran}" ${disabledAttr}>
                                        ${buildBulanOptions(item.default_bulan || "")}
                                    </select>
                                </div>
                                <div class="col-4 mb-1">
                                    <label class="sr-only">s/d Akhir</label>
                                    <select class="form-control form-control-sm item-field" name="bulan_akhir[${item.id_iuran}]" data-item="${item.id_iuran}" ${disabledAttr}>
                                        <option value="">-- Sama --</option>
                                        ${buildBulanOptions("")}
                                    </select>
                                </div>` : ""}

                                <div class="${isBulanan ? "col-4" : "col-4"} mb-1">
                                    <label class="sr-only">Tahun</label>
                                    <input type="number" class="form-control form-control-sm item-field" name="tahun_dibayar[${item.id_iuran}]" placeholder="Tahun" value="${item.default_tahun || currentYear}" min="2020" max="2100" data-item="${item.id_iuran}" ${disabledAttr}>
                                </div>

                                <div class="col-12 mt-1 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend"><span class="input-group-text bg-light text-dark font-weight-bold" style="font-size: 0.75rem;">Nominal per BIn/SSW</span><span class="input-group-text">Rp</span></div>
                                        <input type="text" class="form-control item-field" name="jumlah_bayar[${item.id_iuran}]" value="${new Intl.NumberFormat("id-ID").format(defaultAmount)}" data-item="${item.id_iuran}" onkeyup="formatCurrency(this)" ${disabledAttr}>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join("");

        document.getElementById("tagihanContainer").innerHTML = html;

        document.querySelectorAll(".item-check").forEach((checkbox) => {
            checkbox.addEventListener("change", function() {
                const id = this.getAttribute("data-id");
                document.querySelectorAll(`.item-field[data-item="${id}"]`).forEach((field) => {
                    field.disabled = !this.checked;
                    if (this.checked && (field.name || "").includes("jumlah_bayar")) {
                        // field.focus(); // can be annoying in bulk
                    }
                });
            });
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        if (filterKelas && filterKelas !== "") {
            const kelasParam = (filterKelas === "all" || filterKelas === "0") ? "all" : parseInt(filterKelas || "0");
            if (kelasParam > 0 || kelasParam === "all") {
                fetch("../../api/get_kelas_iuran.php?id_kelas=" + kelasParam + "&mode=" + filterMode)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success" && Array.isArray(data.data)) {
                            renderTagihan(data.data, filterMode);
                        } else {
                            document.getElementById("tagihanContainer").innerHTML = `<div class="text-danger small">${data.message || "Gagal memuat tagihan."}</div>`;
                        }
                    })
                    .catch(() => {
                        document.getElementById("tagihanContainer").innerHTML = "<div class=\"text-danger small\">Gagal memuat informasi tagihan kelas.</div>";
                    });
            }
        }

        const checkAllSantri = document.getElementById("checkAllSantri");
        const santriChecks = document.querySelectorAll(".santri-check");
        
        if(checkAllSantri && santriChecks.length > 0) {
            checkAllSantri.addEventListener("change", function() {
                santriChecks.forEach(function(checkbox) {
                    checkbox.checked = checkAllSantri.checked;
                });
            });
            
            santriChecks.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    checkAllSantri.checked = Array.from(santriChecks).every(c => c.checked);
                });
            });
        }
        
        const formBulk = document.getElementById("formBulkPayment");
        if(formBulk) {
            formBulk.addEventListener("submit", function(e) {
                const checkedSantri = document.querySelectorAll(".santri-check:checked").length;
                if (checkedSantri === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Pilih Santri",
                        text: "Silakan centang minimal 1 santri untuk diproses!"
                    });
                    return;
                }

                const checkedItems = document.querySelectorAll(".item-check:checked").length;
                if (checkedItems === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Pilih Tagihan",
                        text: "Silakan centang minimal 1 tagihan/iuran di sebelah kiri!"
                    });
                    return;
                }
            });
        }
    });
</script>
';

require_once '../../includes/footer.php';
?>
