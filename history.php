<?php 
session_start();
require 'db/connection.php'; // Pastikan file koneksi Anda benar

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php'); // Jika belum login, redirect ke halaman login
    exit();
}

// Ambil data reservation dari database
try {
    $id_user = $_SESSION['id_user'];
    // Query untuk mengambil data pemesanan berdasarkan id_user, urutkan berdasarkan start_date terbaru
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id_user = :id_user ORDER BY start_date DESC");
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil data pemesanan
} catch (PDOException $e) {
    $error_message = "Terjadi kesalahan saat mengambil data pemesanan: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>History Pemesanan</title>
<!-- Menggunakan Bootstrap dan Font Awesome -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    body {
        background-color: #f4f4f4;
    }
    .container {
        margin-top: 50px;
    }
    .table thead {
        background-color: #6c757d; /* Abu-abu */
        color: white;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-custom {
        border-radius: 20px;
        padding: 10px 20px;
    }
    .alert-custom {
        border-radius: 10px;
        font-size: 16px;
    }
    .card-header {
        background-color: #6c757d; /* Abu-abu */
        color: white;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #e9ecef; /* Abu-abu muda */
    }
</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center">History Pemesanan</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger alert-custom"><?= htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>ID Kamar</th>
                                    <th>Total Pembayaran</th>
                                    <th>Status Pembayaran</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($reservations) > 0): ?>
                                    <?php foreach ($reservations as $index => $reservation): ?>
                                        <tr>
                                            <td><?= $index + 1; ?></td>
                                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($reservation['start_date']))); ?></td>
                                            <td><?= $reservation['to_date'] ? htmlspecialchars(date('d-m-Y', strtotime($reservation['to_date']))) : '-'; ?></td>
                                            <td><?= htmlspecialchars($reservation['id_room']); // Ganti dengan nama kamar jika tersedia ?></td>
                                            <td>Rp<?= number_format($reservation['total_amount'], 0, ',', '.'); ?></td>
                                            <td><?= $reservation['payment_proof'] ? 'Sudah Dibayar' : 'Belum Dibayar'; ?></td>
                                            <td>
                                                <?php if (!$reservation['payment_proof']): ?>
                                                    <a href="upload_payment.php?id_reservation=<?= $reservation['id_reservation']; ?>" class="btn btn-light btn-sm btn-custom">
                                                        <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                                                    </a>
                                                <?php else: ?>
                                                    <a href="view_payment.php?id_reservation=<?= $reservation['id_reservation']; ?>" class="btn btn-success btn-sm btn-custom">
                                                        <i class="fas fa-eye"></i> Lihat Bukti Pembayaran
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pemesanan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>