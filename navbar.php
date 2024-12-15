<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
<style>
    @font-face {
        font-family: 'Lito';
        src: local('Lito PRINT Italic'), local('Lito-PRINT-Italic'),
            url('fonts/LITOPRINT-Italic.woff2') format('woff2'),
            url('fonts/LITOPRINT-Italic.woff') format('woff'),
            url('fonts/LITOPRINT-Italic.ttf') format('truetype');
        font-weight: normal;
        font-style: italic;
    }
    #brand {
    font-size: 25px;
    color: white;
    font-family: 'Lito', sans-serif;
    font-style: italic;
    margin-left: 0vw;
    }
    /* Responsif untuk layar lebih kecil (misalnya tablet) */
    @media (max-width: 768px) {
        #brand {
            font-size: 24px; /* Ukuran font lebih kecil untuk layar kecil */
            margin-left: 8vw; /* Menyesuaikan margin untuk layar lebih kecil */
        }
    }
    /* Responsif untuk layar ponsel */
    @media (max-width: 480px) {
        #brand {
            font-size: 20px; /* Ukuran font lebih kecil lagi untuk layar ponsel */
            margin-left: 10vw; /* Menyesuaikan margin untuk ponsel */
        }
    }
    .navbar-nav {
        font-size: 20px;
        font-family: 'Merriweather', serif;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-light fixed-top" style="background-color: #a1a0a5 !important; width: 100%; z-index: 1000;">
    <a href="index.php" class="navbar-brand"><img src="img/favicon.ico" alt="navbr" style="width: 30px; margin-left: 20px;"></a>
    <a href="index.php" class="navbar-brand" id="brand">GRAND MUTIARA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item active">
                <a href="index.php" class="nav-link" id="home" style="color: white; margin-left: 20px;">Home</a>
            </li>
            <li class="nav-item">
                <a href="about.php" class="nav-link" id="about" style="color: white; margin-left: 20px;">About</a>
            </li>
            <?php if (isset($_SESSION['id_user'])) : ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="akunMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white; margin-left: 20px; margin-right:20px;">Akun</a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="akunMenu">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                    <li><a class="dropdown-item" href="history.php">History</a></li>
                    <li><a href="#" onclick="confirmLogout();" class="dropdown-item" style="color: red;">Logout</a></li>
                </ul>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a href="login.php" class="nav-link" id="login" style="color: white; margin-left: 20px;">Log in</a>
            </li>
            <li class="nav-item">
                <a href="register.php" class="nav-link" id="regist" style="color: white; margin-left: 20px; margin-right:20px;">Sign in</a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<script>
function confirmLogout() {
    Swal.fire({
        title: "Apakah Anda yakin ingin logout?",
        text: "Anda akan keluar dari akun ini.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, logout!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php'; 
        }
    });
}
</script>
