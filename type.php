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
$roomQuery = $pdo->prepare("SELECT id_room, number_room, status FROM rooms WHERE id_type = ?");
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
/* Radio khusus untuk nomor kamar */
.room-radio {
    display: none; /* Sembunyikan elemen radio asli */
}
.number {
    display: flex;
    justify-content: flex-start; /* Mengatur elemen di kiri (pinggir) secara horizontal */
    align-items: center; /* Mengatur elemen di tengah secara vertikal */
    text-align: center; /* Memastikan teks berada di tengah */
    flex-wrap: wrap; /* Membungkus elemen jika diperlukan */
    gap: 10px; /* Menambahkan jarak antar elemen */
}

/* Responsif pada layar kecil */
@media (max-width: 768px) {
    .number {
        justify-content: flex-start; /* Menjaga elemen tetap ke pinggir pada layar kecil */
        gap: 15px; /* Menambah jarak antar elemen jika di kolom */
    }

    .room-radio + label {
        display: inline-block;
        padding: 8px 15px; /* Menyesuaikan padding pada layar kecil */
        font-size: 14px; /* Menyesuaikan ukuran font */
    }
}

/* Responsif pada layar lebih kecil (misalnya, ponsel) */
@media (max-width: 480px) {
    .room-radio + label {
        font-size: 12px; /* Menurunkan ukuran font untuk ponsel */
        padding: 6px 10px; /* Menyesuaikan padding */
    }
}

.room-radio + label {
    display: inline-block;
    padding: 10px 20px;
    margin: 5px;
    border: 2px solid #ccc;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: all 0.3s;
}

/* Status Available - Warna Hijau */
.room-radio + label.available {
    background-color: green;
    color: white;
    border-color: green;
}

/* Status Unavailable - Warna Merah */
.room-radio + label.unavailable {
    background-color: red;
    color: white;
    border-color: red;
}

/* Status Pending - Warna Kuning */
.room-radio + label.pending {
    background-color: yellow;
    color: black;
    border-color: yellow;
}

/* Gaya saat radio button dinonaktifkan */
.room-radio:disabled + label {
    background-color: #ddd;  /* Warna abu-abu untuk disabled */
    color: #888;  /* Warna teks abu-abu */
    border-color: #bbb;  /* Border abu-abu */
    cursor: not-allowed;  /* Menunjukkan bahwa elemen tidak dapat diklik */
}

/* Hover effect */
.room-radio:checked + label {
    transform: scale(1.1);
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

.room-radio:checked + label.available {
    background-color: darkgreen;
}

.room-radio:checked + label.unavailable {
    background-color: darkred;
}

.room-radio:checked + label.pending {
    background-color: darkgoldenrod;
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
    <p id="totalAmount"></p>
    <input style="display:none;"type="number" name="total-amount" id="totalAmountReal" readonly>

    <div class="select-room">
    <p>Pilih Nomor Kamar:</p>
        <div class="number">
        <?php foreach ($rooms as $room) : ?>
            <?php
            // Ambil status kamar dari hasil query
            $status = $room['status']; // Status kamar bisa 'available', 'unavailable', atau 'pending'
            // Tentukan apakah kamar bisa dipilih berdasarkan status
            $disabled = ($status == 'unavailable' || $status == 'pending') ? 'disabled' : '';
            ?>
            <input type="radio" id="room_<?= $room['number_room'] ?>" name="number_room" value="<?= $room['id_room']; ?>" class="room-radio" <?= $disabled ?> required>
            <label for="room_<?= $room['number_room'] ?>" class="<?= $status ?>"><?= htmlspecialchars($room['number_room']); ?></label><br>
        <?php endforeach; ?>
        </div>
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
        const totalAmountFake = document.getElementById('totalAmount');
        const totalAmountInput = document.getElementById('totalAmountReal');

        // Fungsi untuk update harga berdasarkan durasi dan tanggal
        function updatePrice() {
            const selectedOption = durationSelect.selectedOptions[0];
            const pricePerUnit = parseFloat(selectedOption.getAttribute('data-price'));
            const startDate = startDateInput.value;
            const endDate = endDateInput.value || startDate;

            if (startDate && endDate) {
                const diffTime = new Date(endDate) - new Date(startDate);
                const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 3600 * 24))); // Menghitung jumlah hari
                const totalPrice = pricePerUnit * diffDays;

                // Menampilkan total harga di input totalAmount
                totalAmountFake.innerText = totalPrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                totalAmountInput.value = totalPrice;
            }
        }

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

            // Update harga ketika memilih durasi menginap
            updatePrice();
        });

        // Cek ulang harga jika memilih durasi atau tanggal berubah
        startDateInput.addEventListener('change', updatePrice);
        endDateInput.addEventListener('change', updatePrice);
    });

    document.getElementById('pesanButton').addEventListener('click', function () {
    const startDate = document.getElementById('startDate').value;
    const selectedRoom = document.querySelector('input[name="number_room"]:checked');

    // Pengecekan apakah tanggal mulai sudah dipilih
    if (!startDate) {
        Swal.fire({
            title: 'Tanggal Belum Dipilih!',
            text: 'Silakan pilih tanggal mulai terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return; // Menghentikan eksekusi jika tanggal belum dipilih
    }

    // Pengecekan apakah kamar sudah dipilih
    if (!selectedRoom) {
        Swal.fire({
            title: 'Kamar Belum Dipilih!',
            text: 'Silakan pilih kamar terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return; // Menghentikan eksekusi jika kamar belum dipilih
    }

    // Jika semua validasi berhasil, lanjutkan ke pemilihan metode pembayaran
    const payMethods = <?= json_encode($methods); ?>; // Menyisipkan data payMethods PHP ke dalam JavaScript

    // Menampilkan alert jika memilih metode pembayaran
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
        inputPlaceholder: 'Pilih Metode Pembayaran',
        showCancelButton: true,
        confirmButtonText: 'Konfirmasi',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            // Mengatur metode pembayaran yang dipilih
            document.getElementById('id_pay_method').value = result.value;
            document.getElementById('bookingForm').submit();
        }
    });
});


const payMethods = <?= json_encode($methods); ?>; // Menyisipkan data payMethods PHP ke dalam JavaScript

</script>

</body>
</html>
