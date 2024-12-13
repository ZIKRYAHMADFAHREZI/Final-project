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
<!-- font -->
<link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<!-- aos -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- goole font -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Neuton&display=swap" rel="stylesheet">
<style>
    body {
        background-color: #DCDCDC;
    }
    p, .list {
        font-family: 'Cinzel', serif;
    }
    .carousel-item img {
        width: 95%;
        height: auto;
        object-fit: cover; /* Menjaga gambar tetap proporsional */
        aspect-ratio: 4 / 3;
        text-decoration: none;
    }
    @font-face {
        font-family: 'Lito';
        src: local('Lito PRINT Italic'), local('Lito-PRINT-Italic'),
            url('fonts/LITOPRINT-Italic.woff2') format('woff2'),
            url('fonts/LITOPRINT-Italic.woff') format('woff'),
            url('fonts/LITOPRINT-Italic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }
    .title-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        width: 100%;
        height: 100%;
        font-family: 'Lito', sans-serif;
    }
    .title {
        font-size: clamp(2rem, 8vw, 100px);
        color: white;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        font-family: 'Lito', sans-serif;
        text-align: left;
        padding: 0 1rem;
        max-width: 90%;
    }
    @media (max-width: 600px) {
        .title {
            font-size: clamp(1.5rem, 6vw, 80px);
        }
    }
</style>
</head>
<body>
<?php include 'navbar.php'; ?>
<h1 class="text-center" style="padding-top: 70px; font-family: 'Neuton', serif; font-size: 60px;"><b>Detail</b></h1>
<img src="img/<?= $type['img'] ?>_1.jpg" alt="Logo" class="d-block w-100" style="aspect-ratio: 19/8;">
<div data-aos="fade-right" data-aos-offset="300" data-aos-easing="linear">
    <p class="title"><?= ($type['name_type']); ?></p>
</div>
<div class="container">
    <p class="fs-3">GRAND MUTIARA HOTEL: <b class="fs-3"><?= htmlspecialchars($type['name_type']); ?></b></p>
    <?php
            // Memecah fasilitas berdasarkan newline (\n) atau kombinasi \r\n dan \n
            $facilities = preg_split('/\r?\n/', $type['long_description']);
            foreach ($facilities as $facility) {
                // Menghapus spasi ekstra dan menampilkan tiap fasilitas dalam <p>
                $facility = trim($facility);
                if (!empty($facility)) {
                    echo '<p>' . htmlspecialchars($facility) . '</p>';
                }
            }
        ?>
    <h3>Fasilitas</h3>
    <ul class="list">
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
<div id="carouselExample" class="carousel slide mb-5" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/<?= $type['img'] ?>_2.jpg" class="d-block mx-auto" alt="img">
        </div>
        <div class="carousel-item">
            <img src="img/<?= $type['img'] ?>_3.jpg" class="d-block mx-auto" alt="img">
        </div>
        <div class="carousel-item">
            <img src="img/<?= $type['img'] ?>_4.jpg" class="d-block mx-auto" alt="img">
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
<div class="container">
    <!-- Link untuk masuk ke halaman pemesanan -->
    <a href="type.php?id_type=<?= $type['id_type']; ?>" class="btn btn-primary">Pesan Sekarang</a>
</div>
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
<!-- aos  -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>

</body>
</html>