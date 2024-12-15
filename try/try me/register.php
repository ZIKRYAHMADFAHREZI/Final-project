<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $remember = isset($_POST['remember']); // Cek apakah remember me dicentang

    // Simpan data pengguna ke database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    // Ambil ID pengguna yang baru saja didaftarkan
    $user_id = $pdo->lastInsertId();

    // Jika Remember Me dipilih
    if ($remember) {
        $token = bin2hex(random_bytes(32)); // Membuat token acak
        $expires_at = time() + 3600 * 24 * 30; // Token berlaku selama 30 hari

        // Simpan token ke database
        $stmt = $pdo->prepare("INSERT INTO remember_me_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmt->execute(['user_id' => $user_id, 'token' => $token, 'expires_at' => date('Y-m-d H:i:s', $expires_at)]);

        // Set cookie di browser pengguna
        setcookie('remember_me', $token, $expires_at, '/', '', false, true);
    }

    // Set sesi login setelah registrasi berhasil
    $_SESSION['is_logged_in'] = true;
    $_SESSION['user_id'] = $user_id;

    // Arahkan ke halaman dashboard setelah registrasi dan login
    header('Location: dashboard.php');
    exit;
}
?>

<form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <label for="remember">Remember Me</label>
    <input type="checkbox" name="remember" id="remember">
    <br>
    <button type="submit">Register</button>
</form>
