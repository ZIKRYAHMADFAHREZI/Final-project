<?php
require 'db/connection.php';
$id = $_GET['id'];
$today = new DateTime();
$formattedDate = $today->format('Y-m-d'); // Format tanggal menjadi YYYY-MM-DD

$sql = "SELECT number_room FROM rooms";
$result = $pdo->query($sql);
$number_room = $result->fetchAll(PDO::FETCH_ASSOC); // Ambil semua data kamar
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
    .custom-checkbox {
    width: 50px; /* Ukuran kotak */
    height: 50px; /* Ukuran kotak */
    appearance: none; /* Menghilangkan gaya default */
    background-color: green; /* Warna default kotak */
    border: 2px solid #000; /* Border kotak */
    border-radius: 3px; /* Sudut kotak */  
    cursor: pointer; /* Menunjukkan pointer saat hover */
}
.custom-checkbox:checked {
    background-color: yellow; /* Warna saat dicentang */
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-light" style="background-color: #a1a0a5 !important; position: fixed; width: 100%; z-index: 1000;">
    <a class="navbar-brand" id="name" style="color: white; margin-left: 20px; cursor: pointer;">Grand Mutiara</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item active">
                <a class="nav-link" id="home" style="color: white; margin-left: 20px;">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="about" style="color: white; margin-left: 20px;">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="login" style="color: white; margin-left: 20px;">Log in</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="regist" style="color: white; margin-left: 20px; margin-right:20px;">Sign in</a>
            </li>
        </ul>
    </div>
</nav>

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
        <section>
            <select class="form-control" id="id_duration" name="id_duration" style="width: 150px;" required>
                <option value="" disabled selected>Durasi Menginap</option>
                <option value="hour">3 Jam</option>
                <option value="hour">12 Jam</option>
                <option value="hour">24 Jam</option>
            </select>
        </section>
    </div>
        
    <div class="form-group mt-2">
        <label for="number_room">No Kamar:</label>
        <div id="number_room" required>
            <?php if (count($number_room) > 0) : ?>
                <?php foreach ($number_room as $number) : ?>
                    <div class="form-check-inline">
                        <input type="radio" id="number<?= htmlspecialchars($number['number_room']); ?>" name="number_room" value="<?= htmlspecialchars($number['number_room']); ?>" required class="custom-checkbox">
                        <label for="number<?= htmlspecialchars($number['number_room']); ?>" class="form-check-label" style="font-size: 20px;"><?= htmlspecialchars($number['number_room']); ?></label>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="alert alert-warning" role="alert">Tidak ada data</div>
            <?php endif; ?>
        </div>
    </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" name="submit" id="submit">Pesan Sekarang</button>
    </form>
</div>

<script src="js/trans.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>