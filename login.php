<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="style.css">
</head>

<body>
<div class="login-container">
    <h2 class="title">Halaman Login</h2>
    <!-- <form action="./backend/admin.php" method="post"> -->
        <div class="input-group">
            <input type="email" name="email" id="email" class="input-group__input" required>
            <label for="email" class="input-group__label">Email</label>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="password" class="input-group__input" required>
            <label for="password" class="input-group__label">Password</label>
        </div>
        <button type="submit" name="submit" id="login">Login</button>
        <p>Don't have a account?<a href="register.php">Register</a></p>
    </form>
</div>
</body>
</html>
