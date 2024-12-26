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

    // Redirect ke login.php jika berhasil
    if ($message === "Password berhasil direset. Anda akan diarahkan ke halaman login.") {
        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: '$message',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.php';
                });
              </script>";
        exit;
    } else {
        // Tampilkan pesan error jika gagal
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: '$message',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>