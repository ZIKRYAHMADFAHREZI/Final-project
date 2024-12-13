<?php
session_start();
require 'db/connection.php';

// Ambil data pengguna
$id_user = $_SESSION['id_user'];

$query = $pdo->prepare("SELECT * FROM pay_methods WHERE active = 1");
$query->execute();
$methods = $query->fetchAll(PDO::FETCH_ASSOC);
$today = new DateTime();
$formattedDate = $today->format('Y-m-d');

if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);
}

function generateBookingOptions($id_type, $pdo) {
    $stmt = $pdo->prepare("SELECT 12hour, 24hour FROM room_rates WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $room_rate = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT price FROM transits WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $transit_price = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<div class="form-group mt-3">';
    echo '<label>Pilih Jenis Menginap:</label><br>';
    echo '<input type="radio" name="booking_type" value="perhari" onchange="updateDateFields()"> Perhari<br>';
    echo '<input type="radio" name="booking_type" value="lebih_perhari" onchange="updateDateFields()"> Lebih Perhari<br>';
    echo '</div>';

    echo '<div class="form-group mt-3" id="duration-options" style="display:none">';
    echo '<label for="id_duration">Pilih Durasi Menginap:</label>';
    echo '<select class="form-control" id="id_duration" name="id_duration" onchange="updatePrice()">';

    if ($transit_price) {
        echo '<option value="transit" data-price="' . htmlspecialchars($transit_price['price']) . '">Transit (3 jam) - Rp ' . number_format($transit_price['price'], 0, ',', '.') . '</option>';
    }

    echo '<option value="12jam" data-price="' . htmlspecialchars($room_rate['12hour']) . '">12 Jam - Rp ' . number_format($room_rate['12hour'], 0, ',', '.') . '</option>';
    echo '<option value="24jam" data-price="' . htmlspecialchars($room_rate['24hour']) . '">24 Jam - Rp ' . number_format($room_rate['24hour'], 0, ',', '.') . '</option>';
    echo '</select>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Tipe Kamar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="icon" type="png" href="img/favicon.ico">
<link rel="stylesheet" href="css/trans.css">
<style>
    body { background-color: #DCDCDC; }
    .container { padding-top: 70px; }
    .date-picker-container { display: flex; gap: 15px; }
</style>
</head>
<body>
<?php include 'navbar.php';?>
<?php
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        Swal.fire({
            title: 'Login Diperlukan!',
            text: 'Silakan login terlebih dahulu untuk melakukan pemesanan.',
            icon: 'warning',
            confirmButtonText: 'Login',
            showCancelButton: true,
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'login.php';
            } else {
                window.location.href = 'detail.php?id_type={$id_type}';
            }
        });
    </script>";
    exit;
}
?>

<div class="container">
<h2 class="text-center mb-4 mt-5">Pilih Tanggal dan Nomor Kamar</h2>
<form action="paynt/payment.php" method="post" class="border p-4 rounded shadow">
    <input type="hidden" id="id_pay_method" name="id_pay_method" value="">

    <div class="form-group">
        <label for="startDate">Tanggal:</label>
        <div class="date-picker-container">
            <input type="date" class="form-control" id="startDate" name="start_date" min="<?= $formattedDate; ?>" required>
            <input type="date" class="form-control" id="endDate" name="end_date" min="<?= $formattedDate; ?>" required>
        </div>
    </div>

    <?php generateBookingOptions($id_type, $pdo); ?>

    <div>
        <?php foreach ($methods as $index => $pm) : ?>
            <input type="radio" id="method_<?= $index ?>" name="id_pay_method" value="<?= htmlspecialchars($pm['id_pay_method']); ?>">
            <label for="method_<?= $index ?>"><?= htmlspecialchars($pm['method']); ?></label>
        <?php endforeach; ?>
    </div>

    <strong>Total Harga: </strong>
    <input type="number" name="total-price" id="totalPrice" readonly>
    <br>

    <button type="submit" name="submit-type" class="btn btn-primary mt-4">Pesan</button>
</form>
</div>

<script>
function updateDateFields() {
    const bookingType = document.querySelector('input[name="booking_type"]:checked').value;
    const endDateField = document.getElementById('endDate');

    if (bookingType === 'perhari') {
        endDateField.style.display = 'none';
        document.getElementById('duration-options').style.display = 'block';
    } else {
        endDateField.style.display = 'inline-block';
        document.getElementById('duration-options').style.display = 'block';
    }
}

function updatePrice() {
    const selectedOption = document.getElementById('id_duration').selectedOptions[0];
    const pricePerUnit = parseFloat(selectedOption.getAttribute('data-price'));

    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value || startDate;

    if (startDate && endDate) {
        const diffTime = new Date(endDate) - new Date(startDate);
        const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 3600 * 24)));
        document.getElementById('totalPrice').value = pricePerUnit * diffDays;
    }
}
</script>
</body>
</html>