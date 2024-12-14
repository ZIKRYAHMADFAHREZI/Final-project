<?php
session_start();
require '../db/connection.php'; // Pastikan file koneksi Anda benar

// Cek apakah data yang diperlukan ada
if (isset($_POST['start_date'], $_POST['to_date'], $_POST['id_duration'], $_POST['id_pay_method'], $_POST['total_amount'], $_POST['number_room'])) {
    // Mendapatkan data dari form yang dikirimkan
    $start_date = $_POST['start_date'];
    $to_date = $_POST['to_date'];
    $id_duration = $_POST['id_duration'];
    $id_pay_method = $_POST['id_pay_method'];
    $total_amount = $_POST['total_amount'];
    $id_room = $_POST['number_room'];  // Mendapatkan nomor kamar yang dipilih

    // Validasi apakah metode pembayaran ada
    try {
        $query = $pdo->prepare("SELECT * FROM pay_methods WHERE id_pay_method = :id_pay_method AND active = 1");
        $query->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
        $query->execute();

        $payment_details = $query->fetch(PDO::FETCH_ASSOC); // Ambil hasil query

        if (!$payment_details) {
            throw new Exception("Metode pembayaran tidak ditemukan atau sudah tidak aktif.");
        }

    } catch (PDOException $e) {
        $error_message = "Terjadi kesalahan saat mengambil data: " . $e->getMessage();
    }

    // Jika tidak ada error, simpan data pemesanan
    if (!isset($error_message)) {
        try {
            // Proses pemesanan atau penyimpanan data ke database
            $stmt = $pdo->prepare("INSERT INTO reservations (id_user, id_pay_method, start_date, to_date, id_room, total_amount) 
                                   VALUES (:id_user, :id_pay_method, :start_date, :to_date, :id_room, :total_amount)");

            $stmt->bindParam(':id_user', $_SESSION['id_user']);
            $stmt->bindParam(':id_pay_method', $id_pay_method);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':to_date', $to_date);
            $stmt->bindParam(':id_room', $id_room);
            $stmt->bindParam(':total_amount', $total_amount);

            $stmt->execute();

            // Ambil id_reservation untuk pemesanan yang baru
            $id_reservation = $pdo->lastInsertId();

            $success_message = "Pemesanan berhasil. Silakan lanjutkan pembayaran.";

        } catch (PDOException $e) {
            // Menangani kesalahan jika ada masalah dengan query SQL
            $error_message = "Terjadi kesalahan saat memproses pemesanan: " . $e->getMessage();
        }
    }
} else {
    $error_message = "Data pemesanan tidak lengkap.";
}

// Menangani unggah bukti pembayaran
if (isset($_POST['submit-payment']) && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {
    $file_tmp = $_FILES['payment_proof']['tmp_name'];
    $file_name = $_FILES['payment_proof']['name'];
    $file_size = $_FILES['payment_proof']['size'];
    $file_type = $_FILES['payment_proof']['type'];

    // Tentukan direktori tujuan untuk menyimpan file
    $upload_dir = '../uploads/';
    $target_file = $upload_dir . basename($file_name);

    // Cek apakah file bisa dipindahkan ke direktori tujuan
    if (move_uploaded_file($file_tmp, $target_file)) {
        // Simpan informasi file di database, misalnya ID pemesanan dan nama file
        try {
            $stmt = $pdo->prepare("UPDATE reservations SET payment_proof = :payment_proof WHERE id_reservation = :id_reservation");
            $stmt->bindParam(':payment_proof', $file_name);
            $stmt->bindParam(':id_reservation', $id_reservation); // Pastikan $id_reservation sudah ada
            $stmt->execute();

            $payment_upload_message = "Bukti pembayaran berhasil diunggah.";
        } catch (PDOException $e) {
            $payment_upload_message = "Terjadi kesalahan saat mengunggah bukti pembayaran: " . $e->getMessage();
        }
    } else {
        $payment_upload_message = "Terjadi kesalahan saat mengunggah file.";
    }
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
                <h6><strong>Total Pembayaran:</strong> Rp <?= number_format($total_amount, 0, ',', '.'); ?></h6>

                <!-- Form untuk mengirimkan bukti pembayaran -->
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="payment_proof">Bukti Pembayaran</label>
                        <input type="file" class="form-control-file" name="payment_proof" required>
                    </div>
                    <button type="submit" name="submit-payment" class="btn btn-primary">Kirim</button>
                </form>
                
                <?php if (isset($payment_upload_message)): ?>
                    <div class="alert alert-info mt-3"><?= htmlspecialchars($payment_upload_message); ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
