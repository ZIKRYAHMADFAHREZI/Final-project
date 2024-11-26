<?php 
require '../db/connection.php';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Metode Pembayaran</title>
    <!-- Link ke CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Pilih Metode Pembayaran</h1>
        <div class="row">
            <div class="col-md-12">
                <ul class="list-group mt-5">
                    <?php if (!empty($pay_methods)) : ?>
                        <?php foreach ($pay_methods as $pm) : ?>
                            <li class="list-group-item">
                                <a href="method.php?id_pay_method=<?= htmlspecialchars($pm['id_pay_method']); ?>" class="btn btn-primary btn-block">
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