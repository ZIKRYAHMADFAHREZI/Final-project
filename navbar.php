<nav class="navbar navbar-expand-lg navbar-dark bg-light fixed-top" style="background-color: #a1a0a5 !important; width: 100%; z-index: 1000;">
    <a href="index.php" class="navbar-brand" id="name" style="color: white; margin-left: 20px; cursor: pointer;">Grand Mutiara</a>
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
                <li class="nav-item">
                    <a href="user_profile.php" class="nav-link" id="profil" style="color: white; margin-left: 20px; margin-right:20px;">Profile</a>
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
