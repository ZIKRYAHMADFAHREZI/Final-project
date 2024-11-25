<?php 
require '../db/connection.php';

try {
    // Ambil data dari database
    $stmt = $pdo->query("SELECT id_pay, method FROM pay_methods");
    
    // Ambil data sebagai array asosiatif
    $pay_methods = $stmt->fetchAll(PDO::FETCH_ASSOC); 

} catch (PDOException $e) {
    // Tangani kesalahan jika query gagal
    echo "Error: " . $e->getMessage();
    $pay_methods = []; // Atur default jika tidak ada data
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
</head>
<body>
    <h1>Pilih Metode Pembayaran</h1>
    <ul>
        <?php if (!empty($pay_methods)) : ?>
            <?php foreach ($pay_methods as $pm) : ?>
                <li>
                    <a href="method.php?id_pay=<?= htmlspecialchars($pm['id_pay']); ?>"> <!-- Link ke method.php dengan id_pay -->
                        <?= htmlspecialchars($pm['method']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>Tidak ada metode pembayaran yang tersedia.</li>
        <?php endif; ?>
    </ul>
</body>
</html>