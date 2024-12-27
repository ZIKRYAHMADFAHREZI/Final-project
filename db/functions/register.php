<?php
require '../connection.php'; // Update path sesuai struktur folder Anda

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['status' => '', 'message' => ''];

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $response = ['status' => 'error', 'message' => 'Password dan konfirmasi password tidak cocok!'];
    } else {
        try {
            $checkUser = "SELECT * FROM users WHERE username = :username";
            $stmt = $pdo->prepare($checkUser);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $response = ['status' => 'error', 'message' => 'Username sudah digunakan.'];
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashed_password);

                if ($stmt->execute()) {
                    $response = ['status' => 'success', 'message' => 'Registrasi berhasil. Silakan login.'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan data.'];
                }
            }
        } catch (PDOException $e) {
            $response = ['status' => 'error', 'message' => 'Kesalahan Server: ' . $e->getMessage()];
        }
    }

    echo json_encode($response);
    exit;
}
?>