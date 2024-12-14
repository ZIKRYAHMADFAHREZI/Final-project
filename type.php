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

// Mengambil nomor kamar berdasarkan id_type
$roomQuery = $pdo->prepare("SELECT number_room FROM rooms WHERE id_type = ?");
$roomQuery->execute([$id_type]);
$rooms = $roomQuery->fetchAll(PDO::FETCH_ASSOC);

function generateBookingOptions($id_type, $pdo) {
    $stmt = $pdo->prepare("SELECT 12hour, 24hour FROM room_rates WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $room_rate = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT price FROM transits WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $transit_price = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<div class="form-group mt-3">';
    echo '<label>Pilih Durasi Menginap:</label>';
    echo '<select class="form-control" id="id_duration" name="id_duration" onchange="updatePrice()">';
    
    // Menambahkan harga transit jika ada
    if ($transit_price) {
        echo '<option value="transit" data-price="' . htmlspecialchars($transit_price['price']) . '">Transit (3 jam) - Rp ' . number_format($transit_price['price'], 0, ',', '.') . '</option>';
    }

    // Menambahkan harga 12 jam
    echo '<option value="12jam" data-price="' . htmlspecialchars($room_rate['12hour']) . '">12 Jam - Rp ' . number_format($room_rate['12hour'], 0, ',', '.') . '</option>';

    // Menambahkan harga 24 jam
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
<title>Pilih Nomor Kamar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        flex-direction: column;
    }
    /* Responsif pada layar kecil */
    @media (max-width: 768px) {
        .date-picker-container {
            flex-direction: column;
            width: 100%;
        }
        .form-group {
            width: 100%;
        }
    }
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
<form action="paynt/payment.php" method="POST" class="border p-4 rounded shadow" id="bookingForm">
    <input type="hidden" id="id_pay_method" name="id_pay_method" value="">

    <?php generateBookingOptions($id_type, $pdo); ?>

    <div class="form-group">
        <label for="startDate">Tanggal Mulai:</label>
        <input type="date" class="form-control" id="startDate" name="start_date" min="<?= $formattedDate; ?>" required onchange="checkDate()">
    </div>

    <div class="form-group" id="endDateContainer" style="display:none;">
        <label for="endDate">Tanggal Selesai:</label>
        <input type="date" class="form-control" id="endDate" name="to_date" min="<?= $formattedDate; ?>" required onchange="updatePrice()">
    </div>

    <strong>Total Harga: </strong>
    <input type="number" name="total-amount" id="totalAmount" readonly>

    <div>
        <p>Pilih Nomor Kamar:</p>
        <?php foreach ($rooms as $room) : ?>
            <input type="radio" id="room_<?= $room['number_room'] ?>" name="number_room" value="<?= htmlspecialchars($room['number_room']); ?>">
            <label for="room_<?= $room['number_room'] ?>"><?= htmlspecialchars($room['number_room']); ?></label><br>
        <?php endforeach; ?>
    </div>

    <button type="button" id="pesanButton" class="btn btn-primary mt-4">Pesan</button>
</form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mengatur perubahan pada input tanggal
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const endDateContainer = document.getElementById('endDateContainer');
        const durationSelect = document.getElementById('id_duration');

        // Mengatur agar tanggal selesai tidak lebih awal dari tanggal mulai
        startDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;
            if (endDateInput.value && new Date(endDateInput.value) < new Date(startDateValue)) {
                alert("Tanggal selesai tidak boleh lebih awal dari tanggal mulai.");
                endDateInput.value = startDateValue;
            }
        });

        endDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;
            const endDateValue = endDateInput.value;
            if (new Date(endDateValue) < new Date(startDateValue)) {
                alert("Tanggal selesai tidak boleh lebih awal dari tanggal mulai.");
                endDateInput.value = startDateValue;
            }
        });

        // Fungsi untuk update harga berdasarkan durasi dan tanggal
        function updatePrice() {
            const selectedOption = durationSelect.selectedOptions[0];
            const pricePerUnit = parseFloat(selectedOption.getAttribute('data-price'));
            const startDate = startDateInput.value;
            const endDate = endDateInput.value || startDate;

            if (startDate && endDate) {
                const diffTime = new Date(endDate) - new Date(startDate);
                const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 3600 * 24))); // Menghitung jumlah hari
                document.getElementById('totalPrice').value = pricePerUnit * diffDays;
            }
        }

        // Reset input dan tampilan ketika memilih durasi menginap
        durationSelect.addEventListener('change', function () {
            const selectedDuration = this.value;
            const startDate = startDateInput;
            const endDate = endDateInput;

            // Jika memilih 3 jam atau 12 jam, reset dan sembunyikan tanggal selesai
            if (selectedDuration === 'transit' || selectedDuration === '12jam') {
                endDateContainer.style.display = 'none';
                endDate.value = startDate.value; // Sesuaikan end date dengan start date
                startDate.disabled = false;
                endDate.disabled = true;
            } else if (selectedDuration === '24jam') {
                endDateContainer.style.display = 'block';
                endDate.disabled = false;
            }
        });

        // Cek ulang harga jika memilih durasi atau tanggal berubah
        startDateInput.addEventListener('change', updatePrice);
        endDateInput.addEventListener('change', updatePrice);
    });
    document.addEventListener('DOMContentLoaded', function () {
    const pesanButton = document.getElementById('pesanButton');
    const payMethods = <?= json_encode($methods); ?>; // Menyisipkan array metode pembayaran dari PHP ke dalam JS

    // Menambahkan event listener pada tombol "Pesan"
    pesanButton.addEventListener('click', function () {
        // Menampilkan SweetAlert2 untuk memilih metode pembayaran
        Swal.fire({
            title: 'Pilih Metode Pembayaran',
            input: 'radio',
            inputOptions: payMethods.reduce(function (options, method) {
                options[method.id_pay_method] = method.method;
                return options;
            }, {}),
            inputValidator: (value) => {
                return !value && 'Anda harus memilih metode pembayaran!';
            },
            showCancelButton: true,
            confirmButtonText: 'Pilih',
            cancelButtonText: 'Batal',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Menyimpan ID metode pembayaran yang dipilih ke dalam form
                document.getElementById('id_pay_method').value = result.value;

                // Mengupdate form untuk menyertakan metode pembayaran yang dipilih
                // Setelah memilih, lanjutkan untuk submit form
                document.getElementById('bookingForm').submit();
            }
        });
    });
});

</script>

</body>
