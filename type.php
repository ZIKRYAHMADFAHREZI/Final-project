<?php
include 'navbar.php';
require 'db/connection.php';

$today = new DateTime();
$formattedDate = $today->format('Y-m-d'); // Format tanggal menjadi YYYY-MM-DD

if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);

    // Ambil nomor kamar berdasarkan id_type
    $stmt = $pdo->prepare("SELECT number_room FROM rooms WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $number_room = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ID tertentu yang mendukung transit
    $stmt = $pdo->prepare("SELECT id_type, 12hour, 24hour FROM room_rates WHERE id_type = ?");
    $stmt->execute([$id_type]);
    $room_rate = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Khusus untuk id 4 dan 6, ambil juga harga transit
    $transit_price = null;
    if (in_array($id_type, [4, 6])) {
        $stmt = $pdo->prepare("SELECT price FROM transits WHERE id_type = ?");
        $stmt->execute([$id_type]);
        $transit_price = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tipe</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="icon" type="png" href="img/logoo.png">
<link rel="stylesheet" href="css/trans.css">
<style>
    body {
        background-color: #DCDCDC;
    }
    .container {
        padding-top: 70px;
    }
    .number_room {
            display: flex; /* Menggunakan flexbox untuk menyusun radio button */
            gap: 10px; /* Jarak antar radio button */
            margin: 20px auto; /* Pusatkan kotak */
            flex-wrap: wrap; /* Membungkus ke baris berikutnya jika diperlukan */
            justify-content: center;
        }
        .number_room label {
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer; /* Menunjukkan pointer saat hover */
        }
        .number_room input[type="radio"] {
            display: none; /* Sembunyikan radio button asli */
        }
        .custom-radio {
            width: 50px; /* Ukuran kotak */
            height: 50px; /* Ukuran kotak */
            appearance: none; /* Menghilangkan gaya default */
            background-color: green; /* Warna default kotak */
            border: 2px solid #000; /* Border kotak */
            border-radius: 3px; /* Sudut kotak */  
            cursor: pointer; /* Menunjukkan pointer saat hover */
            display: flex; /* Menggunakan flex untuk menempatkan angka */
            align-items: center; /* Pusatkan secara vertikal */
            justify-content: center; /* Pusatkan secara horizontal */
            color: white; /* Warna teks angka */
            font-size: 20px; /* Ukuran font angka */
        }
        .number_room input[type="radio"]:checked + label .custom-radio {
            background-color: yellow; /* Warna saat dicentang */
        }
</style>
</head>
<body>
<div id="loading" class="loading">
    <div class="spinner"></div>
    <h2 class="loading-text">GRAND MUTIARA</h2>
</div>

<div class="container">
    <h2 class="text-center mb-4 mt-5">Pilih Tanggal dan Nomor Kamar</h2>
    <form action="paynt/payment.php" method="post" class="border p-4 rounded shadow">
        <div class="form-group">
            <label for="datePicker">Tanggal:</label>
            <input type="date" class="form-control" id="datePicker" name="date" min="<?= $formattedDate; ?>" style="width: 150px;" required>
        </div>
        <div class="form-group mt-2">
    <label for="id_duration">Pilih Lama Menginap</label>
    <select class="form-control" id="id_duration" name="id_duration" style="width: 150px;" required onchange="updatePrice()">
        <?php if (in_array($id_type, [4, 6])): ?>
            <option value="transit" data-price="<?= $transit_price['price'] ?>">Transit(3jam)</option>
        <?php endif; ?>
        <option value="12hour" data-price="<?= $room_rate['12hour'] ?>">12 Jam</option>
        <option value="24hour" data-price="<?= $room_rate['24hour'] ?>">24 Jam</option>
    </select>
</div>


<div class="form-group mt-2">
    <label for="number_room">No Kamar:</label>
    <div class="number_room" required>
        <?php if (!empty($number_room)): ?>
            <?php foreach ($number_room as $number): ?>
                <div class="form-check-inline">
                    <input type="radio" 
                           id="number<?= htmlspecialchars($number['number_room']) ?>" 
                           name="number_room" 
                           value="<?= htmlspecialchars($number['number_room']) ?>" 
                           class="custom-radio">
                    <label for="number<?= htmlspecialchars($number['number_room']) ?>"
                           class="form-check-label">
                        <div class="custom-radio"><?= htmlspecialchars($number['number_room']) ?></div>
                    </label>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning" role="alert">Tidak ada kamar</div>
        <?php endif; ?>
    </div>
    <div id="priceDisplay" class="mt-3 h4"></div>
</div>


        <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" name="submit" id="submit">Pesan Sekarang</button>
    </form>
</div>

<script src="js/trans.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script>
    function updatePrice() {
        const select = document.getElementById('id_duration');
        const priceDisplay = document.getElementById('priceDisplay');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value) {
            const price = selectedOption.dataset.price; // pastikan ini adalah string
            priceDisplay.textContent = `Harga: Rp${price}`; // Menampilkan harga tanpa mengubah format
        } else {
            priceDisplay.textContent = '';
        }
    }
document.addEventListener('DOMContentLoaded', updatePrice);
</script>
</body>
</html>