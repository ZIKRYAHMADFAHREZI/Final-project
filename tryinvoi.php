<?php
// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "hotel_invoice"; // Nama database

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $room_type = $_POST['room_type'];
    $nights = $_POST['nights'];
    $price_per_night = $_POST['price_per_night'];
    $tax_rate = 0.10; // 10% pajak
    $subtotal = $nights * $price_per_night;
    $tax = $subtotal * $tax_rate;
    $total = $subtotal + $tax;

    // Simpan data invoice ke database
    $sql = "INSERT INTO invoices (name, room_type, nights, price_per_night, subtotal, tax, total)
            VALUES ('$name', '$room_type', $nights, $price_per_night, $subtotal, $tax, $total)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Invoice berhasil disimpan!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Ambil data invoice yang sudah ada
$sql = "SELECT * FROM invoices";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-top: 20px;
        }
        .table td, .table th {
            text-align: left;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="invoice-header">Hotel Invoice</h1>
    
    <!-- Form Input -->
    <form action="" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nama Pengunjung</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="room_type">Tipe Kamar</label>
                    <select name="room_type" id="room_type" class="form-control" required>
                        <option value="Deluxe">Deluxe</option>
                        <option value="Suite">Suite</option>
                        <option value="Standard">Standard</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nights">Jumlah Malam</label>
                    <input type="number" name="nights" id="nights" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price_per_night">Harga Per Malam</label>
                    <input type="number" name="price_per_night" id="price_per_night" class="form-control" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Generate Invoice</button>
    </form>

    <!-- Tampilkan Data Invoice yang Sudah Ada -->
    <h3 class="mt-5">Invoice yang Sudah Tersimpan</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pengunjung</th>
                <th>Tipe Kamar</th>
                <th>Jumlah Malam</th>
                <th>Harga Per Malam</th>
                <th>Subtotal</th>
                <th>Pajak</th>
                <th>Total</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                        <td><?php echo $row['nights']; ?></td>
                        <td>Rp <?php echo number_format($row['price_per_night'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($row['tax'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="9">Belum ada data invoice.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Tutup koneksi
$conn->close();
?>
