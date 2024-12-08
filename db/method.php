<?php 
require 'connection.php';
// Pastikan parameter id_pay ada di URL
if (isset($_GET['id_pay_method']) && is_numeric($_GET['id_pay_method'])) {
    $id_pay_method = intval($_GET['id_pay_method']);

    // Ambil data metode pembayaran berdasarkan id_pay_method
    $stmt = $pdo->prepare("SELECT method, no_pay, name_acc FROM pay_methods WHERE id_pay_method = ?");
    $stmt->execute([$id_pay_method]);
    
    // Jika data ditemukan
    if ($stmt->rowCount() > 0) {
        $payment_details = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        die("Data tidak ditemukan.");
    }
} else {
    die("Parameter id_pay_method tidak valid.");
}
?>