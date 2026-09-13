<?php
// Sidebar requires base_url which is set in header.php
if (!isset($base_url)) {
    $base_url = baseUrl();
}

$current_path = $_SERVER['SCRIPT_NAME'];

// Define a helper function to determine active state
if (!function_exists('nav_active')) {
    function nav_active($path, $current_path) {
        if ($path === 'dashboard') {
            if (strpos($current_path, '/pindahkelas.php') !== false) return '';
            if (strpos($current_path, '/pages/') === false) return 'active';
            return '';
        }

        // For santri, including the bulk class update shortcut
        if ($path === 'santri') {
            if (strpos($current_path, '/pindahkelas.php') !== false || strpos($current_path, '/pages/santri/') !== false) return 'active';
            return '';
        }

        if (strpos($current_path, '/pages/' . $path . '/') !== false) {
            return 'active';
        }
        return '';
    }
}
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sidenav shadow-right sidenav-light">
            <div class="sidenav-menu">
                <div class="nav accordion" id="accordionSidenav">

                    <div class="sidenav-menu-heading">Menu Utama</div>
                    <a class="nav-link <?php echo nav_active('dashboard', $current_path); ?>" href="<?php echo $base_url; ?>/index.php">
                        <div class="nav-link-icon"><i data-feather="activity"></i></div>
                        Dashboard
                    </a>

                    <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin'): ?>
                    <div class="sidenav-menu-heading">Data Master</div>
                    <a class="nav-link <?php echo nav_active('santri', $current_path); ?>" href="<?php echo $base_url; ?>/pages/santri/index.php">
                        <div class="nav-link-icon"><i data-feather="user"></i></div>
                        Data Santri
                    </a>
                    <a class="nav-link <?php echo nav_active('kelas', $current_path); ?>" href="<?php echo $base_url; ?>/pages/kelas/index.php">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        Data Kelas
                    </a>
                    <a class="nav-link <?php echo nav_active('iuran', $current_path); ?>" href="<?php echo $base_url; ?>/pages/iuran/index.php">
                        <div class="nav-link-icon"><i data-feather="dollar-sign"></i></div>
                        Data Iuran
                    </a>
                    <a class="nav-link <?php echo nav_active('users', $current_path); ?>" href="<?php echo $base_url; ?>/pages/users/index.php">
                        <div class="nav-link-icon"><i data-feather="user-check"></i></div>
                        Data Pengguna
                    </a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['level']) && $_SESSION['level'] != 'kepala_tpq'): ?>
                    <div class="sidenav-menu-heading">Transaksi</div>
                    <a class="nav-link <?php echo nav_active('pembayaran', $current_path); ?>" href="<?php echo $base_url; ?>/pages/pembayaran/index.php">
                        <div class="nav-link-icon"><i data-feather="credit-card"></i></div>
                        Pembayaran
                    </a>
                    <a class="nav-link <?php echo nav_active('jurnal_umum', $current_path); ?>" href="<?php echo $base_url; ?>/pages/jurnal_umum/index.php">
                        <div class="nav-link-icon"><i data-feather="book-open"></i></div>
                        Jurnal Umum
                    </a>
                    <a class="nav-link <?php echo nav_active('pos_keuangan', $current_path); ?>" href="<?php echo $base_url; ?>/pages/pos_keuangan/index.php">
                        <div class="nav-link-icon"><i data-feather="pie-chart"></i></div>
                        Pos Keuangan
                    </a>
                    <?php endif; ?>

                    <div class="sidenav-menu-heading">Laporan</div>
                    <a class="nav-link <?php echo nav_active('laporan', $current_path); ?>" href="<?php echo $base_url; ?>/pages/laporan/index.php">
                        <div class="nav-link-icon"><i data-feather="printer"></i></div>
                        Laporan
                    </a>

                    <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 'admin'): ?>
                    <div class="sidenav-menu-heading">Pengaturan</div>
                    <a class="nav-link <?php echo nav_active('setting', $current_path); ?>" href="<?php echo $base_url; ?>/pages/setting/general.php">
                        <div class="nav-link-icon"><i data-feather="settings"></i></div>
                        Sistem
                    </a>
                    <?php endif; ?>

                </div>
            </div>

            <div class="sidenav-footer">
                <div class="sidenav-footer-content">
                    <div class="sidenav-footer-subtitle">Logged in as:</div>
                    <div class="sidenav-footer-title">
                        <?php
                        $level_display = $_SESSION['level'] ?? '';
                        if ($level_display == 'kepala_tpq') {
                            echo 'Kepala TPQ';
                        } else {
                            echo ucfirst(htmlspecialchars($level_display));
                        }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content Wrapper -->
    <div id="layoutSidenav_content">
        <main>
