<?php
session_start();
require '../db/connection.php'; // Pastikan file koneksi Anda benar

// Mengambil id_pay_method dari parameter URL
if (isset($_GET['id_pay_method'])) {
    $id_pay_method = $_GET['id_pay_method'];

    try {
        // Query untuk mendapatkan detail metode pembayaran berdasarkan id_pay_method
        $query = $pdo->prepare("SELECT * FROM pay_methods WHERE id_pay_method = :id_pay_method AND active = 1");
        $query->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
        $query->execute();
        
        $payment_details = $query->fetch(PDO::FETCH_ASSOC); // Mengambil hasil query

        // Jika data metode pembayaran tidak ditemukan
        if (!$payment_details) {
            $error_message = "Metode pembayaran tidak ditemukan atau sudah tidak aktif.";
        }
    } catch (PDOException $e) {
        // Menangani kesalahan jika ada masalah dengan query SQL
        $error_message = "Terjadi kesalahan saat mengambil data dari database: " . $e->getMessage();
    }
} else {
    // Jika id_pay_method tidak disertakan di URL
    $error_message = "ID metode pembayaran tidak ditemukan.";
}

// Fungsi untuk memindahkan file gambar (bukti pembayaran)
function uploadPaymentProof($file) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Validasi apakah file gambar
    if (getimagesize($file["tmp_name"]) === false) {
        return "File bukan gambar.";
    }
    
    // Validasi ukuran file (maksimal 5MB)
    if ($file["size"] > 5000000) {
        return "Ukuran file terlalu besar.";
    }
    
    // Validasi tipe file
    if (!in_array($imageFileType, ["jpg", "png", "jpeg"])) {
        return "Hanya file JPG, PNG, dan JPEG yang diizinkan.";
    }
    
    // Coba upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        return "Terjadi kesalahan saat mengunggah file.";
    }
}

// Proses pengiriman bukti pembayaran
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['payment_proof'])) {
        $payment_proof = $_FILES['payment_proof'];

        // Cek apakah file upload berhasil
        $uploadedFile = uploadPaymentProof($payment_proof);
        if (strpos($uploadedFile, 'uploads') === false) {
            $error_message = $uploadedFile;  // Menampilkan pesan error jika upload gagal
        } else {
            // Data berhasil di-upload, sekarang simpan ke database
            try {
                // Menyimpan data pembayaran dan bukti ke tabel reservations
                $stmt = $pdo->prepare("INSERT INTO resevations (id_user, id_pay_method, payment_proof, created_at) VALUES (:id_user, :id_pay_method, :payment_proof, NOW())");
                $stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
                $stmt->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
                $stmt->bindParam(':payment_proof', $uploadedFile, PDO::PARAM_STR);
                $stmt->execute();

                // Menampilkan pesan sukses
                $success_message = "Bukti pembayaran berhasil dikirim dan disimpan.";
            } catch (PDOException $e) {
                $error_message = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Bukti pembayaran tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Metode Pembayaran</title>
    <!-- Link ke CSS Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Detail Metode Pembayaran</h1>

    <div class="card mt-3">
        <div class="card-body">
            <?php if (isset($error_message)): ?>
                <!-- Menampilkan pesan error jika ada -->
                <p class="text-danger"><?= htmlspecialchars($error_message); ?></p>
            <?php elseif (isset($success_message)): ?>
                <!-- Menampilkan pesan sukses jika upload berhasil -->
                <p class="text-success"><?= htmlspecialchars($success_message); ?></p>
            <?php elseif (isset($payment_details)): ?>
                <!-- Menampilkan detail metode pembayaran jika data ditemukan -->
                <p><strong>Metode Pembayaran:</strong> <?= htmlspecialchars($payment_details['method']); ?></p>
                <p><strong>No. Pembayaran:</strong> <?= htmlspecialchars($payment_details['payment_number']); ?></p>
                <p><strong>Atas Nama:</strong> <?= htmlspecialchars($payment_details['account_name']); ?></p>
                <p><strong>Total Bayar:</strong> <?= htmlspecialchars($payment_details['account_name']); ?></p>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file">Bukti Pembayaran</label>
                        <input type="file" class="form-control-file" name="payment_proof" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
