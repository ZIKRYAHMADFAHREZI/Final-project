<?php
session_start();
require 'db/connection.php';

// Ambil data pengguna
$id_user = $_SESSION['id_user'] ?? null;

$query = $pdo->prepare("SELECT * FROM pay_methods WHERE active = 1");
$query->execute();
$methods = $query->fetchAll(PDO::FETCH_ASSOC);
$today = new DateTime();
$formattedDate = $today->format('Y-m-d');

$id_type = null;
if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);
} else {
    echo "ID tipe kamar tidak valid.";
    exit;
}

// Mengambil nomor kamar berdasarkan id_type
$roomQuery = $pdo->prepare("SELECT id_room, number_room, status FROM rooms WHERE id_type = ?");
$roomQuery->execute([$id_type]);
$rooms = $roomQuery->fetchAll(PDO::FETCH_ASSOC);

function generateBookingOptions($id_type, $pdo) {
    // Mengambil data harga durasi menginap
    $stmt = $pdo->prepare("SELECT `12hour`, `24hour`, `transit` FROM types WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $hour = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hour) {
        echo '<p>Data durasi menginap tidak ditemukan.</p>';
        return;
    }

    $options = '<div class="form-group mt-3">';
    $options .= '<label for="hour">Pilih Durasi Menginap:</label>';
    $options .= '<select class="form-control" id="hour" name="hour" onchange="updatePrice()">';

    // Opsi untuk transit
    if (!empty($hour['transit'])) {
        $options .= '<option value="3 jam" data-price="' . htmlspecialchars($hour['transit']) . '">Transit (3 jam) - Rp ' . number_format($hour['transit'], 0, ',', '.') . '</option>';
    }

    // Opsi untuk 12 jam
    if (!empty($hour['12hour'])) {
        $options .= '<option value="12 jam" data-price="' . htmlspecialchars($hour['12hour']) . '">12 Jam - Rp ' . number_format($hour['12hour'], 0, ',', '.') . '</option>';
    }

    // Opsi untuk 24 jam
    if (!empty($hour['24hour'])) {
        $options .= '<option value="24 jam" data-price="' . htmlspecialchars($hour['24hour']) . '">24 Jam - Rp ' . number_format($hour['24hour'], 0, ',', '.') . '</option>';
    }

    $options .= '</select>';
    $options .= '</div>';

    echo $options; // Pastikan opsi dikembalikan ke output
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Nomor Kamar</title>
<link rel="icon" type="png" href="img/favicon.ico">
<!-- bootsrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<link rel="stylesheet" href="css/booking.css">
</head>
<body>
<?php
// Cek apakah user sudah login (id_user ada atau tidak)
if (isset($id_user) && !empty($id_user)) {
    // Jika id_user ada, tampilkan navbar
    include 'navbar.php';
} else {
    // Jika id_user tidak ada, tampilkan SweetAlert
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
                window.location.href = 'login.php'; // Redirect ke halaman login
            } else {
                window.location.href = 'detail.php?id_type={$id_type}'; // Redirect ke halaman detail
            }
        });
    </script>";
    exit; // Hentikan eksekusi kode lebih lanjut
}
?>
<div class="container">
<h2 class="text-center mb-4 mt-5">Pilih Tanggal dan Nomor Kamar</h2>
    <form action="paynt/payment.php" method="POST" class="border p-4 rounded shadow" id="bookingForm">
        <input type="hidden" id="id_pay_method" name="id_pay_method" value="">
        <input type="hidden" id="payMethodsData" value='<?= json_encode($methods); ?>'>

        <?php generateBookingOptions($id_type, $pdo); ?>

        <div class="form-group">
            <label for="startDate">Tanggal Mulai:</label>
            <input type="date" class="form-control" id="startDateInput" name="start_date" min="<?= $formattedDate; ?>" required onchange="checkDate()">
        </div>

        <div class="form-group" id="endDateContainer" style="display:none;">
            <label for="endDate">Tanggal Selesai:</label>
            <input type="date" class="form-control" id="endDate" name="to_date" min="<?= $formattedDate; ?>" required onchange="updatePrice()">
        </div>

    <strong>Total Harga: </strong>
    <p id="totalAmount"></p>
    <input style="display:none;"type="number" name="total_amount" id="totalAmountReal" readonly>

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
<script src="js/booking.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const startDateInput = document.getElementById('startDateInput');
        const endDateInput = document.getElementById('endDate');
        const endDateContainer = document.getElementById('endDateContainer');
        const durationSelect = document.getElementById('hour');
        const totalAmountFake = document.getElementById('totalAmount');
        const totalAmountInput = document.getElementById('totalAmountReal');

        function updatePrice() {
            const selectedOption = durationSelect.selectedOptions[0];
            const pricePerUnit = parseFloat(selectedOption.getAttribute('data-price'));
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                // Pastikan tanggal akhir lebih dari tanggal awal
                if (end <= start) {
                    alert("Tanggal selesai harus lebih dari tanggal mulai.");
                    endDateInput.value = ""; // Reset tanggal akhir
                    return;
                }

                // Hitung selisih hari (min 1 hari dihitung sebagai 1 hari sewa)
                const diffTime = end - start;
                const diffDays = Math.ceil(diffTime / (1000 * 3600 * 24));

                // Perhitungan total harga
                const totalPrice = pricePerUnit * diffDays;

                // Tampilkan total harga
                totalAmountFake.innerText = totalPrice.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
                totalAmountInput.value = totalPrice;
            }
        }

        startDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;

            // Validasi jika tanggal akhir sama atau lebih kecil dari tanggal mulai
            if (endDateInput.value && new Date(endDateInput.value) <= new Date(startDateValue)) {
                alert("Tanggal selesai harus lebih dari tanggal mulai.");
                endDateInput.value = ""; // Reset tanggal akhir
            }

            // Jika durasi 24 jam, hitung tanggal selesai otomatis
            if (durationSelect.value === '24 jam') {
                const startDate = new Date(startDateValue);
                startDate.setDate(startDate.getDate() + 1);
                endDateInput.value = startDate.toISOString().split('T')[0];
            }

            updatePrice();
        });

        endDateInput.addEventListener('change', function () {
            const startDateValue = startDateInput.value;
            const endDateValue = endDateInput.value;

            // Validasi jika tanggal akhir sama atau lebih kecil dari tanggal mulai
            if (new Date(endDateValue) <= new Date(startDateValue)) {
                alert("Tanggal selesai harus lebih dari tanggal mulai.");
                endDateInput.value = ""; // Reset tanggal akhir
                return;
            }

            updatePrice();
        });

        durationSelect.addEventListener('change', function () {
            const selectedDuration = this.value;

            if (selectedDuration === '3 jam' || selectedDuration === '12 jam') {
                endDateContainer.style.display = 'none';
                endDateInput.value = startDateInput.value;
                endDateInput.disabled = true;
            } else if (selectedDuration === '24 jam') {
                endDateContainer.style.display = 'block';
                endDateInput.disabled = false;

                const startDateValue = startDateInput.value;
                if (startDateValue) {
                    const startDate = new Date(startDateValue);
                    startDate.setDate(startDate.getDate() + 1); // Tambah 1 hari
                    const endDate = startDate.toISOString().split('T')[0];
                    endDateInput.value = endDate;
                    updatePrice();
                }
            }
        });
    });
</script>
<!-- Bootstrap JavaScript Libraries -->
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