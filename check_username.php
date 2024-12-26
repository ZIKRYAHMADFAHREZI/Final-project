<?php
require 'db/connection.php';

class UsernameChecker {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function isUsernameAvailable($username) {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] == 0; // Mengembalikan true jika username tersedia
    }
}

// Memeriksa apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        echo json_encode(['status' => 'error', 'message' => 'Username tidak boleh kosong.']);
        exit;
    }

    $usernameChecker = new UsernameChecker($pdo);

    if ($usernameChecker->isUsernameAvailable($username)) {
        echo json_encode(['status' => 'available']);
    } else {
        echo json_encode(['status' => 'unavailable']);
    }
}
?>
