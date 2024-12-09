<?php 
require '../db/connection.php';
$query = $pdo->prepare("SELECT * FROM pay_methods WHERE active = 1");
$query->execute();
$methods = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <!-- Link ke CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" type="png" href="../img/icon.png">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Pilih Metode Pembayaran</h1>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group mt-5">
                <?php if (!empty($methods)) : ?> <!-- Mengecek apakah ada metode pembayaran -->
                    <?php foreach ($methods as $pm) : ?> <!-- Iterasi setiap metode pembayaran -->
                        <li class="list-group-item">
                            <a href="payment.php?id_pay_method=<?= htmlspecialchars($pm['id_pay_method']); ?>" class="btn btn-primary btn-block">
                                <?= htmlspecialchars($pm['method']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li class="list-group-item text-danger">Tidak ada metode pembayaran yang tersedia.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

    <!-- Script Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
