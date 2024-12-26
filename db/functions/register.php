<?php
require '../connection.php';
class UserRegistration {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function registerUser($username, $email, $password, $confirm_password) {
        if ($password !== $confirm_password) {
            $this->showAlert('error', 'Gagal', 'Password dan konfirmasi password tidak cocok!');
            return;
        }

        try {
            // Periksa apakah username sudah ada
            $checkUser = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->pdo->prepare($checkUser);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $this->showAlert('error', 'Gagal', 'Username sudah digunakan. Silakan pilih username lain.');
                return;
            }

            // Hash password sebelum menyimpan ke database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Query untuk memasukkan data ke dalam tabel dengan prepared statement
            $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
            $stmt = $this->pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);

            // Eksekusi query
            if ($stmt->execute()) {
                $this->showAlert('success', 'Registrasi Berhasil', 'Silakan login.', 'login.php');
            } else {
                $this->showAlert('error', 'Registrasi Gagal', 'Terjadi kesalahan saat menyimpan data.');
            }
        } catch (PDOException $e) {
            $this->showAlert('error', 'Kesalahan Server', $e->getMessage());
        }
    }

    private function showAlert($icon, $title, $text, $redirect = null) {
        echo "<script>
            Swal.fire({
                icon: '$icon',
                title: '$title',
                text: '$text',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed && '$redirect' !== null) {
                    window.location.href = '$redirect';
                }
            });
        </script>";
    }
}

// Proses registrasi jika form disubmit
if (isset($_POST['submit'])) {
    require 'db/connection.php';

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $registration = new UserRegistration($pdo);
    $registration->registerUser($username, $email, $password, $confirm_password);
}
?>
