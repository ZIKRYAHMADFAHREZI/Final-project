<?php
include 'db.php';
session_start();

// Cek apakah sudah login melalui sesi
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    // Pengguna sudah login melalui sesi
    echo "Selamat datang, user!";
} elseif (isset($_COOKIE['remember_me'])) {
    // Cek apakah ada cookie "remember_me"
    $token = $_COOKIE['remember_me'];

    // Cek token di database
    $stmt = $pdo->prepare("SELECT * FROM remember_me_tokens WHERE token = :token AND expires_at > NOW()");
    $stmt->execute(['token' => $token]);
    $remember_me = $stmt->fetch();

    if ($remember_me) {
        // Token valid, set sesi dan arahkan ke halaman dashboard
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $remember_me['user_id'];
        header('Location: dashboard.php');
        exit;
    } else {
        // Token tidak valid, hapus cookie
        setcookie('remember_me', '', time() - 3600, '/');
    }
} else {
    // Pengguna belum login, arahkan ke halaman login
    header('Location: login.php');
    exit;
}
?>
