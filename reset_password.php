<?php

class PasswordReset
{
    private $pdo;
    private $token;
    private $user;

    public function __construct($pdo, $token)
    {
        $this->pdo = $pdo;
        $this->token = $token;
        $this->user = null;
    }

    // Menemukan pengguna berdasarkan token
    public function findUserByToken()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE password_reset_token = :token");
        $stmt->execute(['token' => $this->token]);
        $this->user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$this->user) {
            die("Token tidak ditemukan.");
        }
    }

    // Verifikasi apakah token sudah kadaluwarsa
    public function isTokenExpired()
    {
        $expireAt = strtotime($this->user['password_reset_exp']);
        $currentTime = time();

        if ($currentTime > $expireAt) {
            return ['status' => 'error', 'message' => 'Token sudah kadaluarsa.'];
        } else {
            return ['status' => 'success', 'message' => 'Token valid.'];
        }
    }

    // Menghapus token reset password setelah digunakan
    public function clearToken()
    {
        $stmt = $this->pdo->prepare("UPDATE users SET password_reset_token = NULL, password_reset_exp = NULL WHERE id_user = :id_user");
        $stmt->execute(['id_user' => $this->user['id_user']]);
    }

    // Memeriksa apakah password baru sama dengan yang lama
    public function isPasswordSame($new_password)
    {
        return password_verify($new_password, $this->user['password']);
    }

    // Reset password
    public function resetPassword($new_password, $confirm_password)
    {
        if ($new_password !== $confirm_password) {
            return "Password dan konfirmasi password tidak cocok.";
        }

        if ($this->isPasswordSame($new_password)) {
            return "Password baru tidak boleh sama dengan password lama.";
        }

        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare("UPDATE users SET password = :password, password_reset_token = NULL, password_reset_exp = NULL WHERE id_user = :id_user");
            $stmt->execute(['password' => $hashed_password, 'id_user' => $this->user['id_user']]);
            return "Password berhasil direset. Anda akan diarahkan ke halaman login.";
        } catch (PDOException $e) {
            die("Kesalahan database: " . $e->getMessage());
        }
    }
}

require 'db/connection.php';

if (!isset($_GET['token'])) {
    die("Token tidak ditemukan.");
}

$token = $_GET['token'];
$passwordReset = new PasswordReset($pdo, $token);

try {
    // Menemukan pengguna berdasarkan token
    $passwordReset->findUserByToken();

    // Memeriksa apakah token sudah kedaluwarsa
    $result = $passwordReset->isTokenExpired();
    if ($result['status'] == 'error') {
        die($result['message']);
    }
} catch (PDOException $e) {
    die("Kesalahan database: " . $e->getMessage());
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $message = $passwordReset->resetPassword($new_password, $confirm_password);

    // Tampilkan SweetAlert sesuai dengan hasil
    $alertTitle = '';
    $alertText = '';
    $alertIcon = '';

    if ($message === "Password berhasil direset. Anda akan diarahkan ke halaman login.") {
        $alertTitle = 'Berhasil!';
        $alertText = $message;
        $alertIcon = 'success';
    } else {
        $alertTitle = 'Error!';
        $alertText = $message;
        $alertIcon = 'error';
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
<!-- Menambahkan CDN SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<link rel="stylesheet" href="css/r_password.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <!-- <?php if (isset($message)) { echo "<p>$message</p>"; } ?> -->
        <form action="" method="post">
            <div class="mb-3 position-relative">
                <label for="new_password" class="form-label">Password Baru:</label>
                <input type="password" name="new_password" id="new_password" class="form-control" minlength="8" maxlength="254" required>
                <i class="eye-icon bi bi-eye-slash" id="toggleNewPassword"></i>
            </div>
            <div class="mb-3 position-relative">
                <label for="confirm_password" class="form-label">Konfirmasi Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="8" maxlength="254" required>
                <i class="eye-icon bi bi-eye-slash" id="toggleConfirmPassword"></i>
            </div>
            <button type="submit" class="btn btn-primary">Ubah Password</button>
        </form>
    </div>
    <!-- Tampilkan SweetAlert jika ada pesan -->
    <?php if (isset($message)): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '<?php echo $alertTitle; ?>',
            text: '<?php echo $alertText; ?>',
            icon: '<?php echo $alertIcon; ?>',
            confirmButtonText: 'OK'
        }).then(() => {
            <?php if ($alertIcon === 'success') { echo "window.location.href = 'login.php';"; } ?>
        });
    </script>
    <?php endif; ?>
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

