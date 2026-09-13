<?php
// Start output buffering as early as possible so accidental output
// (BOM, whitespace, warnings) never blocks session/cookie headers.
if (ob_get_level() === 0) {
    ob_start();
}

// Adjust relative path depending on where header.php is included from
// Load config FIRST (config/app.php also handles ob_start + session).
$root_dir = dirname(__DIR__);
if (file_exists($root_dir . '/config/app.php')) {
    require_once $root_dir . '/config/app.php';
}
if (file_exists($root_dir . '/config/database.php')) {
    require_once $root_dir . '/config/database.php';
}

// Start the session only if it is not active yet and headers not sent.
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

$base_url = baseUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Sistem Pembayaran Sekolah" />
    <meta name="author" content="" />
    <title><?php echo isset($page_title) ? $page_title . ' - Sistem Keuangan' : 'Sistem Keuangan'; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>/assets/css/styles.css" rel="stylesheet" />
    <link href="<?php echo $base_url; ?>/assets/css/custom.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="<?php echo $base_url; ?>/assets/assets/img/favicon.png" />

    <script data-search-pseudo-elements defer
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <?php if (isset($extra_css))
        echo $extra_css; ?>
</head>

<body class="nav-fixed">

    <nav class="topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white"
        id="sidenavAccordion">
        <button class="btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0" id="sidebarToggle"><i
                data-feather="menu"></i></button>
        <a class="navbar-brand pe-3 ps-4 ps-lg-2 d-flex align-items-center" href="<?php echo $base_url; ?>/index.php">
            <img src="<?php echo $base_url; ?>/assets/img/logo.jpg" alt="Logo" class="topnav-logo me-2" onerror="this.style.display='none'" />
            SIKET
        </a>

        <ul class="navbar-nav align-items-center ms-auto">
            <!-- User Dropdown-->
            <li class="nav-item dropdown no-caret dropdown-user me-3 me-lg-4">
                <a class="btn btn-icon btn-transparent-dark dropdown-toggle" id="navbarDropdownUserImage"
                    href="javascript:void(0);" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <img class="img-fluid"
                        src="<?php echo $base_url; ?>/assets/assets/img/illustrations/profiles/profile-1.png" />
                </a>
                <div class="dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up"
                    aria-labelledby="navbarDropdownUserImage">
                    <h6 class="dropdown-header d-flex align-items-center">
                        <img class="dropdown-user-img"
                            src="<?php echo $base_url; ?>/assets/assets/img/illustrations/profiles/profile-1.png" />
                        <div class="dropdown-user-details">
                            <div class="dropdown-user-details-name"><?php echo htmlspecialchars($_SESSION['nama_user'] ?? 'Pengguna'); ?></div>
                            <div class="dropdown-user-details-email">@<?php echo htmlspecialchars($_SESSION['username'] ?? 'admin'); ?></div>
                        </div>
                    </h6>
                    <div class="dropdown-divider"></div>
                    <!-- Adding setting link directly in user menu since it's a common placement -->
                    <a class="dropdown-item" href="<?php echo $base_url; ?>/pages/users/profile.php">
                        <div class="dropdown-item-icon"><i data-feather="user"></i></div>
                        Profile
                    </a>
                    <a class="dropdown-item" href="<?php echo $base_url; ?>/logout.php">
                        <div class="dropdown-item-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>