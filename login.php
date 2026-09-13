<?php
/**
 * Login Page - Standard Bootstrap 5 (SB Admin Pro)
 * Sistem Keuangan TPQ
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config/database.php';
require_once 'config/app.php';

// Redirect if already logged in
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' AND status = 'active'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['login'] = true;
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_user'] = $user['nama'];
                $_SESSION['level'] = $user['level'];
                mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id_user = " . $user['id_user']);
                logActivity('Login ke sistem', 'users', $user['id_user']);
                header("Location: index.php");
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan atau akun tidak aktif!";
        }
    }
}

$nama_sekolah = getSetting('nama_sekolah', 'SMK Negeri 1 Contoh');

// Dynamic Base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base_url = $protocol . "://" . $_SERVER['HTTP_HOST'];
$script_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($script_dir !== '/') {
    $base_url .= rtrim($script_dir, '/');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - <?php echo APP_NAME; ?></title>

    <!-- Main Style (Bootstrap 5 based) -->
    <link href="<?php echo $base_url; ?>/assets/css/styles.css" rel="stylesheet" />

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            background-color: #ffffff;
            background-image: none;
        }
        .login-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 2rem 0 rgba(0, 0, 0, 0.2);
        }
        .login-header {
            background-color: transparent;
            border-bottom: none;
            padding-top: 2rem;
            padding-bottom: 1rem;
        }
        /* Logo berbentuk bintang 8 dengan latar putih.
           Tanpa border-radius/padding: sudut bintang tidak terpotong dan
           latarnya sudah menyatu dengan card. Cukup atur `height`,
           `width: auto` menjaga rasio asli. */
        .school-logo {
            height: 110px;
            width: auto;
            margin-bottom: 0.75rem;
        }
    </style>
</head>
<body>
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container-xl px-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <!-- Basic login form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5 login-card">
                                <div class="card-header justify-content-center login-header text-center">
                                    <img src="<?php echo $base_url; ?>/assets/img/logo.jpg" alt="Logo" class="school-logo" onerror="this.src='<?php echo $base_url; ?>/assets/img/undraw_profile.svg'">
                                    <h3 class="fw-light my-1"><?php echo htmlspecialchars($nama_sekolah); ?></h3>
                                    <p class="small text-muted mb-0">Sistem Administrasi Keuangan</p>
                                </div>
                                <div class="card-body px-4 pb-4">
                                    <?php if ($error): ?>
                                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                                            <i class="fas fa-exclamation-circle me-1"></i> <?php echo $error; ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Login form-->
                                    <form method="POST" action="">
                                        <!-- Form Group (username)-->
                                        <div class="mb-3">
                                            <label class="small mb-1 text-gray-600" for="inputUsername">Username</label>
                                            <div class="input-group input-group-joined">
                                                <span class="input-group-text text-gray-400"><i class="fas fa-user"></i></span>
                                                <input class="form-control" id="inputUsername" name="username" type="text" placeholder="Masukkan username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus />
                                            </div>
                                        </div>
                                        <!-- Form Group (password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1 text-gray-600" for="inputPassword">Password</label>
                                            <div class="input-group input-group-joined">
                                                <span class="input-group-text text-gray-400"><i class="fas fa-lock"></i></span>
                                                <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Masukkan password" required />
                                            </div>
                                        </div>
                                        <!-- Form Group (remember password checkbox)-->
                                        <div class="mb-3">
                                            <div class="form-check text-gray-600">
                                                <input class="form-check-input" id="checkShowPassword" type="checkbox" />
                                                <label class="form-check-label small" for="checkShowPassword">Tampilkan Password</label>
                                            </div>
                                        </div>
                                        <!-- Form Group (login box)-->
                                        <div class="d-grid mt-4">
                                            <button class="btn btn-primary btn-block p-2" type="submit">
                                                <i class="fas fa-sign-in-alt me-2"></i> Masuk Sekarang
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap Bundle JS (includes Popper) CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show/hide password logic
        const passInput = document.getElementById('inputPassword');
        const showPassCheck = document.getElementById('checkShowPassword');
        if(showPassCheck) {
            showPassCheck.addEventListener('change', function() {
                passInput.type = this.checked ? 'text' : 'password';
            });
        }
    </script>
</body>
</html>
