<?php
require 'ahah.php';
$query = $pdo->prepare("SELECT * FROM payment_methods WHERE is_active = 1");
$query->execute();
$methods = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="process_payment.php" method="POST">
    <h3>Select Payment Method</h3>
    
    <!-- Menampilkan metode pembayaran yang tersedia -->
    <?php foreach ($methods as $method): ?>
        <div>
            <input type="radio" id="method-<?php echo $method['id']; ?>" name="payment_method" value="<?php echo $method['id']; ?>" required>
            <label for="method-<?php echo $method['id']; ?>">
                <?php echo htmlspecialchars($method['method_name']); ?> - <?php echo htmlspecialchars($method['account_info']); ?>
            </label>
        </div>
    <?php endforeach; ?>

    <!-- Input jumlah pembayaran -->
    <label for="amount">Amount to Pay:</label>
    <input type="number" name="amount" id="amount" min="0" step="0.01" required>

    <button type="submit">Proceed to Payment</button>
</form>


</body>
</html>