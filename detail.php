<?php
session_start();
require 'db/connection.php';

// Periksa apakah 'id_type' ada dan merupakan angka
if (isset($_GET['id_type']) && is_numeric($_GET['id_type'])) {
    $id_type = intval($_GET['id_type']);
    
    // Menyiapkan query untuk mengambil data berdasarkan id_type
    $stmt = $pdo->prepare("SELECT * FROM types WHERE id_type = :id_type");
    $stmt->bindParam(':id_type', $id_type, PDO::PARAM_INT);
    $stmt->execute();
    
    // Ambil hasilnya
    $type = $stmt->fetch(PDO::FETCH_ASSOC);

    // Jika data tidak ditemukan
    if (!$type) {
        die("Jenis tidak ditemukan.");
    }
} else {
    die("ID jenis tidak valid.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<style>
    body {
        background-color: #DCDCDC;
    }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>
<h1 class="text-center" style="padding-top: 100px;">Detail </h1>
<img src="img/<?= $type['img'] ?>_1.jpg" alt="Logo" class="d-block w-100" style="aspect-ratio: 19/8;">
<div>
    <p>THE GRAND MUTIARA HOTEL: <b><?= htmlspecialchars($type['name_type']); ?></b></p>
    <p><?= htmlspecialchars($type['long_description']); ?></p> <!-- Menampilkan long_description sebagai paragraf -->
    
    <h3>Fasilitas</h3>
    <ul>
        <?php
            // Memecah fasilitas berdasarkan newline (\n) atau kombinasi \r\n dan \n
            $facilities = preg_split('/\r?\n/', $type['fasility']);
            
            foreach ($facilities as $facility) {
                // Menghapus spasi ekstra dan menampilkan tiap fasilitas dalam <li>
                $facility = trim($facility);
                if (!empty($facility)) {
                    echo '<li>' . htmlspecialchars($facility) . '</li>';
                }
            }
        ?>
    </ul>
</div>

        <!-- Gambar Auto Slide -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/<?= $type['img'] ?>_2.jpg" class="d-block mx-auto" alt="img" style="max-width: 90px; aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/<?= $type['img'] ?>_3.jpg" class="d-block mx-auto" alt="img" style="max-width: 90px; aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/<?= $type['img'] ?>_4.jpg" class="d-block mx-auto" alt="img" style="max-width: 90px; aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
    </div>
    <!-- Controls (Optional) -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

    <!-- Link untuk masuk ke halaman pemesanan -->
    <a href="type.php?id_type=<?= $type['id_type']; ?>" class="btn btn-primary">pesan</a>
<?php include 'footer.html'; ?>
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