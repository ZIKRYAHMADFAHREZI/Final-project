<?php
require 'db/connection.php';

if (!isset($_GET['token'])) {
    die("Token tidak ditemukan.");
}

$token = $_GET['token'];

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE password_reset_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Token tidak valid atau sudah kedaluwarsa.");
    }
} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    try {
        $stmt = $pdo->prepare("UPDATE users SET password = :password, password_reset_token = NULL WHERE id_user = :id_user");
        $stmt->execute(['password' => $password, 'id_user' => $user['id_user']]);
        echo "Password berhasil direset. Anda dapat login sekarang.";
    } catch (PDOException $e) {
        die("Kesalahan database: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form method="post">
        <label for="password">Password Baru:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
