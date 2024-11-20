<?php
require 'db/login.php';
// Mulai sesi
session_start();

// Contoh: Periksa apakah pengguna sudah login
// Gunakan variabel session seperti 'user_id' untuk memverifikasi login
$is_logged_in = isset($_SESSION['email']); // Ganti 'user_id' dengan key yang sesuai
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About</title>
<link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
<!-- <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/trans.css">
<link rel="icon" type="png" href="img/logoo.png">
    <style>
        body {
            background-color: #DCDCDC;
        }
    .isi {
        display: flex;
        align-items: center;
    }
    .logo {
        border-radius: 50%;
        margin-right: 70px;
        margin-left: 40px;
        width: 20%; /*Ubah ukuran logo*/
        
    }
    .title {
        text-align: center;
        padding-top: 70px;
    }
    </style>
</head>
<body>
<header>
    <!-- place navbar here -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light" style="background-color: #a1a0a5 !important; position: fixed; width: 100%; z-index: 1000;">
        <a class="navbar-brand" id="name" style="color: white; margin-left: 20px; cursor: pointer;">Grand Mutiara</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" id="home" style="color: white; margin-left: 20px;">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="about" style="color: white; margin-left: 20px;">About</a>
                </li>
                <?php if ($is_logged_in): ?>
                    <!-- Tampilkan menu Profile jika sudah login -->
                    <li class="nav-item">
                        <a class="nav-link" id="profile" style="color: white; margin-left:20px; margin-right: 20px;">Profile</a>
                    </li>
                <?php else: ?>
                    <!-- Tampilkan Login dan Sign in jika belum login -->
                    <li class="nav-item">
                        <a class="nav-link" id="login" style="color: white; margin-left: 20px;">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="regist" style="color: white; margin-left: 20px; margin-right:20px;">Sign in</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

<div id="loading" class="loading">
    <div class="spinner"></div>
    <h2 class="loading-text">GRAND MUTIARA</h2>
</div>
</header>
    <h2 class="title">About</h2>
    <div class="isi">
        <img src="./img/logo.jpg" alt="grand" class="logo">
        <div class="text">
            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Error asperiores vero blanditiis non deserunt ducimus reiciendis. Doloremque porro assumenda odit adipisci dicta nobis. Dicta deleniti ipsam rerum vero consequatur fugiat.</p>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque quam earum dignissimos quis accusamus. Sunt molestias doloremque, consequuntur, a aperiam nobis ea quod voluptatum delectus doloribus odit alias dignissimos magnam!</p>
            <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis dolor maiores voluptas magnam corrupti. Rerum, sed. Quae officiis, veritatis sunt minima accusamus quisquam, iure autem, explicabo eaque necessitatibus nostrum ullam.</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius dicta laudantium sequi cum natus officia facilis, ipsam voluptatem dolorem at consequatur quidem suscipit voluptate accusamus id quaerat porro minima nulla.</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur iste, laborum vitae earum, vel quae iusto voluptas culpa adipisci quasi similique magnam est voluptatibus, obcaecati odit voluptates. Ea, facere alias.</p>
        </div>
    </div>
    <div class="map d-flex justify-content-center">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.7666989766203!2d107.478647!3d-6.4240141999999985!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6972a0a4cfae79%3A0xc6caafd15b4cf7d3!2sHOTEL%20GRAND%20MUTIARA!5e0!3m2!1sid!2sid!4v1731642372502!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

<div class="text-center">
    <h3 style="padding-top: 50px;">Contact</h3>
        <p class="bi bi-envelope fs-5">  hotelgrandmutiara4@gmail.com</p>  
        <p class="bi bi-whatsapp fs-5">  0838-7362-5307</p>
        <p class="bi bi-whatsapp fs-5">  0831-2867-0469</p>
</div>
<footer>
    <div class="text-center py-4">
        <p class="mb-0">&copy; 2024 Grand Mutiara. All right reserved.</p>
        <p>Follow us on:
            <a href="#" class="text-decoration-none">Facebook</a>,
            <a href="#" class="text-decoration-none">Instagram</a>,
            <a href="#" class="text-decoration-none">Twitter</a>
</div>
</footer>
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
<script src="js/trans.js"></script>
</body>
</html>