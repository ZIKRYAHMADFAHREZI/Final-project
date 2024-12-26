<?php
session_start();
require 'db/connection.php'; // Koneksi database

class Auth {
    // Konstruktor (opsional jika Anda ingin menggunakan koneksi database)
    public function __construct() {
        // Jika Anda butuh database di masa depan, tambahkan logika di sini
    }

    public function logout() {
        // Hapus semua data sesi
        $_SESSION = [];
        session_destroy();

        // Hapus cookie "remember_token" jika ada
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, "/"); // Set expired ke waktu lampau
        }

        // Redirect ke halaman login
        header("Location: login.php");
        exit();
    }
}

// Inisialisasi dan panggil metode logout
$auth = new Auth();
$auth->logout();
?>