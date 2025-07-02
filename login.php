<?php
session_start();
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $admin = validate_admin_login($username, $password);

    if ($admin) {
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        // Redirect back to index with error (optional)
        echo "<script>alert('Invalid login');window.location.href='index.php';</script>";
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
