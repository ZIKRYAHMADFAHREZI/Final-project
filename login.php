<?php
session_start();
require 'db/connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = "";

// Login Proses
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        $login = trim(htmlspecialchars($_POST['login'])); // Sanitasi input
        $password = $_POST['password'];
        
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :login OR username = :login");
            $stmt->bindParam(':login', $login);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Set session data
                $_SESSION['id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Remember me
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    $hashed_token = password_hash($token, PASSWORD_DEFAULT); // Token di-hash untuk keamanan
                    $expiry = time() + (30 * 24 * 60 * 60); // 30 hari

                    // Simpan token di database
                    $stmt = $pdo->prepare("UPDATE users SET remember_token = :token WHERE id_user = :id_user");
                    $stmt->execute(['token' => $hashed_token, 'id_user' => $user['id_user']]);

                    // Simpan token ke cookie
                    setcookie('remember_token', $token, $expiry, "/", "", false, true);
                }

                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard/index.php");
                } elseif ($user['role'] === 'user') {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Email, username, atau password salah.";
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $error = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
        }
    } else {
        $error = "Harap isi semua kolom.";
    }
}

// Remember Me Check
if (isset($_COOKIE['remember_token'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as $user) {
            if (password_verify($_COOKIE['remember_token'], $user['remember_token'])) {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect
                if ($user['role'] === 'admin') {
                    header("Location: dashboard/index.php");
                } elseif ($user['role'] === 'user') {
                    header("Location: index.php");
                }
                exit();
            }
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css"> 
<link rel="stylesheet" href="css/trans.css">
<link rel="icon" type="png" href="img/icon.png">
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
    <?php if (!empty($error)): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="" method="post">
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
        <p>Don't have an account? <a href="register.html">Register</a></p>
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
