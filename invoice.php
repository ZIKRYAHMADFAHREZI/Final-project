<?php 
require 'db/functions/invoice.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Invoice</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- bootsrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<link rel="stylesheet" href="css/invoice.css">
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
        <div class="row">
            <!-- Reservation Section -->
            <div class="col-lg-6 order-lg-1 mb-4">
                <div class="card" id="reservation">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-bed"></i> Detail Reservasi</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nomor Reservasi:</strong> <?= htmlspecialchars($reservation['id_reservation']); ?></p>
                        <p><strong>Nama Kamar:</strong> <?= htmlspecialchars($reservation['name_type']); ?></p>
                        <p><strong>Nomor Kamar:</strong> <?= htmlspecialchars($reservation['number_room']); ?></p>
                        <p><strong>Tanggal Mulai:</strong> <?= htmlspecialchars(date('d F Y', strtotime($reservation['start_date']))); ?></p>
                        <p><strong>Tanggal Selesai:</strong> <?= $reservation['to_date'] !== null ? htmlspecialchars((date('d F Y', strtotime($reservation['to_date'])))) : ''; ?></p>
                        <p><strong>Tanggal Check-in:</strong>
                            <?= ($reservation['check_in_date'] !== null && $reservation['check_in_date'] !== '1970-01-01 00:00:00') ? htmlspecialchars($reservation['check_in_date']) : ''; ?>
                        </p>
                        <p><strong>Tanggal Check-out:</strong>
                            <?= ($reservation['check_out_date'] !== null && $reservation['check_out_date'] !== '1970-01-01 00:00:00') ? htmlspecialchars($reservation['check_out_date']) : ''; ?>
                        </p>
                        <p><strong>Total Pembayaran:</strong> <span class="text-success">Rp<?= number_format($reservation['total_amount'], 0, ',', '.'); ?></span></p>
                        <p><strong>Status:</strong>
                            <span class="<?= $reservation['status'] === 'Pending' ? 'text-warning' : ($reservation['status'] === 'Confirmed' ? 'text-success' : 'text-danger'); ?>">
                                <?= htmlspecialchars($reservation['status']); ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="col-lg-6 order-lg-2 mb-4">
                <div class="card" id="payment">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa-solid fa-credit-card"></i> Detail Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($reservation['payment_method']); ?></p>
                        <p><strong>Nama Akun:</strong> <?= htmlspecialchars($reservation['account_name']); ?></p>
                        <p><strong>Nomor Tujuan:</strong> <?= htmlspecialchars($reservation['payment_number']); ?></p>
                        <p><strong>Bukti Pembayaran:</strong></p>
                        <?php echo htmlspecialchars($reservation['payment_proof']);?>
                        <img src="paynt/uploads/<?= htmlspecialchars($reservation['payment_proof']); ?>" alt="Bukti Pembayaran" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="index.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-arrow-left"></i> Kembali ke Home</a>
        </div>
        <br><br>
    <?php endif; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>