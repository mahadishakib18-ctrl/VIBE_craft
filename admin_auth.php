<?php
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Default hardcoded credentials
$valid_user = 'admin';
$valid_pass = '1234';

if ($username === $valid_user && $password === $valid_pass) {
    $_SESSION['admin'] = true;
    header("Location: admin_dash.php");
    exit;
} else {
    header("Location: admin_login.html?error=1");
    exit;
}
?>

