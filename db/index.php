<?php 
require 'connection.php';
try {
    $result = $pdo->query("SELECT * FROM types");
    $types = $result->fetchAll(PDO::FETCH_ASSOC); // Menentukan mode fetch
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // Menangkap dan menampilkan error
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = :token");
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>