<?php 
require '../db/connection.php';
try {
    // Mengecek apakah form telah disubmit
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mengambil data dari form 
        $id_payment = htmlspecialchars(trim($_POST['id_payment']));
        $payment_proof = $_FILES['payment_proof'];
        $id_user = $_SESSION['user_id']; // Assuming session holds user ID

        // Validasi: Pastikan ID pembayaran dan ID User tidak kosong
        if (empty($id_payment) || empty($id_user)) {
            echo "<p style='color: red;'>ID Pembayaran dan ID User harus diisi.</p>";
        } else {
            // Validasi file bukti pembayaran (misalnya, hanya menerima gambar)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // Maksimal 5MB
            $file_name = $payment_proof['name'];
            $file_tmp = $payment_proof['tmp_name'];
            $file_size = $payment_proof['size'];
            $file_error = $payment_proof['error'];

            // Cek apakah ada error dalam upload
            if ($file_error !== 0) {
                echo "<p style='color: red;'>Terjadi kesalahan saat mengunggah file.</p>";
            } elseif ($file_size > $max_size) {
                echo "<p style='color: red;'>Ukuran file terlalu besar. Maksimal 5MB.</p>";
            } elseif (!in_array($payment_proof['type'], $allowed_types)) {
                echo "<p style='color: red;'>Tipe file tidak valid. Hanya gambar yang diizinkan (JPG, PNG, GIF).</p>";
            } else {
                // Meng-upload file ke direktori 'uploads'
                $upload_dir = 'uploads/';
                $file_path = $upload_dir . basename($file_name);
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Data form lain yang telah tersedia dari sebelumnya
                    $payment_method = htmlspecialchars($payment_details['method']);
                    $payment_number = htmlspecialchars($payment_details['no_pay']);
                    $account_name = htmlspecialchars($payment_details['name_acc']);
                    $payment_amount = htmlspecialchars($payment_details['amount']); // Pastikan ada data ini

                    // Menyiapkan query SQL untuk menyimpan data ke database
                    $sql = "INSERT INTO resevations (id_resevation, id_user, id_room, date, total_price, create_at, destroy_at, id_type, id_room_rate, id_payment, id_transit, status, created_at, name_send, payment_proof)
                            VALUES (:id_resevation, :id_user, :id_room, :date, :total_price, :create_at, :destroy_at, :id_type, :id_room_rate, :id_payment, :d_transit, :status, :created_at, :name_send, :payment_proof)";
                    
                    $stmt = $pdo->prepare($sql);

                    // Mengikat parameter
                    $stmt->bindParam(':id_user', $id_user);
                    $stmt->bindParam(':id_payment', $id_payment);
                    $stmt->bindParam(':id_pay_method', $id_pay_method);
                    $stmt->bindParam(':no_pay', $no_pay);
                    $stmt->bindParam(':name_acc', $name_acc);
                    $stmt->bindParam(':payment_amount', $payment_amount);
                    $stmt->bindParam(':payment_proof', $file_path);

                    // Menjalankan query
                    $stmt->execute();
                    echo "<p>Data berhasil disimpan ke dalam database!</p>";
                } else {
                    echo "<p style='color: red;'>Gagal mengunggah bukti pembayaran.</p>";
                }
            }
        }
    }
} catch (PDOException $e) {
    // Menampilkan pesan error jika terjadi kesalahan
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Metode Pembayaran</title>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="png" href="../img/icon.png">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">Detail Metode Pembayaran</h1>
        <div class="card">
            <div class="card-body">
                <p><strong>Metode:</strong> <?= htmlspecialchars($payment_details['method']); ?></p>
                <p><strong>No. Tujuan:</strong> <?= htmlspecialchars($payment_details['no_pay']); ?></p>
                <p><strong>Atas Nama:</strong> <?= htmlspecialchars($payment_details['name_acc']); ?></p>
                <p><strong>Bayar Sejumlah:</strong> <?= htmlspecialchars($payment_details['amount']); ?></p>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="id_payment">ID Pembayaran</label>
                        <input type="text" class="form-control" name="id_payment" placeholder="ID Pembayaran" required>
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
