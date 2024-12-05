<?php
require 'db/connection.php'; // File koneksi database Anda
require 'vendor/autoload.php'; // Autoload PHPMailer dan dependensi lainnya

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    try {
        // Cek apakah email terdaftar
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate token untuk reset password
            $token = bin2hex(random_bytes(16));
            $stmt = $pdo->prepare("UPDATE users SET password_reset_token = :token WHERE email = :email");
            $stmt->execute(['token' => $token, 'email' => $email]);

            // Link reset password
            $resetLink = "http://localhost/final-project/reset_password.php?token=$token";
            $subject = "Permintaan Reset Password";
            $body = "Halo,\n\nKlik link berikut untuk reset password Anda:\n\n$resetLink\n\nJika Anda tidak meminta reset password, abaikan email ini.";

            // Konfigurasi PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '619786645e4263'; // Ganti dengan Mailtrap username Anda
            $mail->Password = 'a749dfea09d4b3'; // Ganti dengan Mailtrap password Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            // Pengaturan email
            $mail->setFrom('no-reply@yourdomain.com', 'Your App');
            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Kirim email
            $mail->send();
            $success = "Email reset password telah dikirim. Periksa inbox Anda.";
        } else {
            $error = "Email tidak ditemukan.";
        }
    } catch (Exception $e) {
        $error = "Gagal mengirim email: " . $mail->ErrorInfo;
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lupa Password</title>
</head>
<body>
    <h2>Lupa Password</h2>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form action="" method="post">
        <label for="email">Masukkan email Anda:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Kirim</button>
    </form>
</body>
</html>
