<?php
require_once '../src/config.php';
require_once '../src/Database.php';
require_once '../src/Auth.php';

$auth = new Auth(new Database());

// If user is logged in, redirect to dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

// Otherwise, redirect to login
header('Location: login.php');
exit;
?>