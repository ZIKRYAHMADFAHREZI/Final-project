<!DOCTYPE html>
<html lang="en">
<head>
<title>Grand Mutiara</title>
<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no"
/>

<!-- Bootstrap CSS v5.2.1 -->
<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
/>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/trans.css">
<link rel="icon" type="png" href="./img/logoo.png">
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

</header>
    <!-- Gambar Auto Slide -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/1.jpg" class="d-block w-100" alt="G1" style="aspect-ratio: 19 / 7; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/2.jpg" class="d-block w-100" alt="G2" style="aspect-ratio: 19 / 7; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/3.jpg" class="d-block w-100" alt="G3" style="aspect-ratio: 19 / 7; text-decoration: none;">
        </div>
        <div class="carousel-item">
        <img src="img/kt1.png" class="d-block w-100" alt="G4" style="aspect-ratio: 19 / 7; text-decoration: none;">
        </div>
        <div class="carousel-item">
            <img src="img/kt2.jpg" class="d-block w-100" alt="G5" style="aspect-ratio: 19 / 7; text-decoration: none;">
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
                <p class="text-center mb-5">Jika kamu check ini disini kamu akan dapat hotel ini juga</p>

            </div>
        </div>
    </div>
    <!-- Tiga Card -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="img/kt3.jpg" class="card-img-top" alt="Card 1">
                    <div class="card-body">
                        <h5 class="card-title">Tipe 1</h5>
                        <p class="card-text">Deskripsi Card 1.</p>
                        <a href="./tipe/tipe1.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="img/kt2.jpg" class="card-img-top" alt="Card 2">
                    <div class="card-body">
                        <h5 class="card-title">Tipe 2</h5>
                        <p class="card-text">Deskripsi Card 2.</p>
                        <a href="./tipe/tipe2.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="img/kt3.jpg" class="card-img-top" alt="Card 3">
                    <div class="card-body">
                        <h5 class="card-title">Tipe 3</h5>
                        <p class="card-text">Deskripsi Card 3.</p>
                        <a href="./tipe/tipe3.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="img/kt3.jpg" class="card-img-top" alt="Card 3">
                    <div class="card-body">
                        <h5 class="card-title">Tipe 4</h5>
                        <p class="card-text">Deskripsi Card 3.</p>
                        <a href="./tipe/tipe4.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="img/kt3.jpg" class="card-img-top" alt="Card 3">
                    <div class="card-body">
                        <h5 class="card-title">Tipe 5</h5>
                        <p class="card-text">Deskripsi Card 3.</p>
                        <a href="./tipe/tipe5.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="img/kt3.jpg" class="card-img-top" alt="Card 3">
                    <div class="card-body">
                        <h5 class="card-title">Tipe 6</h5>
                        <p class="card-text">Deskripsi Card 3.</p>
                        <a href="./tipe/tipe6.php" class="btn btn-primary">Pesan</a>
                    </div>
                </div>
            </div>
        </div>
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
<script src="js/trans.js" defer></script>
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
</body>
</html>
