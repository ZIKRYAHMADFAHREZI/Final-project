<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Login</title>
<link rel="icon" type="image/x-icon" href="img/favicon.ico">
<link rel="stylesheet" href="css/style.css"> 
<link rel="stylesheet" href="css/trans.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
    .show-password-icon {
        position: absolute;
        top: 50%;
        right: 30px; /* Pindahkan ikon lebih ke kiri */
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 18px;
        color: #888;
        user-select: none;
    }
    .input-group__input {
        font: inherit;
        color: #000;
        padding: 10px 0px 10px 0px; /* Ruang untuk ikon */
        width: 100%; /* Input disesuaikan dengan lebar container */
        border: 1px solid #ccc;
        padding-left: 10px;
        border-radius: 10px;
        outline: none;
        background-color: #f8f9fa;
        transition: border-color 500ms;
    }
    .error-message {
        color: #dc3545;
        margin-bottom: 15px;
        text-align: center;
        font-size: 14px;
    }
</style>
</head>

<body>
<div class="login-container">
    <h2 class="title">Halaman Login</h2>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
        <div class="error-message">Email, username, atau password salah.</div>
    <?php endif; ?>
    <form action="db/functions/login.php" method="post">
        <div class="input-group">
            <input type="text" name="login" id="login" class="input-group__input" required>
            <label for="login" class="input-group__label">Username atau Email</label>
        </div>
        <div class="input-group">
            <input type="password" name="password" id="password" class="input-group__input" required>
            <label for="password" class="input-group__label">Password</label>
            <span class="show-password-icon" id="toggle-password">&#x1F512;</span>
        </div>
        <div>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember-me">Ingat Saya</label>
        </div>
        <p><a href="forgot_password.php">Lupa Password?</a></p>
        <button type="submit" name="submit" id="login-btn">Login</button>
        <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>
<script>
    // Toggle Password Visibility
    const inputPassword = document.getElementById("password");
    const togglePassword = document.getElementById("toggle-password");

    togglePassword.addEventListener("click", () => {
        const type = inputPassword.getAttribute("type") === "password" ? "text" : "password";
        inputPassword.setAttribute("type", type);
        togglePassword.textContent = type === "password" ? "\u{1F512}" : "\u{1F513}";
    });

    // Input Label Animation
    const inputs = document.querySelectorAll('.input-group__input');
    
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.trim() !== '') {
                input.classList.add('has-value');
            } else {
                input.classList.remove('has-value');
            }
        });

        if (input.value.trim() !== '') {
            input.classList.add('has-value');
        }
    });
</script>
</body>
</html>