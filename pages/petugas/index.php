<?php
ob_start();
/**
 * Data Petugas
 * Sistem Keuangan TPQ
 */

$page_title = 'Data Petugas';
require_once '../../includes/header.php';

// Check admin level
if ($_SESSION['level'] != 'admin') {
    setFlash('danger', 'Anda tidak memiliki akses ke halaman ini!');
    header("Location: ../../index.php");
    exit;
}

require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);

    // Prevent self-delete
    if ($id == $_SESSION['id_petugas']) {
        setFlash('danger', 'Anda tidak dapat menghapus akun sendiri!');
    } else {
        // Check if petugas has payments
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pembayaran WHERE id_petugas = '$id'"));

        if ($check['total'] > 0) {
            // Set to inactive instead of delete
            mysqli_query($conn, "UPDATE petugas SET status = 'inactive' WHERE id_petugas = '$id'");
            logActivity('Menonaktifkan akun petugas', 'petugas', $id);
            setFlash('warning', 'Petugas memiliki data pembayaran. Status diubah menjadi tidak aktif.');
        } else {
            if (mysqli_query($conn, "DELETE FROM petugas WHERE id_petugas = '$id'")) {
                logActivity('Menghapus data petugas', 'petugas', $id);
                setFlash('success', 'Data petugas berhasil dihapus!');
            } else {
                setFlash('danger', 'Gagal menghapus data petugas!');
            }
        }
    }
    header("Location: index.php");
    exit;
}

// Handle add/edit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_petugas = isset($_POST['id_petugas']) ? sanitize($_POST['id_petugas']) : '';
    $username = sanitize($_POST['username']);
    $nama_petugas = sanitize($_POST['nama_petugas']);
    $email = sanitize($_POST['email']);
    $no_telp = sanitize($_POST['no_telp']);
    $level = sanitize($_POST['level']);
    $status = sanitize($_POST['status']);
    $password = $_POST['password'];

    $error = '';

    if (empty($username) || empty($nama_petugas)) {
        $error = 'Username dan nama harus diisi!';
    }

    // Check duplicate username
    if (empty($id_petugas)) {
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_petugas FROM petugas WHERE username = '$username'"));
        if ($check)
            $error = 'Username sudah digunakan!';
        if (empty($password))
            $error = 'Password harus diisi untuk petugas baru!';
    } else {
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_petugas FROM petugas WHERE username = '$username' AND id_petugas != '$id_petugas'"));
        if ($check)
            $error = 'Username sudah digunakan!';
    }

    if (empty($error)) {
        if (empty($id_petugas)) {
            // Add new
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO petugas (username, password, nama_petugas, email, no_telp, level, status)
                       VALUES ('$username', '$hashed_password', '$nama_petugas', '$email', '$no_telp', '$level', '$status')";
            $msg = 'ditambahkan';
        } else {
            // Edit
            $password_sql = '';
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $password_sql = ", password = '$hashed_password'";
            }
            $query = "UPDATE petugas SET
                        username = '$username',
                        nama_petugas = '$nama_petugas',
                        email = '$email',
                        no_telp = '$no_telp',
                        level = '$level',
                        status = '$status'
                        $password_sql
                      WHERE id_petugas = '$id_petugas'";
            $msg = 'diubah';
        }

        if (mysqli_query($conn, $query)) {
            logActivity("Menyimpan data petugas ($msg)", 'petugas', $id_petugas ?: mysqli_insert_id($conn));
            setFlash('success', "Data petugas berhasil $msg!");
        } else {
            setFlash('danger', 'Gagal menyimpan data petugas!');
        }
    } else {
        setFlash('danger', $error);
    }

    header("Location: index.php");
    exit;
}

// Get data
$query = "SELECT g.*
          FROM petugas g
          ORDER BY g.level, g.nama_petugas";
$result = mysqli_query($conn, $query);
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                        Data Petugas
                    </h1>
                </div>
                <div class="col-12 col-md-auto mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#petugasModal"
                        onclick="resetForm()">
                        <i class="fas fa-plus me-1"></i> Tambah Petugas
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Petugas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Level</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><code><?php echo htmlspecialchars($row['username']); ?></code></td>
                                <td><?php echo htmlspecialchars($row['nama_petugas']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo $row['level'] == 'admin' ? 'danger' : 'primary'; ?>">
                                        <?php echo ucfirst($row['level']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <div class="dropdown no-caret me-2">
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark dropdown-toggle" id="dropdownMenuLink_<?php echo $row['id_petugas']; ?>" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink_<?php echo $row['id_petugas']; ?>">
                                                <div class="dropdown-header">Aksi:</div>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick='editPetugas(<?php echo json_encode($row); ?>)'>
                                                    <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                        <?php if ($row['id_petugas'] != $_SESSION['id_petugas']): ?>
                                            <button class="btn btn-datatable btn-icon btn-transparent-dark" onclick="confirmDelete('index.php?delete=<?php echo $row['id_petugas']; ?>')">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-xl -->

<!-- Modal -->
<div class="modal fade" id="petugasModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Tambah Petugas</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="id_petugas" id="id_petugas">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <span class="text-danger" id="passReq">*</span></label>
                                <input type="password" name="password" id="password" class="form-control">
                                <small class="text-muted" id="passHelp">Minimal 6 karakter</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama_petugas" id="nama_petugas" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telepon</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="level" id="level" class="form-control" required>
                                    <option value="petugas">Petugas</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
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
<script>
function resetForm() {
    document.getElementById("modalTitle").textContent = "Tambah Petugas";
    document.getElementById("id_petugas").value = "";
    document.getElementById("username").value = "";
    document.getElementById("password").value = "";
    document.getElementById("password").required = true;
    document.getElementById("passReq").style.display = "inline";
    document.getElementById("passHelp").textContent = "Minimal 6 karakter";
    document.getElementById("nama_petugas").value = "";
    document.getElementById("email").value = "";
    document.getElementById("no_telp").value = "";
    document.getElementById("level").value = "petugas";
    document.getElementById("status").value = "active";
}

function editPetugas(data) {
    document.getElementById("modalTitle").textContent = "Edit Petugas";
    document.getElementById("id_petugas").value = data.id_petugas;
    document.getElementById("username").value = data.username;
    document.getElementById("password").value = "";
    document.getElementById("password").required = false;
    document.getElementById("passReq").style.display = "none";
    document.getElementById("passHelp").textContent = "Kosongkan jika tidak ingin mengubah password";
    document.getElementById("nama_petugas").value = data.nama_petugas;
    document.getElementById("email").value = data.email || "";
    document.getElementById("no_telp").value = data.no_telp || "";
    document.getElementById("level").value = data.level;
    document.getElementById("status").value = data.status;
    $("#petugasModal").modal("show");
}
</script>
';

require_once '../../includes/footer.php';
?>
