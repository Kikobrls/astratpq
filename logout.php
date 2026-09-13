<?php
/**
 * Logout
 * Sistem Keuangan TPQ
 */

session_start();
require_once 'config/database.php';

// Log activity
if (isset($_SESSION['id_user'])) {
    logActivity('Logout dari sistem', 'users', $_SESSION['id_user']);
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header("Location: login.php");
exit;
?>
