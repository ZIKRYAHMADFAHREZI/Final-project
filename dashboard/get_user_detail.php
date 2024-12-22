<?php
header('Content-Type: application/json');
require_once '../db/connection.php'; // Ganti dengan file koneksi database Anda

$username = $_GET['username'] ?? null;

if (!$username) {
    echo json_encode(['error' => 'Username is required']);
    exit;
}

$query = "SELECT username, email, first_name, last_name, phone_number, date_of_birth 
          FROM user_profile 
          WHERE username = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement']);
    exit;
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo json_encode(['error' => 'User not found']);
}
?>
