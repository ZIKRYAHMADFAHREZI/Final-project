<?php
// Sertakan file koneksi database
include('ahah.php');

// Pastikan koneksi berhasil sebelum melanjutkan
if (!$pdo) {
    die("Failed to connect to database.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $paymentMethodId = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $userId = 1;  // Gantilah dengan ID pengguna yang sebenarnya (misalnya berdasarkan sesi login)

    // Simpan transaksi pembayaran ke database
    try {   
        $query = $pdo->prepare("INSERT INTO user_payments (user_id, payment_method_id, amount) VALUES (:user_id, :payment_method_id, :amount)");
        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $query->bindParam(':payment_method_id', $paymentMethodId, PDO::PARAM_INT);
        $query->bindParam(':amount', $amount, PDO::PARAM_STR);
        $query->execute();

        // Ambil ID transaksi yang baru disimpan
        $paymentId = $pdo->lastInsertId();
        
        // Ambil detail pembayaran yang dipilih
        $query = $pdo->prepare("SELECT * FROM payment_methods WHERE id = :id");
        $query->bindParam(':id', $paymentMethodId, PDO::PARAM_INT);
        $query->execute();
        $selectedMethod = $query->fetch(PDO::FETCH_ASSOC);

        if ($selectedMethod) {
            // Menampilkan konfirmasi transaksi
            echo "Transaction successful! <br>";
            echo "You selected: " . htmlspecialchars($selectedMethod['method_name']) . "<br>";
            echo "Amount paid: " . htmlspecialchars($amount) . "<br>";
            echo "Transaction ID: " . $paymentId . "<br>";
        }
    } catch (PDOException $e) {
        echo "Error processing payment: " . $e->getMessage();
    }
}
?>
