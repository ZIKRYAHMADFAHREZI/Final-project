<?php
session_start();
require 'db/connection.php';

// Periksa apakah pengguna sudah login
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Jika sudah login, arahkan ke dashboard
    header('Location: dashboard/index.php');
    exit;
}


class UserSession {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function fetchTypes() {
        try {
            $result = $this->pdo->query("SELECT * FROM types");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // Menangkap dan menampilkan error
            return [];
        }
    }

    public function handleRememberToken() {
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            try {
                $stmt = $this->pdo->prepare("SELECT * FROM users WHERE remember_token = :token");
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
    }
}

// Contoh penggunaan
$userSession = new UserSession($pdo);
$types = $userSession->fetchTypes();
$userSession->handleRememberToken();
?>
