<?php
// Koneksi ke database (sesuaikan dengan database Anda)
$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Menangani data dari form registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Validasi password
    if ($pass !== $confirm_pass) {
        die("Password dan konfirmasi password tidak cocok.");
    }

    // Hash password
    $password_hash = password_hash($pass, PASSWORD_DEFAULT);

    // Masukkan data pengguna ke database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$user, $email, $password_hash]);
        echo "Registrasi berhasil! Silakan <a href='login.html'>Login</a>";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fitur Remember Me</title>
</head>
<body>
    <h2>Registrasi Pengguna Baru</h2>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Konfirmasi Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">Daftar</button>
    </form>
    <br>
    <p>Sudah punya akun? <a href="login.html">Login di sini</a></p>
</body>
</html>