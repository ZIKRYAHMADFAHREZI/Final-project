<?php 

require 'connection.php';

try {
    // Ambil data dari database
    $stmt = $pdo->query("SELECT id_pay_method, method FROM pay_methods");
    
    // Ambil data sebagai array asosiatif
    $pay_methods = $stmt->fetchAll(PDO::FETCH_ASSOC); 

} catch (PDOException $e) {
    // Tangani kesalahan jika query gagal
    echo "Error: " . $e->getMessage();
    $pay_methods = []; // Atur default jika tidak ada data
}
?>