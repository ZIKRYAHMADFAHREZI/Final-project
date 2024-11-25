<?php 
require '../db/connection.php';

// Pastikan parameter id_pay ada di URL
if (isset($_GET['id_pay']) && is_numeric($_GET['id_pay'])) {
    $id_pay = intval($_GET['id_pay']);

    // Ambil data metode pembayaran berdasarkan id_pay
    $stmt = $pdo->prepare("SELECT method, no_pay, name_acc FROM pay_methods WHERE id_pay = ?");
    $stmt->execute([$id_pay]);
    
    // Jika data ditemukan
    if ($stmt->rowCount() > 0) {
        $payment_details = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Detail Metode Pembayaran</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Metode:</strong> <?= htmlspecialchars($payment_details['method']); ?></p>
                <p><strong>No. Tujuan:</strong> <?= htmlspecialchars($payment_details['no_pay']); ?></p>
                <p><strong>Atas Nama:</strong> <?= htmlspecialchars($payment_details['name_acc']); ?></p>
                <form action="../done.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="sender_name"></label>
                        <input type="text" class="form-control" name="sender_name" placeholder="Nama Pengirim" required>
                    </div>
                    <div class="form-group">
                        <label for="file">Bukti Pembayaran</label>
                        <input type="file" class="form-control-file" name="payment_proof" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>