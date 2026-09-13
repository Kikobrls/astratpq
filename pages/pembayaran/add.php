<?php
/**
 * Input Pembayaran (Bulanan/Tahunan)
 * Sistem Administrasi Keuangan Sekolah
 */

$page_title = 'Input Pembayaran';
require_once '../../config/app.php';
require_once '../../config/database.php';
requirePaymentAccess();
require_once '../../includes/header.php';

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

$errors = [];
$success = false;
$success_info = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_santri = (int) ($_POST['id_santri'] ?? 0);
    if (!paymentCsrfValid()) {
        http_response_code(403);
        exit('Sesi formulir tidak valid. Muat ulang halaman sebelum menyimpan.');
    }
    foreach (['pilih_item', 'bulan_dibayar', 'bulan_akhir', 'tahun_dibayar', 'jumlah_bayar'] as $field) {
        if (isset($_POST[$field]) && (!is_array($_POST[$field]) || count(array_filter($_POST[$field], 'is_array')) > 0)) {
            http_response_code(400);
            exit('Data formulir tidak valid.');
        }
    }
    $tgl_bayar = date('Y-m-d');
    $mode = strtolower(sanitize($_POST['mode'] ?? 'tahunan'));
    if ($mode !== 'bulanan' && $mode !== 'tahunan') {
        $mode = 'tahunan';
    }
    $metode_bayar = 'tunai';
    $keterangan = trim($_POST['keterangan'] ?? '');
    $id_user = (int) $_SESSION['id_user'];

    $pilih_item = $_POST['pilih_item'] ?? [];
    $bulan_dibayar = $_POST['bulan_dibayar'] ?? [];
    $bulan_akhir_dibayar = $_POST['bulan_akhir'] ?? [];
    $tahun_dibayar = $_POST['tahun_dibayar'] ?? [];
    $jumlah_bayar = $_POST['jumlah_bayar'] ?? [];


    if ($id_santri <= 0)
        $errors[] = 'Santri harus dipilih!';

    if (empty($pilih_item)) {
        $errors[] = 'Pilih minimal 1 tagihan untuk dibayar!';
    }

    if (!paymentSantriAllowed($id_santri)) {
        $errors[] = 'Santri tidak aktif atau kelas santri tidak ditugaskan kepada Anda.';
    }
    $iuran_map = empty($errors) ? paymentSantriFees($id_santri) : [];

    $detail_items = [];
    $grand_total = 0;

    if (empty($errors)) {
        foreach ($pilih_item as $id_iuran_raw => $val) {
            $id_iuran = (int) $id_iuran_raw;
            if ($id_iuran <= 0 || !isset($iuran_map[$id_iuran])) {
                $errors[] = 'Tagihan tidak valid.';
                continue;
            }

            $iuran = $iuran_map[$id_iuran];
            $periode_tipe = $iuran['periode_tipe'] === 'tahunan' ? 'tahunan' : 'bulanan';
            if ($periode_tipe !== $mode) {
                $errors[] = 'Tagihan ' . $iuran['nama_iuran'] . ' tidak sesuai mode pembayaran saat ini.';
                continue;
            }
            $tahun = sanitize($tahun_dibayar[$id_iuran] ?? '');
            $bulan_awal = $mode === 'bulanan' ? sanitize($bulan_dibayar[$id_iuran] ?? '') : '-';
            $bulan_akhir = $mode === 'bulanan' ? sanitize($bulan_akhir_dibayar[$id_iuran] ?? '') : '';

            $jumlah_raw = str_replace(['.', ','], '', (string) ($jumlah_bayar[$id_iuran] ?? '0'));
            $jumlah = (float) $jumlah_raw;

            if ($jumlah <= 0) {
                $errors[] = 'Jumlah bayar untuk ' . $iuran['nama_iuran'] . ' harus lebih dari 0.';
                continue;
            }

            if (!preg_match('/^20[0-9]{2}$|^2100$/', $tahun)) {
                $errors[] = 'Tahun dibayar untuk ' . $iuran['nama_iuran'] . ' harus diisi.';
                continue;
            }

            $semua_bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $bulan_list_process = [];
            
            if ($mode === 'bulanan' && !empty($bulan_awal)) {
                $idx_awal = array_search($bulan_awal, $semua_bulan);
                $idx_akhir = empty($bulan_akhir) ? $idx_awal : array_search($bulan_akhir, $semua_bulan);
                
                if ($idx_awal === false || $idx_akhir === false || $idx_akhir < $idx_awal) {
                    $errors[] = 'Rentang bulan pembayaran tidak valid.';
                    continue;
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
                    $errors[] = 'Periode pembayaran untuk ' . $iuran['nama_iuran'] . ' tidak valid.';
                    continue;
                }

                $sum_q = mysqli_query($conn, "
                    SELECT COALESCE(SUM(jumlah_bayar), 0) as total
                    FROM pembayaran
                    WHERE id_santri = '$id_santri'
                      AND id_iuran = '$id_iuran'
                      AND periode_key = '" . mysqli_real_escape_string($conn, $periode_key) . "'
                ");
                $sum_row = mysqli_fetch_assoc($sum_q);
                $sudah_bayar = (float) $sum_row['total'];
                $nominal = (float) $iuran['nominal'];

                if (($sudah_bayar + $jumlah) > $nominal) {
                    $nama_bln = $bln !== '-' ? " ($bln)" : "";
                    $errors[] = 'Pembayaran ' . $iuran['nama_iuran'] . $nama_bln . ' melebihi nominal periode. Sisa maksimal: ' . formatRupiah($nominal - $sudah_bayar) . '.';
                    continue;
                }

                $detail_items[] = [
                    'id_iuran' => $id_iuran,
                    'nama_iuran' => $iuran['nama_iuran'],
                    'periode_tipe' => $periode_tipe,
                    'bulan_dibayar' => $bln,
                    'tahun_dibayar' => $tahun,
                    'periode_key' => $periode_key,
                    'jumlah_bayar' => $jumlah,
                    'keterangan' => $keterangan,
                ];

                $grand_total += $jumlah;
            }
        }
    }

    if (empty($errors) && !empty($detail_items)) {
        mysqli_begin_transaction($conn);
        try {

            // Serialize payments for this santri, then recheck scope and balance.
            mysqli_query($conn, "SELECT id_santri FROM santri WHERE id_santri = $id_santri FOR UPDATE");
            $current_fees = paymentSantriFees($id_santri);
            $last_insert_id = 0;
            foreach ($detail_items as $item) {
                $fee_id = (int) $item['id_iuran'];
                if (!isset($current_fees[$fee_id]) || $current_fees[$fee_id]['periode_tipe'] !== $item['periode_tipe']) {
                    throw new RuntimeException('Penugasan kelas atau iuran telah berubah. Muat ulang halaman.');
                }
                $period_key = mysqli_real_escape_string($conn, $item['periode_key']);
                $paid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_bayar), 0) AS total FROM pembayaran
                    WHERE id_santri = $id_santri AND id_iuran = $fee_id AND periode_key = '$period_key'"));
                if ((float) $paid['total'] + $item['jumlah_bayar'] > (float) $current_fees[$fee_id]['nominal']) {
                    throw new RuntimeException('Pembayaran melebihi sisa tagihan. Muat ulang halaman.');
                }
                $insert_detail = "INSERT INTO pembayaran (id_user, id_santri, tgl_bayar, periode_tipe, periode_key, bulan_dibayar, tahun_dibayar, id_iuran, jumlah_bayar, metode_bayar, keterangan)
                                  VALUES ('$id_user', '$id_santri', '$tgl_bayar', '" . $item['periode_tipe'] . "', '" . mysqli_real_escape_string($conn, $item['periode_key']) . "', '" . mysqli_real_escape_string($conn, $item['bulan_dibayar']) . "', '" . mysqli_real_escape_string($conn, $item['tahun_dibayar']) . "', '" . $item['id_iuran'] . "', '" . $item['jumlah_bayar'] . "', '$metode_bayar', '" . mysqli_real_escape_string($conn, $item['keterangan']) . "')";
                if (!mysqli_query($conn, $insert_detail)) {
                    throw new RuntimeException('Gagal menyimpan rincian pembayaran.');
                }
                $last_insert_id = mysqli_insert_id($conn);
            }

            mysqli_commit($conn);

            logActivity('Input pembayaran', 'pembayaran', $last_insert_id);
            $santri_info = mysqli_fetch_assoc(mysqli_query($conn, "
                SELECT s.nama, k.nama_kelas
                FROM santri s
                JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE s.id_santri = '$id_santri'
            "));
            $success_info = [
                'nama' => $santri_info['nama'] ?? '-',
                'nama_kelas' => $santri_info['nama_kelas'] ?? '-',
                'jumlah_item' => count($detail_items),
                'total' => $grand_total
            ];

            $success = true;
        } catch (Throwable $e) {
            mysqli_rollback($conn);
            $errors[] = 'Gagal menyimpan pembayaran: ' . $e->getMessage();
        }
    }
}

// Get classes
$class_scope = paymentClassScope('id_kelas');
$santri_scope = paymentClassScope();
$kelas_list = mysqli_query($conn, "SELECT id_kelas, nama_kelas FROM kelas WHERE $class_scope ORDER BY nama_kelas");

// Get students
$siswa_list = mysqli_query($conn, "
    SELECT s.id_santri, s.nama, s.id_kelas, k.nama_kelas
    FROM santri s
    JOIN kelas k ON s.id_kelas = k.id_kelas
    WHERE s.status = 'active' AND $santri_scope
    ORDER BY k.nama_kelas, s.nama
");

// Pre-select student if passed via GET
$selected_id = isset($_GET['id_santri']) ? sanitize($_GET['id_santri']) : '';
$selected_kelas = isset($_GET['id_kelas']) ? (int) $_GET['id_kelas'] : 0;

$bulan_list = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-plus-circle"></i></div>
                        Input Pembayaran
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

    <?php if ($success && $success_info): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil!',
                html: '<p>Pembayaran untuk <strong><?php echo addslashes(htmlspecialchars($success_info['nama'])); ?></strong> (<?php echo addslashes(htmlspecialchars($success_info['nama_kelas'])); ?>) berhasil disimpan.</p><p>Jumlah item: <strong><?php echo (int) $success_info['jumlah_item']; ?></strong></p><p>Total: <strong><?php echo addslashes(formatRupiah($success_info['total'])); ?></strong></p><p class="text-muted">Bukti pembayaran bisa dicetak per item di riwayat pembayaran.</p>',
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3085d6'
            });
        </script>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Pembayaran</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="" id="formPembayaranBatch">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(paymentCsrfToken()); ?>">
                        <div class="form-group">
                            <label>Mode Pembayaran <span class="text-danger">*</span></label>
                            <select name="mode" id="modeSelect" class="form-control" required>
                                <option value="tahunan">Tahunan</option>
                                <option value="bulanan">Bulanan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Kelas</label>
                            <select id="kelasSelect" class="form-control">
                                <option value="">-- Semua Kelas yang Diizinkan --</option>
                                <?php while ($kls = mysqli_fetch_assoc($kelas_list)): ?>
                                    <option value="<?php echo $kls['id_kelas']; ?>" <?php echo $selected_kelas == $kls['id_kelas'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($kls['nama_kelas']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Pilih Santri <span class="text-danger">*</span></label>
                            <select name="id_santri" id="siswaSelect" class="form-control" required>
                                <option value="">-- Pilih Santri --</option>
                                <?php while ($siswa = mysqli_fetch_assoc($siswa_list)): ?>
                                    <option value="<?php echo $siswa['id_santri']; ?>"
                                        data-kelas="<?php echo htmlspecialchars($siswa['nama_kelas']); ?>"
                                        data-id-kelas="<?php echo $siswa['id_kelas']; ?>" <?php echo $selected_id == $siswa['id_santri'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($siswa['nama']); ?> -
                                        <?php echo htmlspecialchars($siswa['nama_kelas']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tagihan Aktif <span class="text-danger">*</span></label>
                            <div id="tagihanContainer" class="border rounded p-3 bg-light">
                                <div class="text-muted">Pilih santri terlebih dahulu untuk menampilkan tagihan.</div>
                            </div>
                        </div>



                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional"></textarea>
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pembayaran
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Info Santri</h6>
                </div>
                <div class="card-body" id="infoSiswa">
                    <p class="text-muted text-center">Pilih santri untuk melihat informasi</p>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Hari Ini</h6>
                </div>
                <div class="card-body">
                    <?php
                    $todayCountQuery = "SELECT COUNT(*) as jumlah, COALESCE(SUM(jumlah_bayar), 0) as total FROM pembayaran p JOIN santri s ON s.id_santri = p.id_santri WHERE DATE(p.tgl_bayar) = CURDATE() AND $santri_scope";
                    $today = mysqli_fetch_assoc(mysqli_query($conn, $todayCountQuery));
                    ?>
                    <p class="mb-1"><strong>Transaksi:</strong> <?php echo $today['jumlah']; ?></p>
                    <p class="mb-0"><strong>Total:</strong> <?php echo formatRupiah($today['total']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$extra_js = '
<script>
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
        const msg = mode === "bulanan" ? "Tagihan bulanan aktif tidak ditemukan." : "Tagihan tahunan aktif tidak ditemukan.";
        document.getElementById("tagihanContainer").innerHTML = `<div class="text-danger">${msg}</div>`;
        return;
    }

    const currentYear = new Date().getFullYear();
    const isBulanan = mode === "bulanan";

    const html = items.map((item) => {
        const disabledAttr = "disabled";
        const defaultAmount = Math.max(item.sisa_tagihan || 0, 0);

        return `
            <div class="card mb-3 border-left-primary">
                <div class="card-body py-3">
                    <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input item-check" id="check_${item.id_iuran}" name="pilih_item[${item.id_iuran}]" value="1" data-id="${item.id_iuran}">
                        <label class="custom-control-label font-weight-bold" for="check_${item.id_iuran}">
                            ${item.nama_iuran} (${isBulanan ? "Bulanan" : "Tahunan"})
                        </label>
                    </div>
                    <div class="small text-muted mb-2">Nominal tagihan: ${formatRupiahJs(item.nominal)} | Sudah bayar periode default: ${formatRupiahJs(item.sudah_bayar)} | Sisa: ${formatRupiahJs(item.sisa_tagihan)}</div>

                    <input type="hidden" name="periode_tipe[${item.id_iuran}]" value="${item.periode_tipe}">
                    ${isBulanan ? "" : `<input type="hidden" name="bulan_dibayar[${item.id_iuran}]" value="-">`}

                    <div class="form-row">
                        ${isBulanan ? `
                        <div class="col-md-3 mb-2">
                            <label class="small mb-1">Bulan Mulai</label>
                            <select class="form-control form-control-sm item-field" name="bulan_dibayar[${item.id_iuran}]" data-item="${item.id_iuran}" ${disabledAttr}>
                                ${buildBulanOptions(item.default_bulan || "")}
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="small mb-1">s/d Akhir</label>
                            <select class="form-control form-control-sm item-field" name="bulan_akhir[${item.id_iuran}]" data-item="${item.id_iuran}" ${disabledAttr}>
                                <option value="">-- Sama --</option>
                                ${buildBulanOptions("")}
                            </select>
                        </div>` : ""}

                        <div class="${isBulanan ? "col-md-2" : "col-md-4"} mb-2">
                            <label class="small mb-1">Tahun</label>
                            <input type="number" class="form-control form-control-sm item-field" name="tahun_dibayar[${item.id_iuran}]" value="${item.default_tahun || currentYear}" min="2020" max="2100" data-item="${item.id_iuran}" ${disabledAttr}>
                        </div>

                        <div class="${isBulanan ? "col-md-4" : "col-md-8"} mb-2">
                            <label class="small mb-1">Jml/Bln</label>
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                <input type="text" class="form-control item-field" name="jumlah_bayar[${item.id_iuran}]" value="${new Intl.NumberFormat("id-ID").format(defaultAmount)}" data-item="${item.id_iuran}" onkeyup="formatCurrency(this)" ${disabledAttr}>
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
                    field.focus();
                }
            });
        });
    });
}

function loadTagihan() {
    const siswaSelect = document.getElementById("siswaSelect");
    const mode = document.getElementById("modeSelect").value || "tahunan";
    const id_santri = siswaSelect.value;
    const option = siswaSelect.options[siswaSelect.selectedIndex];
    const kelas = option ? option.getAttribute("data-kelas") : "";
    const nama = option ? (option.text.split(" - ")[0] || "") : "";

    if (!id_santri) {
        document.getElementById("infoSiswa").innerHTML = "<p class=\"text-muted text-center\">Pilih santri untuk melihat informasi</p>";
        document.getElementById("tagihanContainer").innerHTML = "<div class=\"text-muted\">Pilih santri terlebih dahulu untuk menampilkan tagihan.</div>";
        return;
    }

    document.getElementById("infoSiswa").innerHTML = `<p><strong>Nama:</strong> ${nama}</p><p><strong>Kelas:</strong> ${kelas || "-"}</p>`;

    fetch("../../api/get_santri_iuran.php?id=" + encodeURIComponent(id_santri) + "&mode=" + encodeURIComponent(mode))
        .then(response => response.json())
        .then(data => {
            if (data.status === "success" && Array.isArray(data.data)) {
                renderTagihan(data.data, mode);
            } else {
                document.getElementById("tagihanContainer").innerHTML = `<div class="text-danger">${data.message || "Gagal memuat tagihan."}</div>`;
            }
        })
        .catch(() => {
            document.getElementById("tagihanContainer").innerHTML = "<div class=\"text-danger\">Gagal memuat informasi tagihan.</div>";
        });
}

function filterSantriByKelas() {
    const kelasSelect = document.getElementById("kelasSelect");
    const siswaSelect = document.getElementById("siswaSelect");
    const idKelas = kelasSelect.value;
    let currentStillVisible = false;

    Array.from(siswaSelect.options).forEach((opt) => {
        if (!opt.value) return;
        const match = !idKelas || opt.getAttribute("data-id-kelas") === idKelas;
        opt.hidden = !match;
        opt.disabled = !match;
        if (match && opt.value === siswaSelect.value) {
            currentStillVisible = true;
        }
    });

    if (!currentStillVisible) {
        siswaSelect.value = "";
        loadTagihan();
    }
}

document.getElementById("kelasSelect").addEventListener("change", filterSantriByKelas);
document.getElementById("siswaSelect").addEventListener("change", loadTagihan);
document.getElementById("modeSelect").addEventListener("change", loadTagihan);

document.getElementById("formPembayaranBatch").addEventListener("submit", function(e) {
    const checked = document.querySelectorAll(".item-check:checked").length;
    if (checked === 0) {
        e.preventDefault();
        Swal.fire({
            icon: "warning",
            title: "Pilih Tagihan",
            text: "Pilih minimal 1 item tagihan yang akan dibayar."
        });
    }
});

filterSantriByKelas();

if (document.getElementById("siswaSelect").value) {
    loadTagihan();
}
</script>
';

require_once '../../includes/footer.php';
?>
