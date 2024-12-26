<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link rel="stylesheet" href="css/about.css">
<link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
<!-- <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css"> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<!-- goole font -->
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Neuton&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<h1 class="title text-center" style="padding-top: 80px; font-family: 'Neuton', serif;"><b>About</b></h1>
<div class="isi">
    <img src="./img/GRAND MUTIARA.png" alt="grandmutiara" class="logo">
    <div class="container">
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Error asperiores vero blanditiis non deserunt ducimus reiciendis. Doloremque porro assumenda odit adipisci dicta nobis. Dicta deleniti ipsam rerum vero consequatur fugiat.</p>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eaque quam earum dignissimos quis accusamus. Sunt molestias doloremque, consequuntur, a aperiam nobis ea quod voluptatum delectus doloribus odit alias dignissimos magnam!</p>
        <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veritatis dolor maiores voluptas magnam corrupti. Rerum, sed. Quae officiis, veritatis sunt minima accusamus quisquam, iure autem, explicabo eaque necessitatibus nostrum ullam.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Eius dicta laudantium sequi cum natus officia facilis, ipsam voluptatem dolorem at consequatur quidem suscipit voluptate accusamus id quaerat porro minima nulla.</p>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aspernatur iste, laborum vitae earum, vel quae iusto voluptas culpa adipisci quasi similique magnam est voluptatibus, obcaecati odit voluptates. Ea, facere alias.</p>
    </div>
</div>
<div class="map d-flex justify-content-center">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7929.533401069718!2d107.478647!3d-6.424014!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6972a0a4cfae79%3A0xc6caafd15b4cf7d3!2sHOTEL%20GRAND%20MUTIARA!5e0!3m2!1sid!2sid!4v1734060129267!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>

<div class="text-center">
    <h3 style="padding-top: 50px;">Contact</h3>
        <p class="bi bi-envelope fs-5">  hotelgrandmutiara4@gmail.com</p>  
        <p class="bi bi-whatsapp fs-5">  0838-7362-5307</p>
        <p class="bi bi-whatsapp fs-5">  0831-2867-0469</p>
</div>
<?php include 'footer.html'; ?>
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
<!-- <script src="js/trans.js"></script> -->
</body>
</html>