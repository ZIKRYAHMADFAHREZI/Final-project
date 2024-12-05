<?php

session_start();

// Menginclude file koneksi
require 'connection.php'; // Pastikan path ini sesuai dengan lokasi file koneksi.php

if (isset($_POST['submit'])) {
    // Mengambil data dari form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        die("Password dan konfirmasi password tidak cocok.");
    }

    // Hash password sebelum menyimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data ke dalam tabel dengan prepared statement
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Eksekusi query
    if ($stmt->execute()) {
        header('Location: ../login.php');
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    // Menutup koneksi tidak diperlukan, karena PDO akan menutup koneksi saat skrip selesai
}
?>