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
if (($_SESSION['level'] ?? '') !== 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

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

            if (mysqli_query($conn, $query)) {
                logActivity("Menyimpan data pengguna ($msg)", 'users', $id_user ?: mysqli_insert_id($conn));
                setFlash('success', "Data pengguna berhasil $msg!");
            } else {
                setFlash('danger', 'Gagal menyimpan data pengguna!');
            }
        }
    }
    header("Location: index.php");
    exit;
}

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
    $("#userModal").modal("show");
}

$(function () {
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
