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

// Menangani login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? true : false;

    // Ambil data pengguna dari database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user]);
    $user_data = $stmt->fetch();

    if ($user_data && password_verify($pass, $user_data['password_hash'])) {
        // Jika password cocok, buat sesi
        session_start();
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];

        // Set cookie untuk Remember Me jika checkbox dicentang
        if ($remember_me) {
            $token = bin2hex(random_bytes(16));
            setcookie("remember_me", $token, time() + (86400 * 30), "/"); // Set cookie selama 30 hari

            // Simpan token ke database untuk pencocokan
            $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            $stmt->execute([$token, $user_data['id']]);
        }

        header("Location: dashboard.php"); // Arahkan ke halaman utama setelah login
        exit();
    } else {
        echo "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Fitur Remember Me</title>
</head>
<body>
    <h2>Login</h2>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="remember_me">Remember Me:</label>
        <input type="checkbox" id="remember_me" name="remember_me"><br><br>

        <button type="submit">Login</button>
    </form>
    <br>
    <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</body>
</html>
