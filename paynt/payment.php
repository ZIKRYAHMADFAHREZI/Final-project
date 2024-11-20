<?php 
require '../db/connection.php';

// Ambil data dari database
$result = $conn->query("SELECT id_pay, method FROM pay_methods");

// Pastikan query berhasil
if ($result && $result->num_rows > 0) {
    $pay_methods = $result->fetch_all(MYSQLI_ASSOC); // Ambil data sebagai array asosiatif
} else {
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
        <?php foreach ($pay_methods as $pm) : ?>
            <li>
                <a href="method.php?id_pay=<?= $pm['id_pay']; ?>"> <!-- Link ke method.php dengan id_pay -->
                    <?= $pm['method']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
