<?php
require 'db/connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($_POST['username']) || isset($data['username'])) {
        $username = isset($_POST['username']) ? $_POST['username'] : $data['username'];

        // Query untuk mengecek apakah username sudah ada
        $stmt = $pdo->prepare("SELECT id_user FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        // Jika username ditemukan
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'unavailable', 'exists' => true]);
        } else {
            echo json_encode(['status' => 'available', 'exists' => false]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>
