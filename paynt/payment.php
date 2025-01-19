<?php
session_start();
require '../db/connection.php';

// Daftar ekstensi file yang diperbolehkan
$allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];

// Fungsi untuk mengupload bukti pembayaran
function uploadPaymentProof($file) {
    global $allowed_extensions;

    $file_tmp = $file['tmp_name'];
    $original_file_name = $file['name'];
    $file_extension = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));

    // Cek apakah ekstensi file diperbolehkan
    if (!in_array($file_extension, $allowed_extensions)) {
        return "Format file tidak diperbolehkan! Hanya JPG, JPEG, PNG, atau PDF yang diperbolehkan.";
    }

    // Membuat nama file baru
    $new_file_name = date('YmdHis') . '_' . uniqid() . '.' . $file_extension;
    $upload_dir = 'uploads/';
    $target_file = $upload_dir . $new_file_name;

    // Pastikan direktori ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Pindahkan file ke direktori tujuan
    if (move_uploaded_file($file_tmp, $target_file)) {
        return $new_file_name;
    } else {
        return "Terjadi kesalahan saat mengunggah file.";
    }
}

$error_message = '';
$success_message = '';
$payment_details = null;

// Simpan data ke session jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_date'])) $_SESSION['start_date'] = $_POST['start_date'];
    if (isset($_POST['to_date'])) $_SESSION['to_date'] = $_POST['to_date'] ?? null;
    if (isset($_POST['id_pay_method'])) $_SESSION['id_pay_method'] = $_POST['id_pay_method'];
    if (isset($_POST['total_amount'])) $_SESSION['total_amount'] = (float)$_POST['total_amount'];
    if (isset($_POST['number_room'])) $_SESSION['id_room'] = $_POST['number_room'];
    if (isset($_POST['hour'])) $_SESSION['hour'] = $_POST['hour'];
}

// Ambil data dari session jika ada
$start_date = $_SESSION['start_date'];
$to_date = $_SESSION['to_date'] ?? null;
$id_pay_method = $_SESSION['id_pay_method'];
$total_amount = $_SESSION['total_amount'];
$id_room = $_SESSION['id_room'];
$hour = $_SESSION['hour'];

// Ambil detail metode pembayaran berdasarkan id_pay_method
if ($id_pay_method) {
    $query = $pdo->prepare("SELECT method, account_name, payment_number FROM pay_methods WHERE id_pay_method = :id_pay_method AND active = 1");
    $query->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
    $query->execute();
    $payment_details = $query->fetch(PDO::FETCH_ASSOC);
}

$success_message = "Pemesanan berhasil. Silakan lanjutkan pembayaran.";

if (isset($_POST['submit-payment'])) {
    try {
        // Proses upload bukti pembayaran
        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === 0) {
            $upload_result = uploadPaymentProof($_FILES['payment_proof']);
            if (strpos($upload_result, ".") === false) {
                throw new Exception($upload_result);
            }

            $payment_proof = $upload_result;

            // Tangani nilai kosong pada to_date
            $to_date = !empty($to_date) ? $to_date : null;

            // Query untuk memasukkan data ke tabel reservations
            $stmt = $pdo->prepare("
                INSERT INTO reservations (id_user, id_pay_method, start_date, to_date, id_room, total_amount, payment_proof, hour) 
                VALUES (:id_user, :id_pay_method, :start_date, :to_date, :id_room, :total_amount, :payment_proof, :hour)
            ");

            // Bind semua parameter sesuai placeholder
            $stmt->bindParam(':id_user', $_SESSION['id_user'], PDO::PARAM_INT);
            $stmt->bindParam(':id_pay_method', $id_pay_method, PDO::PARAM_INT);
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':to_date', $to_date, $to_date !== null ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindParam(':id_room', $id_room, PDO::PARAM_INT);
            $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
            $stmt->bindParam(':payment_proof', $payment_proof, PDO::PARAM_STR);
            $stmt->bindParam(':hour', $hour, PDO::PARAM_STR);

            // Eksekusi query
            $stmt->execute();

            $id_reservation = $pdo->lastInsertId();
            $_SESSION['id_reservation'] = $id_reservation;

            // Update status kamar
            $pdo->prepare("UPDATE rooms SET status = 'pending' WHERE id_room = ?")
                ->execute([$id_room]);

            // Arahkan ke halaman invoice
            header('Location: ../invoice.php?id_reservation=' . $id_reservation);
            exit(); // Pastikan kode berhenti di sini agar pengalihan bekerja
        } else {
            throw new Exception("Bukti pembayaran tidak valid.");
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pembayaran</title>
    <link rel="icon" type="image/x-icon" href="../img/favicon.ico">
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

        <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
        <?php elseif ($success_message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if ($payment_details): ?>
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
  <div class="card-body">
    <h6 class="text-center" style="color:red;">*jangan tutup halaman ini jika sedang melakukan pembayaran.</h6>
  </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('payment-form').addEventListener('submit', function(e) {
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