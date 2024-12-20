<?php
session_start();
require '../db/connection.php'; // Pastikan file koneksi Anda benar

// Daftar ekstensi file yang diperbolehkan
$allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];

// Fungsi untuk mengupload bukti pembayaran
function uploadPaymentProof($file) {
    global $allowed_extensions;

    $file_tmp = $file['tmp_name'];
    $original_file_name = $file['name'];
    $file_extension = pathinfo($original_file_name, PATHINFO_EXTENSION);
    $file_extension = strtolower($file_extension); // Convert extension to lowercase

    // Cek apakah ekstensi file diperbolehkan
    if (!in_array($file_extension, $allowed_extensions)) {
        return "Format file tidak diperbolehkan! Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.";
    }

    // Membuat nama file baru
    $new_file_name = date('YmdHis') . '_' . uniqid() . '.' . $file_extension;
    $upload_dir = 'uploads/';
    $target_file = $upload_dir . $new_file_name;

    // Pastikan direktori ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Cek apakah file bisa dipindahkan ke direktori tujuan
    if (move_uploaded_file($file_tmp, $target_file)) {
        return $new_file_name;
    } else {
        return "Terjadi kesalahan saat mengunggah file.";
    }
}

if (isset($_POST['submit-payment']) && isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {
    $upload_result = uploadPaymentProof($_FILES['payment_proof']);
    if (strpos($upload_result, ".") !== false) { // Jika file berhasil diupload
        $new_file_name = $upload_result;

        if (isset($_SESSION['id_reservation'], $_POST['id_room'])) {
            $id_reservation = $_SESSION['id_reservation'];
            $id_room = $_POST['id_room'];
        
            try {
                // Update payment_proof di tabel reservations
                $stmt = $pdo->prepare("UPDATE reservations SET payment_proof = :payment_proof WHERE id_reservation = :id_reservation");
                $stmt->bindParam(':payment_proof', $new_file_name);
                $stmt->bindParam(':id_reservation', $id_reservation, PDO::PARAM_INT);
                $stmt->execute();
        
                // Update status room di tabel rooms
                $stmt = $pdo->prepare("UPDATE rooms SET status = 'pending' WHERE id_room = ?");
                $stmt->execute([$id_room]);
        
                $payment_upload_message = "Bukti pembayaran berhasil diunggah.";
        
                // Redirect ke halaman invoices
                header('Location: ../invoices.php?id_reservation=' . $id_reservation);
                exit();
            } catch (PDOException $e) {
                $payment_upload_message = "Terjadi kesalahan saat mengunggah bukti pembayaran: " . $e->getMessage();
            }
        } else {
            $payment_upload_message = "ID pemesanan atau ID kamar tidak ditemukan.";
        }
        
    } else {
        $payment_upload_message = $upload_result; // Menampilkan pesan kesalahan
    }
}
elseif (isset($_POST['start_date'], $_POST['id_duration'], $_POST['id_pay_method'], $_POST['total-amount'], $_POST['number_room'])) {
    // Mendapatkan data dari form yang dikirimkan
    $start_date = $_POST['start_date'];
    $to_date = $_POST['to_date'] ?? null;
    $id_duration = $_POST['id_duration'];
    $id_pay_method = $_POST['id_pay_method'];
    $total_amount = (float) $_POST['total-amount'];
    $id_room = $_POST['number_room'];  // Mendapatkan nomor kamar yang dipilih

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

    try {
        if ($total_amount > 0) { // Ganti dengan pengecekan yang valid untuk memastikan pembayaran berhasil
            if (!isset($error_message)) {
                if ($to_date) {
                    $stmt = $pdo->prepare("INSERT INTO reservations (id_user, id_pay_method, start_date, to_date, id_room, total_amount) 
                                           VALUES (:id_user, :id_pay_method, :start_date, :to_date, :id_room, :total_amount)");
                    $stmt->bindParam(':to_date', $to_date);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO reservations (id_user, id_pay_method, start_date, id_room, total_amount) 
                                           VALUES (:id_user, :id_pay_method, :start_date, :id_room, :total_amount)");
                }

                $stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
                $stmt->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
                $stmt->bindParam(':start_date', $start_date);
                $stmt->bindParam(':id_room', $id_room, PDO::PARAM_INT);
                $stmt->bindParam(':total_amount', $total_amount);
                $stmt->execute();

                $id_reservation = $pdo->lastInsertId();
                $_SESSION['id_reservation'] = $id_reservation;
                $success_message = "Pemesanan berhasil. Silakan lanjutkan pembayaran.";
            }
        }
    } catch (PDOException $e) {
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
<style>
    body {
        background-color: #DCDCDC;
    }
</style>
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
                <p><strong>Metode Pembayaran: <?= htmlspecialchars($payment_details['method']); ?></strong></p>
                <p><strong>Nama Akun: <?= htmlspecialchars($payment_details['account_name']); ?></strong></p>
                <p><strong>Nomor Tujuan: <?= htmlspecialchars($payment_details['payment_number']); ?></strong></p>
                <p><strong>Total Pembayaran: Rp<?= number_format($total_amount, 0, ',', '.'); ?></strong></p>

                <!-- Form untuk mengirimkan bukti pembayaran -->
                <form action="" method="POST" enctype="multipart/form-data" id="payment-form">
                    <div class="form-group">
                    <input type="hidden" id="id_room" name="id_room" value="<?= htmlspecialchars($id_room); ?>">
                        <label for="payment_proof">Bukti Pembayaran</label>
                        <input type="file" class="form-control-file" name="payment_proof" id="payment_proof" required>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('payment-form').addEventListener('submit', function (e) {
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        const fileInput = document.getElementById('payment_proof');
        const fileName = fileInput.value;
        const fileExtension = fileName.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            e.preventDefault(); // Mencegah form dikirim
            // Menampilkan pop-up error menggunakan SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Format File Tidak Diperbolehkan',
                text: 'Hanya file dengan ekstensi JPG, JPEG, PNG, atau PDF yang diperbolehkan.',
                confirmButtonText: 'OK'
            });
            fileInput.value = ''; // Reset file input
        }
    });
</script>

</body>
</html>
