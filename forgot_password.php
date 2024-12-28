<?php
require 'db/connection.php';
require 'vendor/autoload.php'; // Autoload PHPMailer dan dependensi lainnya

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PasswordReset {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRequest($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Alamat email tidak valid.'];
        }

        try {
            $user = $this->getUserByEmail($email);

            if ($user) {
                $token = $this->generateToken();
                $this->saveResetToken($email, $token);
                $this->sendResetEmail($email, $token);
                return ['status' => 'success', 'message' => 'Email reset password telah dikirim. Token berlaku selama 5 menit. Periksa inbox Anda.'];
            } else {
                return ['status' => 'error', 'message' => 'Email tidak ditemukan.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => 'Gagal mengirim email: ' . $e->getMessage()];
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Terjadi kesalahan pada sistem. Silakan coba lagi.'];
        }
    }

    private function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function generateToken() {
        return bin2hex(random_bytes(32));
    }

    private function saveResetToken($email, $token) {
        $createdAt = date('Y-m-d H:i:s');  // Waktu saat token dibuat
        $expireAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));  // Waktu kedaluwarsa token (5 menit setelah waktu pembuatan)
        
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET password_reset_token = :token, password_reset_exp = :expire_at 
            WHERE email = :email
        ");
        $stmt->execute([
            'token' => $token,
            'expire_at' => $expireAt,  // Waktu kedaluwarsa baru
            'email' => $email
        ]);
    }
    
    private function sendResetEmail($email, $token) {
        $resetLink = "http://localhost/fp/final-project/reset_password.php?token=$token";
        $subject = "Permintaan Reset Password";
        $body = "
            <html>
            <head>
                <title>Permintaan Reset Password</title>
            </head>
            <body>
                <p>Halo $email,</p>
                <p>Klik link berikut untuk reset password Anda:</p>
                <p><a href=\"$resetLink\" target=\"_blank\">$resetLink</a></p>
                <p>Catatan: Link reset password ini hanya berlaku selama <strong>5 menit</strong>. Jika kedaluwarsa, silakan minta ulang reset password.</p>
                <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            </body>
            </html>
        ";
    
        $mail = new PHPMailer(true);

        try {
            // Konfigurasi SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Host SMTP Gmail yang benar
            $mail->SMTPAuth = true;
            $mail->Username = 'zikridede137@gmail.com'; // Ganti dengan email Gmail Anda
            $mail->Password = 'fcvi qpcw jwej yqyw'; // Ganti dengan App Password Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Gunakan enkripsi TLS
            $mail->Port = 587; // Port untuk TLS
            // Pengaturan email
            $mail->setFrom('grandmutiara4@gmail.com', 'Grand Mutiara'); // Ganti dengan nama pengirim
            $mail->addAddress($email); // Alamat email penerima
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $body;
    
        } catch (Exception $e) {
            // Tampilkan pesan kesalahan jika gagal
            echo "Pesan tidak dapat dikirim. Error: {$mail->ErrorInfo}";
        }
        
    }    
}

// Proses request
$response = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $passwordReset = new PasswordReset($pdo);
    $response = $passwordReset->handleRequest($email);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- bootsrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/mail.css">
</head>
<body style="background-color: #DCDCDC;">
    <div class="container">
        <h2>Lupa Password</h2>
        <?php if ($response): ?>
            <div class="alert alert-<?php echo htmlspecialchars($response['status'] === 'success' ? 'success' : 'danger'); ?>" role="alert">
                <?php echo htmlspecialchars($response['message']); ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Masukkan email Anda:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Kirim</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
