<?php 
require '../db/connection.php';

// Pastikan parameter id_pay ada di URL
if (isset($_GET['id_pay']) && is_numeric($_GET['id_pay'])) {
    $id_pay = intval($_GET['id_pay']);

    // Ambil data metode pembayaran berdasarkan id_pay
    $stmt = $conn->prepare("SELECT method, no_pay, name_acc FROM pay_methods WHERE id_pay = ?");
    $stmt->bind_param("i", $id_pay);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan
    if ($result->num_rows > 0) {
        $payment_details = $result->fetch_assoc();
    } else {
        die("Data tidak ditemukan.");
    }
} else {
    die("Parameter id_pay tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Metode Pembayaran</title>
</head>
<body>
    <h1>Detail Metode Pembayaran</h1>
    <p>Metode: <?= ($payment_details['method']); ?></p>
    <p>No. Tujuan: <?= ($payment_details['no_pay']); ?></p>
    <p>Atas Nama: <?= ($payment_details['name_acc']); ?></p>
    <form action="">
    <input type="text" name="sender_name" placeholder="Nama Pengirim">
        <label for="file">Bukti Pembayaran</label>
        <input type="file" name="payment_proof">
        <button type="submit">Kirim</button>
    </form>
</body>
</html>
