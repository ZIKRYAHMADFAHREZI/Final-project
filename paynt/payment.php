<?php
session_start();
require '../db/connection.php'; // Pastikan file koneksi Anda benar

// Mendapatkan data dari form yang dikirimkan
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$id_duration = $_POST['id_duration'];
$method = $_POST['id_pay_method'];
$total_price = $_POST['total-price'];
$id_room = $_POST['number_room'];  // Mendapatkan nomor kamar yang dipilih

// Ambil data id_pay_method dari POST
if (isset($_POST['id_pay_method'])) {
    $id_pay_method = $_POST['id_pay_method'];
} else {
    $error_message = "ID metode pembayaran tidak ditemukan.";
}

// Lanjutkan proses jika id_pay_method tersedia
if (!isset($error_message)) {
    try {
        // Query untuk mendapatkan detail metode pembayaran berdasarkan id_pay_method
        $query = $pdo->prepare("SELECT * FROM pay_methods WHERE id_pay_method = :id_pay_method AND active = 1");
        $query->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
        $query->execute();

        $payment_details = $query->fetch(PDO::FETCH_ASSOC); // Ambil hasil query

        if (!$payment_details) {
            $error_message = "Metode pembayaran tidak ditemukan atau sudah tidak aktif.";
        } else {
            // Data metode pembayaran berhasil ditemukan
            // Bisa digunakan untuk ditampilkan atau diproses lebih lanjut
        }

    } catch (PDOException $e) {
        $error_message = "Terjadi kesalahan saat mengambil data: " . $e->getMessage();
    }
}

// Validasi apakah data yang diperlukan ada
if (isset($start_date, $end_date, $id_duration, $method, $total_price, $id_room)) {
    try {
        // Validasi metode pembayaran
        $query = $pdo->prepare("SELECT * FROM pay_methods WHERE id_pay_method = :id_pay_method AND active = 1");
        $query->bindParam(':id_pay_method', $method, PDO::PARAM_INT);
        $query->execute();

        $payment_details = $query->fetch(PDO::FETCH_ASSOC); // Mengambil hasil query

        // Jika data metode pembayaran tidak ditemukan
        if (!$payment_details) {
            $error_message = "Metode pembayaran tidak ditemukan atau sudah tidak aktif.";
        } else {
            // Data metode pembayaran valid
            // Proses pemesanan atau penyimpanan data ke database
            // Pastikan data pemesanan dapat disimpan dengan benar

            $stmt = $pdo->prepare("INSERT INTO reservations (id_user, id_pay_method, start_date, end_date, id_room, total_price) 
                                   VALUES (:id_user, :id_pay_method, :start_date, :end_date, :id_room, :total_price)");

            $stmt->bindParam(':id_user', $_SESSION['id_user']);
            $stmt->bindParam(':id_pay_method', $method);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->bindParam(':id_room', $id_room);
            $stmt->bindParam(':total_price', $total_price);

            $stmt->execute();

            // Setelah data berhasil disimpan, tampilkan pesan sukses
            $success_message = "Pemesanan berhasil. Silakan lanjutkan pembayaran.";
        }

    } catch (PDOException $e) {
        // Menangani kesalahan jika ada masalah dengan query SQL
        $error_message = "Terjadi kesalahan saat memproses pemesanan: " . $e->getMessage();
    }
} else {
    $error_message = "Data pemesanan tidak lengkap.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Detail Pembayaran</h1>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
    <?php elseif (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
    <?php endif; ?>

    <!-- Tampilkan informasi pemesanan jika berhasil -->
    <?php if (isset($success_message)): ?>
        <div class="card mt-3">
            <div class="card-body">
                <h5><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($payment_details['method']); ?></h5>
                <h6><strong>Total Pembayaran:</strong> Rp <?= number_format($total_price, 0, ',', '.'); ?></h6>
                <form action="invoices.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="payment_proof">Bukti Pembayaran</label>
                        <input type="file" class="form-control-file" name="payment_proof" required>
                    </div>
                    <button type="submit" name="submit-payment" class="btn btn-primary">Kirim</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
