<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
</head>
<link rel="stylesheet" href="style.css">
<body>
    <div class="login-container">
        <h2 class="title">Halaman Register</h2>
        <!-- <form action="./backend/admin.php" method="post"> -->
        <div class="input-group">
                <input type="text" name="username" id="username" class="input-group__input" required>
                <label for="username" class="input-group__label">Username</label>
            </div>
            <div class="input-group">
                <input type="email" name="email" id="email" class="input-group__input" required>
                <label for="email" class="input-group__label">Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" class="input-group__input" required>
                <label for="password" class="input-group__label">Password</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" class="input-group__input" required>
                <label for="password" class="input-group__label">Konfirmasi Password</label>
            </div>
            <button type="submit" name="submit" id="login">Register</button>
            <p>Have an account?<a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>