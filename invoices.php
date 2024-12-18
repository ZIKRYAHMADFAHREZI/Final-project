<?php
session_start();
require 'db/connection.php';

$id_reservation = $_GET['id_reservation']; // Misalnya, ID reservasi diterima melalui URL GET
try {
    // Query untuk mengambil data pemesanan berdasarkan id_reservation
    $query = $pdo->prepare("
        SELECT r.*, p.method, p.payment_number, p.account_name, rt.room_name, rt.room_rate, r.status
        FROM reservations r
        LEFT JOIN pay_methods p ON r.id_pay_method = p.id_pay_method
        LEFT JOIN room_types rt ON r.id_type = rt.id_type
        WHERE r.id_reservation = :id_reservation AND r.active = 1
    ");
    $query->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
    $query->execute();
    $reservation_details = $query->fetch(PDO::FETCH_ASSOC);

    if (!$reservation_details) {
        $error_message = "Data reservasi tidak ditemukan.";
    }

} catch (PDOException $e) {
    $error_message = "Terjadi kesalahan saat mengambil data: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Reservasi Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8">
        <div class="card">
          <div class="card-header text-center bg-primary text-white">
            <h4>INVOICE RESERVASI HOTEL</h4>
          </div>
          <div class="card-body">
            <!-- Header Invoice -->
            <div class="row">
              <div class="col-6">
                <h5>Hotel Grand Mutiara</h5>
                <p>Alamat: Jl. Raya No. 123, Kota Maluku Utara</p>
                <p>Email: yandex</p>
              </div>
              <div class="col-6 text-end">
                <h5>Invoice #<?= $reservation_details['id_reservation']; ?></h5>
                <p>Tanggal: <?= date('d M Y') ?></p>
                <!-- Menggunakan id_duration untuk menghitung tanggal cek out -->
                <p>Cek out: <?= date('d M Y', strtotime('+' . $reservation_details['id_duration'] . ' days')); ?></p>
              </div>
            </div>
            
            <!-- Data Pelanggan -->
            <div class="row mt-4">
              <div class="col-12">
                <h6><strong>Data Pelanggan</strong></h6>
                <p>Nama: <?= htmlspecialchars($_SESSION['username']); ?></p> <!-- Asumsi nama pelanggan ada di session -->
                <p>Email: <?= htmlspecialchars($_SESSION['email']); ?></p>
                <p>Telepon: <?= htmlspecialchars($_SESSION['phone_number']); ?></p>
              </div>
            </div>

            <!-- Detail Pemesanan -->
            <div class="row mt-4">
              <div class="col-12">
                <h6><strong>Detail Pemesanan</strong></h6>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Nama Kamar</th>
                      <th>Jumlah Malam</th>
                      <th>Harga Per Malam</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?= htmlspecialchars($reservation_details['room_name']); ?></td>
                      <td><?= htmlspecialchars($reservation_details['id_duration']); ?> Malam</td>
                      <td>Rp <?= number_format($reservation_details['room_rate'], 0, ',', '.'); ?></td>
                      <!-- Menghitung total biaya berdasarkan room_rate dan id_duration -->
                      <td>Rp <?= number_format($reservation_details['room_rate'] * $reservation_details['id_duration'], 0, ',', '.'); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Total Biaya -->
            <div class="row mt-4">
              <div class="col-12 text-end">
                <h5><strong>Total Pembayaran: Rp <?= number_format($reservation_details['total_amount'], 0, ',', '.'); ?></strong></h5>
                </div>
            </div>

            <!-- Pembayaran -->
            <div class="row mt-4">
              <div class="col-12">
                <h6><strong>Metode Pembayaran:</strong></h6>
                <p><strong>Metode:</strong> <?= htmlspecialchars($reservation_details['method']); ?></p>
                <p><strong>No. Pembayaran:</strong> <?= htmlspecialchars($reservation_details['payment_number']); ?></p>
                <p><strong>Atas Nama:</strong> <?= htmlspecialchars($reservation_details['account_name']); ?></p>
              </div>
            </div>

            <!-- Status Pembayaran -->
            <div class="row mt-4">
              <div class="col-12">
                <h6><strong>Status Pembayaran:</strong></h6>
                <p class="text-<?= $reservation_details['status'] == 'pending' ? 'warning' : 'success'; ?>">
                  <strong><?= ucfirst($reservation_details['status']); ?></strong>
                </p>
              </div>
            </div>

          </div>
          <div class="card-footer text-center">
            <p class="mb-0">Terima kasih telah menginap di Hotel Grand Mutiara</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
