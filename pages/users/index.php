<?php
/**
 * Data Pengguna (CRUD)
 * Sistem Keuangan TPQ
 */

// Start session and require config FIRST
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

// Only admin can manage users
if (paymentUserRole() !== 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

$has_class_assignments = appTableExists('bendahara_kelas');

// Handle delete - BEFORE any HTML output
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    if ($id === (int) ($_SESSION['id_user'] ?? 0)) {
        setFlash('danger', 'Anda tidak dapat menghapus akun yang sedang digunakan!');
    } else {
        if (mysqli_query($conn, "DELETE FROM users WHERE id_user = '$id'")) {
            logActivity('Menghapus data pengguna', 'users', $id);
            setFlash('success', 'Data pengguna berhasil dihapus!');
        } else {
            setFlash('danger', 'Gagal menghapus data pengguna!');
        }
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit - BEFORE any HTML output
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!paymentCsrfValid()) {
        redirect('index.php', 'danger', 'Sesi formulir tidak valid. Silakan coba lagi.');
    }
    $id_user = isset($_POST['id_user']) ? (int) $_POST['id_user'] : 0;
    $username = sanitize($_POST['username'] ?? '');
    $nama = sanitize($_POST['nama'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $no_telp = sanitize($_POST['no_telp'] ?? '');
    $level = sanitize($_POST['level'] ?? 'bendahara');
    $status = sanitize($_POST['status'] ?? 'active');
    $password = $_POST['password'] ?? '';

    $allowed_levels = ['admin', 'bendahara', 'kepala_tpq'];
    if (!in_array($level, $allowed_levels, true)) {
        $level = 'bendahara';
    }
    if ($status !== 'active' && $status !== 'inactive') {
        $status = 'active';
    }

    $kelas_ids = [];
    if ($level === 'bendahara') {
        if (!$has_class_assignments) {
            redirect('index.php', 'danger', 'Jalankan migrasi 2026_09_13_add_bendahara_kelas.sql terlebih dahulu.');
        }
        $posted_kelas = $_POST['kelas_ids'] ?? [];
        if (!is_array($posted_kelas)) {
            redirect('index.php', 'danger', 'Pilihan kelas tidak valid.');
        }
        foreach ($posted_kelas as $value) {
            if (!is_scalar($value) || !ctype_digit((string) $value) || (int) $value <= 0) {
                redirect('index.php', 'danger', 'Pilihan kelas tidak valid.');
            }
            $kelas_ids[] = (int) $value;
        }
        $kelas_ids = array_values(array_unique($kelas_ids));
        if ($kelas_ids) {
            $ids = implode(',', $kelas_ids);
            $found = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kelas WHERE id_kelas IN ($ids)"));
            if ((int) $found['total'] !== count($kelas_ids)) {
                redirect('index.php', 'danger', 'Kelas tidak ditemukan. Muat ulang halaman.');
            }
        }
    }

    if (empty($username) || empty($nama)) {
        setFlash('danger', 'Username dan nama harus diisi!');
    } elseif ($id_user <= 0 && strlen($password) < 6) {
        setFlash('danger', 'Password minimal 6 karakter!');
    } else {
        // Unique username check
        $dup = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE username = '$username' AND id_user != '$id_user'"));
        if ($dup['total'] > 0) {
            setFlash('danger', 'Username sudah digunakan!');
        } else {
            if ($id_user <= 0) {
                // Add new
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (username, password, nama, email, no_telp, level, status)
                          VALUES ('$username', '$hash', '$nama', '$email', '$no_telp', '$level', '$status')";
                $msg = 'ditambahkan';
            } else {
                // Edit
                $query = "UPDATE users SET
                            username = '$username',
                            nama = '$nama',
                            email = '$email',
                            no_telp = '$no_telp',
                            level = '$level',
                            status = '$status'";
                if ($password !== '') {
                    if (strlen($password) < 6) {
                        setFlash('danger', 'Password baru minimal 6 karakter!');
                        header("Location: index.php");
                        exit;
                    }
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $query .= ", password = '$hash'";
                }
                $query .= " WHERE id_user = '$id_user'";
                $msg = 'diubah';
            }

            mysqli_begin_transaction($conn);
            try {
                if (!mysqli_query($conn, $query)) {
                    throw new RuntimeException('Gagal menyimpan pengguna.');
                }
                $saved_id = $id_user ?: (int) mysqli_insert_id($conn);
                if ($has_class_assignments) {
                    if (!mysqli_query($conn, "DELETE FROM bendahara_kelas WHERE id_user = $saved_id")) {
                        throw new RuntimeException('Gagal memperbarui penugasan.');
                    }
                    foreach ($kelas_ids as $kelas_id) {
                        if (!mysqli_query($conn, "INSERT INTO bendahara_kelas (id_user, id_kelas) VALUES ($saved_id, $kelas_id)")) {
                            throw new RuntimeException('Gagal menyimpan penugasan.');
                        }
                    }
                }
                logActivity("Menyimpan pengguna dan penugasan kelas ($msg)", 'users', $saved_id);
                mysqli_commit($conn);
                setFlash('success', "Data pengguna berhasil $msg!" . ($level === 'bendahara' && !$kelas_ids ? ' Bendahara belum dapat input pembayaran sampai kelas ditugaskan.' : ''));
            } catch (Throwable $e) {
                mysqli_rollback($conn);
                setFlash('danger', 'Gagal menyimpan pengguna dan kelas. Tidak ada perubahan yang disimpan.');
            }
        }
    }
    header("Location: index.php");
    exit;
}

$kelas_options = mysqli_query($conn, 'SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas');

// NOW include header/sidebar - AFTER redirect logic
$page_title = 'Data Pengguna';
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
                        <div class="page-header-icon"><i class="fas fa-user-check"></i></div>
                        Data Pengguna
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Pengguna
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">

    <?php if (!$has_class_assignments): ?>
        <div class="alert alert-warning">Pengaturan kelas belum siap. Jalankan file
            <code>migrations/2026_09_13_add_bendahara_kelas.sql</code> melalui phpMyAdmin terlebih dahulu.</div>
    <?php endif; ?>
    <!-- DataTales -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna</h6>
        </div>
        <div class="card-body">
            <div class="dt-loading-wrap">
                <div class="dt-loading-overlay" id="usersLoadingOverlay">
                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>
                </div>
                <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th width="12%">Level</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows are loaded via ajax from api/datatable_users.php -->
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Pengguna</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_user" id="id_user">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(paymentCsrfToken()); ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Password <span class="text-danger" id="passwordRequired">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                                <small class="text-muted" id="passwordHint">Minimal 6 karakter.</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>No. Telepon</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="level" id="level" class="form-control" required>
                                    <option value="admin">Admin</option>
                                    <option value="bendahara">Bendahara</option>
                                    <option value="kepala_tpq">Kepala TPQ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <fieldset id="kelasAssignment" class="border rounded p-3 mb-3">
                        <legend class="h6">Kelas yang boleh dikelola bendahara</legend>
                        <p class="small text-muted">Centang satu atau beberapa kelas. Tanpa pilihan, bendahara tidak dapat input pembayaran. Admin tetap dapat mengelola semua kelas.</p>
                        <?php while ($kelas = mysqli_fetch_assoc($kelas_options)): ?>
                            <div class="form-check">
                                <input class="form-check-input kelas-assignment" type="checkbox" name="kelas_ids[]"
                                    id="kelas_<?php echo (int) $kelas['id_kelas']; ?>" value="<?php echo (int) $kelas['id_kelas']; ?>">
                                <label class="form-check-label" for="kelas_<?php echo (int) $kelas['id_kelas']; ?>"><?php echo htmlspecialchars($kelas['nama_kelas']); ?></label>
                            </div>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($kelas_options) === 0): ?>
                            <p class="text-muted mb-0">Belum ada kelas. Tambahkan melalui Data Kelas.</p>
                        <?php endif; ?>
                    </fieldset>
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
function toggleKelasAssignment() {
    const enabled = document.getElementById("level").value === "bendahara";
    document.getElementById("kelasAssignment").hidden = !enabled;
    document.querySelectorAll(".kelas-assignment").forEach(function (el) { el.disabled = !enabled; });
}
function resetForm() {
    document.getElementById("modalTitle").textContent = "Tambah Pengguna";
    document.getElementById("id_user").value = "";
    document.getElementById("username").value = "";
    document.getElementById("password").value = "";
    document.getElementById("password").required = true;
    document.getElementById("passwordRequired").style.display = "inline";
    document.getElementById("passwordHint").textContent = "Minimal 6 karakter.";
    document.getElementById("nama").value = "";
    document.getElementById("email").value = "";
    document.getElementById("no_telp").value = "";
    document.getElementById("level").value = "bendahara";
    document.getElementById("status").value = "active";
    document.querySelectorAll(".kelas-assignment").forEach(function (el) { el.checked = false; });
    toggleKelasAssignment();
}

function editUser(data) {
    document.getElementById("modalTitle").textContent = "Edit Pengguna";
    document.getElementById("id_user").value = data.id_user;
    document.getElementById("username").value = data.username;
    document.getElementById("password").value = "";
    document.getElementById("password").required = false;
    document.getElementById("passwordRequired").style.display = "none";
    document.getElementById("passwordHint").textContent = "Kosongkan jika tidak ingin mengubah password.";
    document.getElementById("nama").value = data.nama;
    document.getElementById("email").value = data.email || "";
    document.getElementById("no_telp").value = data.no_telp || "";
    document.getElementById("level").value = data.level;
    document.getElementById("status").value = data.status;
    const kelasIds = (data.kelas_ids || []).map(String);
    document.querySelectorAll(".kelas-assignment").forEach(function (el) { el.checked = kelasIds.includes(el.value); });
    toggleKelasAssignment();
    bootstrap.Modal.getOrCreateInstance(document.getElementById("userModal")).show();
}

$(function () {
    $("#level").on("change", toggleKelasAssignment);
    toggleKelasAssignment();
    $("#usersTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "' . $base_url . '/api/datatable_users.php",
            error: function () {
                $("#usersLoadingOverlay").fadeOut(150);
            }
        },
        columns: [
            { data: 0, orderable: false, searchable: false },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4, className: "text-center" },
            { data: 5, className: "text-center" },
            { data: 6, orderable: false, searchable: false, className: "text-center" }
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#usersLoadingOverlay").fadeOut(150);
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
