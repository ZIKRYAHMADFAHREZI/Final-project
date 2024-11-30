<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Sederhana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #007bff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .navbar a:hover {
            background-color: #0056b3;
        }
        .navbar .nav-links {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <h2>My Website</h2>
    </div>
    <div class="nav-links">
        <?php
        // Simulasi status login
        $isLoggedIn = true; // Ubah menjadi false untuk menguji tampilan login

        if ($isLoggedIn): ?>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </div>
</div>

<div class="content">
    <h1>Selamat datang di website kami!</h1>
    <p>Ini adalah contoh halaman dengan navbar dinamis.</p>
</div>

</body>
</html>