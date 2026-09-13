<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/app.php';
require_once '../../config/database.php';

$id_petugas = $_SESSION['id_petugas'] ?? null;

if (!$id_petugas) {
    header("Location: ../../login.php");
    exit;
}

$page_title = 'Profil Saya';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
require_once '../../includes/topbar.php';

// Get current user data
$query = "SELECT * FROM petugas WHERE id_petugas = '$id_petugas'";
$result = mysqli_query($conn, $query);
$petugas = mysqli_fetch_assoc($result);

if (!$petugas) {
    session_destroy();
    header("Location: ../../login.php");
    exit;
}

$errors = [];
$success = false;

// Handle profile update
if (isset($_POST['update_profile'])) {
    $nama_petugas = sanitize($_POST['nama_petugas']);
    $email = sanitize($_POST['email']);
    $no_telp = sanitize($_POST['no_telp']);

    if (empty($nama_petugas)) {
        $errors[] = "Nama harus diisi!";
    }

    if (empty($errors)) {
        $query = "UPDATE petugas SET 
                    nama_petugas = '$nama_petugas',
                    email = '$email',
                    no_telp = '$no_telp'
                  WHERE id_petugas = '$id_petugas'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['nama_petugas'] = $nama_petugas;
            logActivity('Mengubah profil', 'petugas', $id_petugas);
            setFlash('success', 'Profil berhasil diperbarui!');
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Gagal memperbarui profil!";
        }
    }

    $petugas['nama_petugas'] = $nama_petugas;
    $petugas['email'] = $email;
    $petugas['no_telp'] = $no_telp;
}

// Handle password change
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $errors[] = "Semua field password harus diisi!";
    } else if (!password_verify($current_password, $petugas['password'])) {
        $errors[] = "Password saat ini tidak valid!";
    } else if (strlen($new_password) < 6) {
        $errors[] = "Password baru minimal 6 karakter!";
    } else if ($new_password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak cocok!";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE petugas SET password = '$hashed_password' WHERE id_petugas = '$id_petugas'";

        if (mysqli_query($conn, $query)) {
            logActivity('Mengubah password', 'petugas', $id_petugas);
            setFlash('success', 'Password berhasil diubah!');
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Gagal mengubah password!";
        }
    }
}

// Get activity statistics
$stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total_pembayaran
    FROM pembayaran WHERE id_petugas = '$id_petugas'
"));
?>

<!-- Page Header (SB Admin Pro style) -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-xl px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="fas fa-user"></i></div>
                        Profil Saya
                    </h1>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Begin Page Content -->
<div class="container-xl px-4 mt-4">



    <?php if (!empty($errors)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: '<?php echo addslashes(implode('<br>', $errors)); ?>',
                showConfirmButton: true
            });
        </script>
    <?php endif; ?>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3" src="../../assets/img/undraw_profile.svg"
                        style="width: 150px;">
                    <h5 class="font-weight-bold"><?php echo htmlspecialchars($petugas['nama_petugas']); ?></h5>
                    <p class="text-muted mb-1">@<?php echo htmlspecialchars($petugas['username']); ?></p>
                    <span class="badge bg-<?php echo $petugas['level'] == 'admin' ? 'danger' : 'primary'; ?>">
                        <?php echo ucfirst($petugas['level']); ?>
                    </span>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong>Total Pembayaran Diproses:</strong><br>
                        <span class="h5 text-success"><?php echo formatRupiah($stats['total_pembayaran']); ?></span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Edit Forms -->
        <div class="col-lg-8">
            <!-- Profile Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit mr-2"></i>Edit Profil
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control bg-light"
                                value="<?php echo htmlspecialchars($petugas['username']); ?>" readonly>
                            <small class="text-muted">Username tidak dapat diubah</small>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_petugas" class="form-control"
                                value="<?php echo htmlspecialchars($petugas['nama_petugas']); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo htmlspecialchars($petugas['email']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="text" name="no_telp" class="form-control"
                                        value="<?php echo htmlspecialchars($petugas['no_telp']); ?>">
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Password Saat Ini <span class="text-danger">*</span></label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password Baru <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" class="form-control" minlength="6"
                                        required>
                                    <small class="text-muted">Minimal 6 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="change_password" class="btn btn-warning">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<?php require_once '../../includes/footer.php'; ?>
