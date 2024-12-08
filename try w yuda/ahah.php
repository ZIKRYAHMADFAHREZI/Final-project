<?php
// Koneksi ke database
$host = 'localhost';  // atau '127.0.0.1' jika localhost tidak berfungsi
$db = 'methods';   // Nama database Anda
$user = 'root';       // Username database
$pass = '';           // Password database

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
