<?php
session_start();
include 'db.php';

// Hapus sesi
session_unset();
session_destroy();

// Hapus cookie remember me
setcookie('remember_me', '', time() - 3600, '/');

// Arahkan ke halaman login
header('Location: login.php');
exit;
?>
