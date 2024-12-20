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
    // Query untuk mengambil data pemesanan dan join dengan tabel rooms dan types
    $query = "
        SELECT 
            r.*, 
            ro.number_room, 
            t.name_type 
        FROM reservations r
        JOIN 
            rooms ro ON r.id_room = ro.id_room
        JOIN 
            types t ON ro.id_type = t.id_type
        WHERE 
            r.id_user = :id_user
        ORDER BY 
            r.start_date DESC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC); // Ambil data pemesanan
} catch (PDOException $e) {
    $error_message = "Terjadi kesalahan saat mengambil data pemesanan: " . $e->getMessage();
}
?>



<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta
name="viewport"
content="width=device-width, initial-scale=1, shrink-to-fit=no"
/>
<title>History Pemesanan</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link rel="stylesheet" href="css/history.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="tainer">
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
                                    <th>Tipe Kamar</th>  
                                    <th>Nomor Kamar</th>  
                                    <th>Total Pembayaran</th>
                                    <th>Status Pembayaran</th>
                                    <th>Status Reservasi</th>
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
                                            <td><?= htmlspecialchars($reservation['name_type']); ?></td>
                                            <td><?= htmlspecialchars($reservation['number_room']); ?></td>
                                            <td>Rp<?= number_format($reservation['total_amount'], 0, ',', '.'); ?></td>
                                            <td>
                                                <?= $reservation['payment_status'] === 'paid' ? 'Sudah Dibayar' : 
                                                ($reservation['payment_status'] === 'refunded' ? 'Dikembalikan' : '') ?>
                                            </td>
                                            <td><?= htmlspecialchars($reservation['status'])  ?></td>
                                            <td><a href="javascript:void(0);" onclick="showPaymentProof('<?= htmlspecialchars($reservation['payment_proof']); ?>')">Lihat Bukti Pembayaran</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data pemesanan.</td>
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
<div id="paymentModal" style="display:none;">
    <div style="background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
        <div style="background-color: white; padding: 20px; border-radius: 10px;">
            <img id="paymentImage" src="" alt="Bukti Pembayaran" style="max-width: 600px; max-height: 400px; width: auto; height: auto;">
            <button onclick="closeModal()" class="btn btn-secondary mt-3">Tutup</button>
        </div>
    </div>
</div>
<script>
function showPaymentProof(fileName) {
    console.log('File name:', fileName); // Periksa apakah fileName terisi
    var modal = document.getElementById('paymentModal');
    var img = document.getElementById('paymentImage');

    if (fileName) {
        img.src = 'paynt/uploads/' + fileName; // Path lengkap ke file gambar
        modal.style.display = 'flex'; // Tampilkan modal
    } else {
        alert('Bukti pembayaran tidak tersedia');
    }
}
function closeModal() {
    var modal = document.getElementById('paymentModal');
    if (modal) {
        modal.style.display = 'none'; // Sembunyikan modal
    } else {
        console.error('Modal element not found');
    }
}
</script>
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
></script>
</body>
</html>
