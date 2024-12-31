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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <!-- <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- goole font -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Neuton&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/about.css">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="isi">
        <img src="img/GRAND MUTIARA.png" alt="grandmutiara" class="logo">
        <div class="container my-5">
            <section>
                <h1 class="text-center mb-4"><b>About Us</b>
                </h1>
                <p class="text-justify">Hotel Grand Mutiara adalah destinasi akomodasi ideal yang menawarkan kenyamanan
                    dan fasilitas lengkap untuk tamu dengan berbagai kebutuhan. Terletak di lokasi strategis, hotel ini
                    dirancang untuk memberikan pengalaman menginap yang nyaman dan menyenangkan, baik untuk perjalanan
                    bisnis maupun liburan.</p>
            </section>

            <section class="mt-5">
                <h2 class="text-center mb-4">Pilihan Tipe Kamar</h2>
                <div class="row g-4">
                    <!-- deluce AC -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Deluxe AC</h5>
                                <p class="card-text">Kamar Deluxe AC menawarkan kenyamanan maksimal dengan fasilitas
                                    lengkap seperti queen bed, AC, WiFi, TV, shower, closet duduk, perlengkapan mandi
                                    (amenities), serta teras pribadi. Kamar ini juga tersedia dalam opsi smoking room.
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Family Room -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Family Room</h5>
                                <p class="card-text">Ideal untuk keluarga dengan queen bed dan single bed, dilengkapi
                                    AC, WiFi, water heater, closet duduk, amenities, smoking room, dan teras pribadi
                                    untuk kebersamaan yang nyaman.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Superior AC -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Superior AC</h5>
                                <p class="card-text">Fasilitas lengkap seperti queen bed, AC, WiFi, water heater, closet
                                    duduk, amenities, smoking room, dan teras pribadi memberikan kenyamanan modern untuk
                                    pengalaman menginap yang optimal.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Superior Fan -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Superior Fan</h5>
                                <p class="card-text">Dilengkapi dengan queen bed, kipas angin, amenities, smoking room,
                                    dan teras pribadi, tipe ini cocok bagi tamu yang menginginkan kenyamanan dengan
                                    fasilitas dasar.</p>
                            </div>
                        </div>
                    </div>


                    <!-- Standar AC -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Standar AC</h5>
                                <p class="card-text">Pilihan tepat untuk tamu yang mencari fasilitas esensial dengan
                                    queen bed, AC, WiFi, amenities, smoking room, dan teras pribadi untuk bersantai.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Standar Fan -->
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Standar Fan</h5>
                                <p class="card-text">Fasilitas seperti queen bed, kipas angin, amenities, smoking room,
                                    dan teras pribadi menciptakan pengalaman menginap yang sederhana namun tetap nyaman.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-5">
                <h2 class="text-center mb-4">Keunggulan Hotel</h2>
                <ul class="list-group">
                    <li class="list-group-item">Fasilitas modern dengan pendingin ruangan (AC/fan), perlengkapan mandi
                        lengkap, WiFi, dan tempat tidur berkualitas tinggi.</li>
                    <li class="list-group-item">Pilihan smoking room untuk tamu yang merokok tanpa mengurangi kenyamanan
                        ruangan.</li>
                    <li class="list-group-item">Teras pribadi di setiap kamar untuk bersantai dan menikmati pemandangan
                        sekitar.</li>
                    <li class="list-group-item">Lokasi strategis yang dekat dengan pusat hiburan, restoran, dan tempat
                        wisata.</li>
                </ul>
            </section>
        </div>
    </div>
    <div class="map d-flex justify-content-center">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7929.533401069718!2d107.478647!3d-6.424014!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6972a0a4cfae79%3A0xc6caafd15b4cf7d3!2sHOTEL%20GRAND%20MUTIARA!5e0!3m2!1sid!2sid!4v1734060129267!5m2!1sid!2sid"
            width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <div class="text-center">
        <h3 style="padding-top: 50px;">Contact</h3>
        <p class="bi bi-envelope fs-5"> hotelgrandmutiara4@gmail.com</p>
        <p class="bi bi-whatsapp fs-5"> 0838-7362-5307</p>
        <p class="bi bi-whatsapp fs-5"> 0831-2867-0469</p>
    </div>
    <?php include 'footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <!-- <script src="js/trans.js"></script> -->
</body>

</html>