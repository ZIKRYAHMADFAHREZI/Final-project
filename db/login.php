<?php
// Sertakan file koneksi
require 'connection.php';

// Daftar email dan password yang sudah ada
$existing_users = [
    'user1@example.com' => 'password1',
    'user2@example.com' => 'password2',
    // Tambahkan pengguna lain sesuai kebutuhan
];

// Cek jika form login disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = $_POST['email'];
    $input_password = $_POST['password'];

    // Mencari user di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifikasi password dari database
        if (password_verify($input_password, $user['password'])) {
            // Login sukses dari database
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header("Location: ../about.php"); // Ganti dengan halaman tujuan setelah login
            exit();
        } else {
            echo "Password salah.";
        }
    } else {
        // Jika tidak ditemukan di database, periksa di array
        if (array_key_exists($input_username, $existing_users) && $existing_users[$input_username] === $input_password) {
            // Login sukses dari array
            session_start();
            $_SESSION['email'] = $input_username;
            header("Location: ../dashboard/index.php"); // Ganti dengan halaman tujuan setelah login
            exit();
        } else {
            echo "Email tidak ditemukan.";
        }
    }

    $stmt->close();
}

$conn->close();
?>