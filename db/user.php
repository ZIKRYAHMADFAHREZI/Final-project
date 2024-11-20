<?php
// user_profile.php

// Menghubungkan ke database
include 'connection.php';

// Memeriksa apakah user_id telah diset
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Mengambil data profil pengguna
    $sql = "SELECT u.id, u.username, u.email, p.first_name, p.last_name, p.phone_number, p.date_of_birth 
            FROM users u 
            JOIN profile_user p ON u.id = p.user_id 
            WHERE u.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Menampilkan data profil pengguna
        $user = $result->fetch_assoc();
        echo "<h1>Profil Pengguna</h1>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($user['username']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
        echo "<p><strong>Nama Depan:</strong> " . htmlspecialchars($user['first_name']) . "</p>";
        echo "<p><strong>Nama Belakang:</strong> " . htmlspecialchars($user['last_name']) . "</p>";
        echo "<p><strong>No HP:</strong> " . htmlspecialchars($user['phone_number']) . "</p>";
        echo "<p><strong>Tanggal Lahir:</strong> " . htmlspecialchars($user['date_of_birth']) . "</p>";
        echo "<a href='user_profile_form.php?user_id=" . $user['id'] . "'>Edit Profil</a>";
    } else {
        echo "Profil pengguna tidak ditemukan.";
    }

    $stmt->close();
} else {
    echo "User  ID tidak diset.";
}

$conn->close();
?>