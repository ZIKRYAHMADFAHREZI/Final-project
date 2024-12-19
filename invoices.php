<?php
session_start();
require 'db/connection.php'; // Pastikan file koneksi benar

try {
    // Query untuk mengambil data terbaru satu pengguna berdasarkan sesi
    $stmt = $pdo->prepare("
        SELECT r.id_reservation, r.start_date, r.to_date, r.total_amount, r.payment_proof, r.status,
               p.method AS payment_method, p.account_name, p.payment_number,
               rm.number_room, rm.id_type
        FROM reservations r
        JOIN pay_methods p ON r.id_pay_method = p.id_pay_method
        JOIN rooms rm ON r.id_room = rm.id_room
        WHERE r.id_user = :id_user
        ORDER BY r.start_date DESC
        LIMIT 1
    ");
    $stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Terjadi kesalahan saat mengambil data: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Invoice</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f4f4f4;
        font-family: 'Arial', sans-serif;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .card-header.bg-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }
    .card-header.bg-success {
        background: linear-gradient(135deg, #28a745, #1e7e34);
    }
    .btn-primary {
        background-color: #007bff;
        border: none;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .alert {
        border-radius: 12px;
    }
    .invoice-icon {
        font-size: 50px;
        color: #007bff;
    }
</style>
</head>
<body>
<div class="container mt-5">
    <div class="text-center mb-4">
        <i class="fa-solid fa-file-invoice invoice-icon"></i>
        <h1 class="mt-2">Detail Invoice</h1>
        <p class="text-muted">Detail informasi pemesanan dan pembayaran Anda.</p>
    </div>

    <?php if (!$reservation): ?>
        <div class="alert alert-info text-center">Belum ada reservasi yang ditemukan untuk pengguna ini.</div>
    <?php else: ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa-solid fa-bed"></i> Detail Reservasi</h5>
            </div>
            <div class="card-body">
                <p><strong>Nomor Reservasi:</strong> <?= htmlspecialchars($reservation['id_reservation']); ?></p>
                <p><strong>Nama Kamar:</strong> <?= htmlspecialchars($reservation['room_name']); ?> (<?= htmlspecialchars($reservation['room_type']); ?>)</p>
                <p><strong>Tanggal Check-in:</strong> <?= htmlspecialchars($reservation['start_date']); ?></p>
                <p><strong>Tanggal Check-out:</strong> <?= htmlspecialchars($reservation['to_date']); ?></p>
                <p><strong>Total Pembayaran:</strong> <span class="text-success">Rp<?= number_format($reservation['total_amount'], 0, ',', '.'); ?></span></p>
                <p><strong>Status:</strong> 
                    <span class="<?= $reservation['status'] === 'Pending' ? 'text-warning' : ($reservation['status'] === 'Confirmed' ? 'text-success' : 'text-danger'); ?>">
                        <?= htmlspecialchars($reservation['status']); ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fa-solid fa-credit-card"></i> Detail Pembayaran</h5>
            </div>
            <div class="card-body">
                <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($reservation['payment_method']); ?></p>
                <p><strong>Nama Akun:</strong> <?= htmlspecialchars($reservation['account_name']); ?></p>
                <p><strong>Nomor Tujuan:</strong> <?= htmlspecialchars($reservation['payment_number']); ?></p>
                <?php if ($reservation['payment_proof']): ?>
                    <p><strong>Bukti Pembayaran:</strong></p>
                    <img src="uploads/<?= htmlspecialchars($reservation['payment_proof']); ?>" alt="Bukti Pembayaran" class="img-fluid rounded shadow">
                <?php else: ?>
                    <p class="text-danger">Bukti pembayaran belum diunggah.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center">
            <a href="payment.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-arrow-left"></i> Kembali ke Pembayaran</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
