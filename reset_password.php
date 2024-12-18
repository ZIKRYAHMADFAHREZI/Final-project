<?php
require 'db/connection.php';

if (!isset($_GET['token'])) {
    die("Token tidak ditemukan.");
}

$token = $_GET['token'];

try {
    // Cari user berdasarkan token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE password_reset_token = :token");
    $stmt->execute(['token' => $token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Token tidak valid.");
    }

    // Periksa apakah token sudah kedaluwarsa
    $createdAt = new DateTime($user['password_reset_exp']);
    $now = new DateTime();
    $interval = $createdAt->diff($now);

    if ($interval->i >= 1) { // Token kedaluwarsa setelah 1 menit
        // Hapus token dari database
        $stmt = $pdo->prepare("UPDATE users SET password_reset_token = NULL, password_reset_exp = NULL WHERE id_user = :id_user");
        $stmt->execute(['id_user' => $user['id_user']]);
        die("Token telah kedaluwarsa. Silakan minta reset password ulang.");
    }
} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}

// Jika metode POST digunakan, proses reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi konfirmasi password
    if ($new_password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Hash password baru
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        try {
            // Update password di database dan hapus token
            $stmt = $pdo->prepare("UPDATE users SET password = :password, password_reset_token = NULL, password_reset_exp = NULL WHERE id_user = :id_user");
            $stmt->execute(['password' => $hashed_password, 'id_user' => $user['id_user']]);
            $success = "Password berhasil direset. Anda dapat login sekarang.";
        } catch (PDOException $e) {
            die("Kesalahan database: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Reset Password</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }
    .container {
        margin-top: 50px;
        max-width: 400px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #343a40;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    .btn-primary {
        width: 100%;
    }
    .eye-icon {
        cursor: pointer;
        position: absolute;
        margin-top: 17px;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3 position-relative">
                <label for="new_password" class="form-label">Password Baru:</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
                <i class="eye-icon bi bi-eye-slash" id="toggleNewPassword"></i>
            </div>
            <div class="mb-3 position-relative">
                <label for="confirm_password" class="form-label">Konfirmasi Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                <i class="eye-icon bi bi-eye-slash" id="toggleConfirmPassword"></i>
            </div>
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleNewPassword').addEventListener('click', function () {
            const newPassword = document.getElementById('new_password');
            const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            newPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPassword = document.getElementById('confirm_password');
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>

