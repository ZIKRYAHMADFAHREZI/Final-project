<?php
session_start();
require 'db/connection.php';
// Ambil data pengguna
$id_user = $_SESSION['id_user'];
// Periksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
$query = $pdo->prepare("SELECT * FROM pay_methods WHERE active = 1");
$query->execute();
$methods = $query->fetchAll(PDO::FETCH_ASSOC);
$today = new DateTime();
$formattedDate = $today->format('Y-m-d'); // Format tanggal menjadi YYYY-MM-DD

if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);

    // Ambil nomor kamar berdasarkan id_type
    $stmt = $pdo->prepare("SELECT number_room FROM rooms WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $number_room = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ambil tarif kamar berdasarkan id_type
    $stmt = $pdo->prepare("SELECT id_type, 12hour, 24hour FROM room_rates WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $room_rate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Cek jika ada harga transit untuk tipe kamar ini
    $transit_price = null;
    $stmt = $pdo->prepare("SELECT price FROM transits WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $transit_price = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Jika ada harga transit, harga transit tidak diubah
    // Pastikan harga transit tetap apa adanya
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Tipe Kamar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="png" href="img/favicon.ico">
<link rel="stylesheet" href="css/trans.css">
<style>
    body {
        background-color: #DCDCDC;
    }
    .container {
        padding-top: 70px;
    }
    .date-picker-container {
        display: flex;
        gap: 15px;
    }
</style>
</head>
<body>
<?php include 'navbar.php';?>
<div class="container">
    <h2 class="text-center mb-4 mt-5">Pilih Tanggal dan Nomor Kamar</h2>
<form action="paynt/payment.php" method="post" class="border p-4 rounded shadow">
    <!-- Input metode pembayaran tersembunyi -->
    <input type="hidden" id="id_pay_method" name="id_pay_method" value="">

    <!-- Tanggal -->
    <div class="form-group">
        <label for="startDate">Tanggal:</label>
        <div class="date-picker-container">
            <input type="date" class="form-control" id="startDate" name="start_date" min="<?= $formattedDate; ?>" required>
            <input type="date" class="form-control" id="endDate" name="end_date" min="<?= $formattedDate; ?>" required>
        </div>
    </div>

    <!-- Durasi Menginap -->
    <div class="form-group mt-2">
        <label for="id_duration">Pilih Lama Menginap:</label>
        <select class="form-control" id="id_duration" name="id_duration" required onchange="updatePrice()">
            <!-- Opsi transit jika ada harga transit -->
            <?php if ($transit_price): ?>
                <option value="transit" data-price="<?= $transit_price['price'] ?>">Transit (3 jam) - Rp <?= number_format($transit_price['price'], 0, ',', '.') ?></option>
            <?php endif; ?>
            
            <!-- Opsi tarif kamar harian -->
            <option value="daily" data-price="<?= $room_rate['12hour'] ?>">12 Jam - Rp <?= number_format($room_rate['12hour'], 0, ',', '.') ?></option>
            <option value="daily" data-price="<?= $room_rate['24hour'] ?>">24 Jam - Rp <?= number_format($room_rate['24hour'], 0, ',', '.') ?></option>
        </select>
    </div>

    <!-- Pilihan metode pembayaran -->
    <div>
        <?php foreach ($methods as $index => $pm) : ?> <!-- Iterasi setiap metode pembayaran -->
            <input type="radio" id="method_<?= $index ?>" name="id_pay_method" value="<?= htmlspecialchars($pm['id_pay_method']); ?>">
            <label for="method_<?= $index ?>"><?= htmlspecialchars($pm['method']); ?></label>
        <?php endforeach; ?>
    </div>

    <!-- Total Harga -->
    <strong>Total Harga: </strong>
    <input type="number" name="total-price" id="totalPrice" readonly>
    <br>

    <button type="submit" name="submit-type" class="btn btn-primary mt-4">Pesan</button>
</form>

</div>

<script>
function updatePrice() {
    const selectedOption = document.getElementById('id_duration').selectedOptions[0];
    const pricePerUnit = parseFloat(selectedOption.getAttribute('data-price'));
    
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    if (startDate && endDate) {
        const diffTime = new Date(endDate) - new Date(startDate); // Hitung selisih waktu
        const diffDays = Math.ceil(diffTime / (1000 * 3600 * 24)); // Konversi milidetik ke hari
        
        let totalPrice = pricePerUnit * diffDays; // Total harga berdasarkan durasi
        
        // Update harga total di layar
        document.getElementById('totalPrice').value = totalPrice;
    }
}
</script>
</body>
</html>