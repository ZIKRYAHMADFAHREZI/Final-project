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
                return ['status' => 'success', 'message' => 'Email reset password telah dikirim. Token berlaku selama 1 menit. Periksa inbox Anda.'];
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
        $createdAt = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET password_reset_token = :token, password_reset_exp = :created_at 
            WHERE email = :email
        ");
        $stmt->execute([
            'token' => $token,
            'created_at' => $createdAt,
            'email' => $email
        ]);
    }

    private function sendResetEmail($email, $token) {
        $resetLink = "http://localhost/final-project/reset_password.php?token=$token";
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
                <p>Catatan: Link reset password ini hanya berlaku selama <strong>1 menit</strong>. Jika kedaluwarsa, silakan minta ulang reset password.</p>
                <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
            </body>
            </html>
        ";

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '619786645e4263'; // Ganti dengan Mailtrap username Anda
        $mail->Password = 'a749dfea09d4b3'; // Ganti dengan Mailtrap password Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;

        $mail->setFrom('hotelgrandmutiara4@gmail.com', 'Hotel Grand Mutiara');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $body;

        $mail->send();
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