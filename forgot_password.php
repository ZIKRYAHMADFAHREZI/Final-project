<?php
require 'db/connection.php'; // File koneksi database Anda
require 'vendor/autoload.php'; // Autoload PHPMailer dan dependensi lainnya

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Alamat email tidak valid.";
    } else {
        try {
            // Cek apakah email terdaftar
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Generate token untuk reset password
                $token = bin2hex(random_bytes(32));
                $createdAt = date('Y-m-d H:i:s');

                // Update token dan timestamp di database
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET password_reset_token = :token, password_reset_exp = :created_at 
                    WHERE email = :email
                ");
                $stmt->execute([
                    'token' => $token,
                    'created_at' => $createdAt,
                    'email' => $email
                ]);

                // Link reset password
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
                $mail->setFrom('hotelgrandmutiara4@gmail.com', 'Hotel Grand Mutiara');
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->isHTML(true); // Kirim email sebagai HTML
                $mail->Body = $body;

                // Kirim email
                $mail->send();
                $success = "Email reset password telah dikirim. Token berlaku selama 1 menit. Periksa inbox Anda.";
            } else {
                $error = "Email tidak ditemukan.";
            }
        } catch (Exception $e) {
            $error = "Gagal mengirim email: " . $mail->ErrorInfo;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Lupa Password</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Masukkan email Anda:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

