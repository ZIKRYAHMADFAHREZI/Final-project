<?php 
require '../db/method.php';
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
                <p><strong>Bayar Senjumlah:</strong> <?= htmlspecialchars($payment_details['name_acc']); ?></p>
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