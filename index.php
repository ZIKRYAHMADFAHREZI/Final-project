<?php 
require 'db/functions/index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
<title>Grand Mutiara</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/indexcrd.css">
<link rel="stylesheet" href="css/trans.css">
<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<!-- Aos -->
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
<!-- goole font -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
<!-- Font Awesome untuk ikon WhatsApp -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>
    body {
        background-color: #DCDCDC;
    }
    h2 {
        font-family: 'Playfair Display', serif;
    }
    p {
        font-family: 'Lora', serif;
    }
    h5 {
        font-family: 'Cinzel', serif;
    }
</style>
</head>
<body>
<?php include 'navbar.php';?>
    <!-- Gambar Auto Slide -->
<div id="carouselExample" class="carousel slide mt-4" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/hotel.jpg" class="d-block w-100" alt="G1" style="aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/hotel1.jpg" class="d-block w-100" alt="G3" style="aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
        <div class="carousel-item">
        <img src="img/hotel2.jpg" class="d-block w-100" alt="G4" style="aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/hotel3.jpg" class="d-block w-100" alt="G5" style="aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/hotel4.jpg" class="d-block w-100" alt="G5" style="aspect-ratio: 19 / 8; text-decoration: none;">
        </div>
</div>
<!-- <p class="carousel-control-prev fw-medium text-black fs-2 font-monospace">hallo</p> -->
<button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
</button>
<button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
</button>
</div>

<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-4">Welcome to Hotel <span id="typing-text" class="typing-effect"></span></h2>
            <p class="text-center mb-4">Tempat Beristirahat dengan Kenyamanan Berkelas, Pelayanan Ramah, dan Fasilitas Berkualitas untuk Pengalaman Menginap yang Tak Terlupakan.</p>

        </div>
    </div>
</div>
<div class="container">
    <div class="row">
    <?php foreach ($types as $type) : ?>
        <div class="col-md-4" data-aos="zoom-in-up" data-aos-duration="500">
            <div class="card mb-4">
                <img src="img/<?= $type["img"]; ?>_1.jpg" class="card-img-top" alt="type" style="object-fit: cover; aspect-ratio: 4 / 3;">
                <div class="card-body">
                    <h5 class="card-title"><b><?= $type["name_type"]; ?></b></h5> 
                    <p class="card-text"><?= $type["description"]; ?></p>
                    <?php $prices = array($type['transit'], $type['12hour'], $type['24hour']); 
                    rsort($prices);
                    ?>
                    <p class="card-price" style="font-weight: bold; color: green; margin-bottom: 20px;">Harga mulai dari Rp<?= number_format($prices[0], 0, '', '.'); ?></p>
                    <a href="detail.php?id_type=<?= $type["id_type"]; ?>" class="btn btn-primary">Lihat Details</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <a href="https://wa.me/6282211879219?text=Halo%20saya%20ingin%20bertanya." class="whatsapp-float" target="_blank">
            <i class="fab fa-whatsapp"></i>
            Hubungi Kami
    </a>
</div>

<?php include 'footer.html'; ?>
<!-- <script src="js/trans.js" defer></script> -->
<script src="js/writeEF.js"></script>  
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
<!-- Aos -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
</script>
</body>
</html>