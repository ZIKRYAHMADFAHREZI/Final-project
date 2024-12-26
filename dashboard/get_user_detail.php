<?php
header('Content-Type: application/json');
require_once '../db/connection.php'; // Ganti dengan file koneksi database Anda

$username = $_GET['username'] ?? null;

if (!$username) {
    echo json_encode(['error' => 'Username is required']);
    exit;
}

try {
    $query = "SELECT username, email, first_name, last_name, phone_number, date_of_birth 
              FROM user_profile 
              WHERE username = ?";
    $stmt = $pdo->prepare($query); // Pastikan $pdo berasal dari connection.php
    $stmt->bindParam(1, $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
