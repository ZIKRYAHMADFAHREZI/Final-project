<?php 
require 'connection.php';
try {
    $result = $pdo->query("SELECT * FROM types");
    $types = $result->fetchAll(PDO::FETCH_ASSOC); // Menentukan mode fetch
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage(); // Menangkap dan menampilkan error
}
?>